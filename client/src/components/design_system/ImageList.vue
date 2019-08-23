<template>
    <svg
        :viewBox="`0 0 ${maxWidth} ${fullHeight}`" xmlns="http://www.w3.org/2000/svg"
        version="1.1"
        class="image-list"
        @mouseleave="pointerX = null"
        @mousemove="setPointerPosition">
        <clipPath :id="`images-mask-${_uid}`">
            <rect :x="0" :y="0" :width="imagesWidth" :height="fullHeight" fill="#fff"></rect>
        </clipPath>

        <rect :x="0" :y="0" :width="imagesWidth" :height="fullHeight" fill="transparent" @click="selectImage"></rect>

        <g :clip-path="`url(#images-mask-${_uid})`" class="image-list__items">
            <g
                v-for="(image, index) in computedImages" :key="index"
                :transform="`translate(${image.offsetX}, 0) scale(${image.size / maxSize})`"
                :style="{ transformOrigin: `0px ${maxSize / 2}px` }"
                class="image-list__item">

                <rect :width="maxSize" :height="maxSize" fill="#000" x="0" y="0" opacity=".1" />

                <!-- Warning -->
                <path
                    v-if="image.loadingProgress === null && image.hasWarning"
                    :d="paths.warning.d"
                    :style="{ transformOrigin: `${maxSize / 2}px ${maxSize / 2}px`, transform: `translate(${(maxSize - paths.warning.width) / 2}px, ${(maxSize - paths.warning.height) / 2}px)` }"
                    class="image-list__item-content"
                    fill="#FCFDA1"/>
                <!-- Loading -->
                <g
                    v-if="image.loadingProgress !== null"
                    :opacity="image.opacity"
                    :style="{ transform: `translate(${(maxSize - paths.loading.width) / 2 - 1}px, ${(maxSize - paths.loading.height) / 2 - 1}px) scale(${0.4 * maxSize / paths.loading.width})` }"
                    class="image-list__item-content">
                    <path :d="paths.loading.d" :style="{ transformOrigin: `${paths.loading.width / 2}px ${paths.loading.height / 2}px` }" class="image-list__loading" fill="#444450"/>
                </g>
                <!-- Image -->
                <image
                    v-else-if="image.url"
                    :opacity="image.opacity"
                    v-bind="{'xlink:href': image.url}"
                    :width="maxSize"
                    :height="maxSize"
                    class="image-list__item-content"
                    preserveAspectRatio="xMidYMid slice">
                </image>
                <!-- Blank -->
                <path
                    v-else-if="!image.hasWarning"
                    :opacity="image.opacity"
                    :fill="image.isHovered ? '#777780' : '#3c3d48'"
                    :d="paths.blank.d"
                    :style="{ transformOrigin: `${maxSize / 2}px ${maxSize / 2}px`, transform: `scale(${0.3 * maxSize / paths.blank.width}) translate(${(maxSize - paths.blank.width) / 2}px, ${(maxSize - paths.blank.height) / 2}px)` }"
                    class="image-list__item-content"/>

                <rect v-if="image.isCurrent" :y="maxSize + image.borderSpacing" :width="maxSize" height="2" class="image-list__item-underline"></rect>
            </g>
        </g>

        <g :opacity="hasArrows.left ? 1 : 0" :style="{ transform: `translate(${(20 - paths.arrow.width) / 2}px, ${(maxSize - paths.arrow.height) / 2}px)` }" class="image-list__arrow">
            <path :d="paths.arrow.d" transform="rotate(180)" transform-origin="center" fill="#F5F5F5"/>
        </g>
        <g :opacity="hasArrows.right ? 1 : 0" :style="{ transform: `translate(${imagesWidth - (20 + paths.arrow.width) / 2}px, ${(maxSize - paths.arrow.height) / 2}px)` }" class="image-list__arrow">
            <path :d="paths.arrow.d" fill="#F5F5F5"/>
        </g>

        <g
            v-for="(button, index) in buttons" :key="index"
            :style="{ opacity: enableAdd ? 1 : 0.4, transformOrigin: `0px ${maxSize / 2}px`, transform: `translate(${imagesWidth + spacing}px, 0px) scale(${button.size / maxSize})` }"
            class="image-list__button"
            @click="addImage">

            <rect :width="maxSize" :height="maxSize" fill="#444450" x="0" y="0" />
            <path :style="{ transformOrigin: `${maxSize / 2}px ${maxSize / 2}px`, transform: `scale(${20/19.2}) translate(${(maxSize - paths.plus.width) / 2}px, ${(maxSize - paths.plus.height) / 2}px)` }" :d="paths.plus.d" />
        </g>
    </svg>
</template>

<script>
import * as utils from './helpers/image_list_utils.js'

export default {
    props: {
        images: { type: Array, required: true },
        currentIndex: { type: Number, required: false },
        maxWidth: { type: Number, default: 300 },
        maxSize: { type: Number, default: 60 },
        minSize: { type: Number, default: 30 },
        maxCount: { type: Number, required: false },
        enableAdd: { type: Boolean, default: true },
    },
    data () {
        return {
            currentImages: [],
            pointerX: null,
            startIndex: 0,
            previousWidths: [],
            animationTime: 0,
            currentXOffset: 0,
            currentWidth: 0,
            targetCount: 0,
        }
    },
    computed: {
        count () {
            return this.currentImages.length
        },
        hasArrows () {
            return {
                left: this.startIndex > 0,
                right: this.startIndex + this.numVisible < this.targetCount,
            }
        },
        fullHeight () {
            return this.maxSize + 5 + 2
        },
        maxVisible () {
            return Math.floor(this.maxWidth / (this.minSize + (this.maxSize - this.minSize) * 0.2)) - this.buttonsData.length
        },
        buttonsData () {
            const buttons = []

            if (!this.maxCount || this.targetCount < this.maxCount) {
                buttons.push({})
            }

            return buttons
        },
        numVisible () {
            return Math.min(this.targetCount, this.maxVisible)
        },
        targetWidth () {
            const count = this.targetCount + this.buttonsData.length
            return count === 0 ? 0 : Math.min(this.maxWidth, count * this.maxSize + (count - 1) * this.spacing)
        },
        imagesWidth () {
            return this.currentWidth - this.buttonsWidth
        },
        buttonsWidth () {
            return this.displayWidths.slice(this.count).reduce((s, acc) => s + acc, 0) + this.buttonsData.length * this.spacing
        },
        computedImages () {
            const images = []
            let currentX = 0

            return this.displayWidths.map((size, index) => {
                const image = this.currentImages[index] || {}
                const isDim = index <= this.startIndex && this.hasArrows.left || index >= this.startIndex + this.numVisible - 1 && this.hasArrows.right
                const isCurrent = index === this.currentIndex
                const isHovered = isCurrent || this.hoverIndex === index - this.startIndex
                const data = {
                    size: size,
                    isCurrent: isCurrent,
                    isHovered: isHovered,
                    borderSpacing: size > (this.maxSize + this.minSize) / 2 ? 5 : 3,
                    opacity: (image.hasWarning ? 0.2 : isDim ? 0.3 : 1) * (isHovered ? 1 : 0.8),
                    hasWarning: image.hasWarning,
                    loadingProgress: image.loadingProgress || null,
                    url: image.url || null,
                    offsetX: currentX - this.currentXOffset,
                }
                currentX += size + this.spacing

                return data
            })
        },
        targetWidths () {
            const before = []
            const numBefore = this.startIndex
            for (var i = 0; i < numBefore; i++) {
                before.push(this.minSize)
            }

            const after = []
            const numAfter = this.count - this.startIndex - this.numVisible
            for (var i = 0; i < numAfter; i++) {
                after.push(this.minSize)
            }

            return before.concat(this.visibleWidths.slice(0, this.numVisible)).concat(after).concat(this.visibleWidths.slice(this.numVisible))
        },
        buttons () {
            return this.buttonsData.map((button, index) => {
                const size = this.displayWidths[this.count + index] || this.minSize
                return {
                    ...button,
                    size: size,
                    borderSpacing: size > (this.maxSize + this.minSize) / 2 ? 5 : 3,
                    isHovered: this.hoverIndex === this.numVisible + index,
                }
            })
        },
        visibleWidths () {
            const count = this.numVisible + this.buttonsData.length
            return utils.calculateWidths(count, this.hoverIndex, this.targetWidth - (count - 1) * this.spacing, this.maxSize, this.minSize)
        },
        movingDirection () {
            if (this.pointerX === null) {
                return null
            } else if (this.pointerX < 0.4 * this.maxSize) {
                return -1
            } else if (this.pointerX > this.imagesWidth - 0.4 * this.maxSize && this.pointerX <= this.imagesWidth) {
                return 1
            }
        },
        hoverIndex () {
            if (this.pointerX === null || this.pointerX > this.currentWidth) {
                return null
            }

            const itemCount = this.numVisible + this.buttonsData.length
            const averageWidth = this.currentWidth / itemCount
            return Math.floor(this.pointerX / averageWidth)
        },
        animationRatio () {
            const ease = (t) => 1+(--t)*t*t*t*t

            const totalAnimationTime = 0.5
            return ease(Math.min(1, this.animationTime / totalAnimationTime))
        },
        displayWidths () {
            return utils.transitionWidths(this.previousWidths, this.targetWidths, this.animationRatio)
        },
        targetXOffset () {
            return (this.minSize + this.spacing) * this.startIndex
        },
    },
    watch: {
        images (value, oldValue) {
            this.targetCount = value.length

            this.startIndex = Math.min(this.startIndex, Math.max(0, Math.min(this.maxCount - this.maxVisible, value.length - this.maxVisible)))

            if (value.length < oldValue.length) {
                setTimeout(() => {
                    this.previousWidths = this.previousWidths.slice(0, value.length).concat(this.previousWidths.slice(this.previousWidths.length - this.buttonsData.length))
                    this.currentImages = value
                }, 500)
            } else {
                this.currentImages = value
            }
        },
        targetWidths (newValue, oldValue) {
            const oldValueAligned = oldValue.slice(0, newValue.length - this.buttonsData.length).concat(oldValue.slice(oldValue.length - this.buttonsData.length))
            let equal = true
            for (var i = 0; i < newValue.length; i++) {
                if (Math.abs(newValue[i] - oldValueAligned[i]) > 0.1) {
                    equal = false
                    break
                }
            }
            if (!equal) {
                const previousAnimated = utils.transitionWidths(this.previousWidths, oldValueAligned, this.animationRatio)
                const previous = []
                for (var i = 0; i < newValue.length; i++) {
                    previous.push(previousAnimated[i] || newValue[i])
                }
                this.previousWidths = previous
                this.animationTime = 0
            }
        },
    },
    created () {
        this.spacing = 1

        this.paths = {
            blank: {
                d: 'M 17 21.6 L 1 21.6 C 0.4 21.6 0 21.2 0 20.6 C 0 20 0.4 19.6 1 19.6 L 17 19.6 C 17.6 19.6 18 20 18 20.6 C 18 21.2 17.6 21.6 17 21.6 Z M 15.7 6.9 L 17.3 5.3 C 17.7 4.9 17.7 4.3 17.3 3.9 L 13.7 0.3 C 13.3 -0.1 12.7 -0.1 12.3 0.3 L 10.7 1.9 L 15.7 6.9 Z M 9.3 3.3 L 1.3 11.3 C 1.1 11.5 1 11.7 1 12 L 1 15.6 C 1 16.2 1.4 16.6 2 16.6 L 5.6 16.6 C 5.9 16.6 6.1 16.5 6.3 16.3 L 14.3 8.3 L 9.3 3.3 Z',
                width: 18,
                height: 22,
            },
            warning: {
                d: 'M25.896 22.735L13.763.417c-.302-.556-1.224-.556-1.526 0L.104 22.735a.85.85 0 0 0 .019.847.87.87 0 0 0 .744.418h24.267a.867.867 0 0 0 .743-.418.85.85 0 0 0 .02-.847zM14.486 7.69l-.619 8.583h-1.733l-.619-8.583h2.97zM13 21.425c-.85 0-1.54-.683-1.54-1.526s.69-1.527 1.54-1.527c.851 0 1.541.684 1.541 1.527 0 .843-.69 1.526-1.54 1.526z',
                width: 26,
                height: 24,
            },
            loading: {
                d: 'M5.922 17.457A8.988 8.988 0 0 1 .542 5.922c.965-2.65 3.185-4.715 5.846-5.535l.697 2.297c-1.99.553-3.569 2.085-4.287 4.059-1.231 3.383.505 7.207 3.945 8.459a6.581 6.581 0 0 0 8.459-3.945c1.17-3.213-.326-6.823-3.38-8.254l.99-2.193c4.197 1.974 6.246 6.87 4.645 11.268a8.988 8.988 0 0 1-11.535 5.38z',
                width: 18,
                height: 18,
            },
            arrow: {
                d: 'M2.143 0L0 2l5.714 5.5L0 13l2.143 2L10 7.5',
                width: 10,
                height: 15,
            },
            plus: {
                d: 'M 12 0 L 7.2 0 L 7.2 7.2 L 0 7.2 L 0 12 L 7.2 12 L 7.2 19.2 L 12 19.2 L 12 12 L 19.2 12 L 19.2 7.2 L 12 7.2',
                width: 19,
                height: 19,
            },
        }

        this.currentImages = this.images
        this.targetCount = this.images.length
        this.currentWidth = this.targetWidth
    },
    mounted () {
        let previousTime = Date.now()
        const update = () => {
            const now = Date.now()
            this.update((now - previousTime) / 1000)
            previousTime = now
            this.animationFrameId = requestAnimationFrame(update)
        }
        update()
    },
    destroyed () {
        if (this.animationFrameId) {
            cancelAnimationFrame(this.animationFrameId)
            this.animationFrameId = null
        }
    },
    methods: {
        update (elapsed) {
            const elapsedFactor = elapsed * 80

            this.animationTime += elapsed

            const delta = this.targetXOffset - this.currentXOffset
            if (Math.abs(delta) < 5 && this.movingDirection !== null) {
                this.move(this.movingDirection)
            }

            const minSpeed = 0.5
            const freshDelta = this.targetXOffset - this.currentXOffset
            if (Math.abs(freshDelta) <= minSpeed) {
                this.currentXOffset = this.targetXOffset
            } else {
                this.currentXOffset += elapsedFactor * (0.05 * freshDelta + minSpeed * Math.sign(freshDelta))
            }

            const widthDelta = this.targetWidth - this.currentWidth
            if (Math.abs(widthDelta) <= minSpeed) {
                this.currentWidth = this.targetWidth
            } else {
                this.currentWidth += elapsedFactor * (0.1 * widthDelta + minSpeed * Math.sign(widthDelta))
            }
        },
        setPointerPosition (event) {
            this.pointerX = event.clientX - this.$el.getBoundingClientRect().left
        },
        selectImage (event) {
            this.$root.$emit('tracking-event', { type: 'button', label: 'selectImage', trigger: 'click', data: { index: this.hoverIndex } })
            this.$emit('select', this.hoverIndex)
        },
        move (direction) {
            const newPosition = this.startIndex + direction
            if (newPosition >= 0 && newPosition < this.targetCount - this.numVisible + 1) {
                this.startIndex = newPosition
            }
        },
        addImage () {
            if (this.enableAdd) {
                if (this.targetCount > this.maxVisible) {
                    this.startIndex = this.targetCount - this.maxVisible
                }

                this.$root.$emit('tracking-event', { type: 'button', label: 'addImage', trigger: 'click' })
                this.$emit('add')
            }
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.image-list {
    &__items {
        pointer-events: none;
    }

    &__item {
        cursor: pointer;
    }

    &__item-content {
        transition: opacity @default-transition-time ease;
    }

    &__item-underline {
        fill: @blue;
    }

    &__arrow {
        transition: opacity @default-transition-time ease-out;
        pointer-events: none;
    }

    &__loading {
        animation: spin 1s linear infinite;

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    }

    &__button {
        cursor: pointer;
        fill: @cement;
        &:hover { fill: white; }
    }
}
</style>
