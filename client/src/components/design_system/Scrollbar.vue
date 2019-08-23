<template>
    <div
        :class="[domNames.orientation] | prefix('scrollbar--')"
        class="scrollbar"
        @click="click">
        <div
            v-if="isVisible"
            :style="{ transform: `${domNames.translate}(${handlePosition}px)`, [domNames.size]: `${handleSize}px`}"
            :class="[theme, { passive: passive }] | prefix('scrollbar__handle--')"
            class="scrollbar__handle"
            @mousedown="startDrag">
        </div>
    </div>
</template>

<script>
export default {
    model: {
        prop: 'scrollOffset',
        event: 'scroll',
    },
    props: {
        axis: {
            type: String,
            required: true,
            validator: function (value) {
                return ['x', 'y'].includes(value)
            },
        },
        theme: { type: String, default: 'dark' },
        scrollOffset: { type: Number, required: true },
        container: { type: HTMLElement },
        passive: { type: Boolean, default: false },
    },
    data () {
        return {
            size: 0,
            totalSize: 0,
            scrollbarSize: 0,
        }
    },
    computed: {
        domNames () {
            return {
                size: this.axis === 'x' ? 'width' : 'height',
                maxSize: this.axis === 'x' ? 'maxWidth' : 'maxHeight',
                clientSize: this.axis === 'x' ? 'clientWidth' : 'clientHeight',
                scrollSize: this.axis === 'x' ? 'scrollWidth' : 'scrollHeight',
                scrollOffset: this.axis === 'x' ? 'scrollLeft' : 'scrollTop',
                translate: this.axis === 'x' ? 'translateX' : 'translateY',
                orientation: this.axis === 'x' ? 'horizontal' : 'vertical',
            }
        },
        isVisible () {
            return this.size < this.totalSize
        },
        handlePosition () {
            const containerScrollableAmount = this.totalSize - this.size
            const scrollbarScrollableAmount = this.scrollbarSize - this.handleSize
            return scrollbarScrollableAmount * this.scrollOffset / containerScrollableAmount
        },
        handleSize () {
            return this.scrollbarSize * this.size / this.totalSize
        },
    },
    watch: {
        container () {
            this.setupContainer()
        },
        scrollOffset (value) {
            if (value !== this.lastEmittedValue) {
                this.container[this.domNames.scrollOffset] = value
            }
        },
        isVisible () {
            this.$emit('visible', this.isVisible)
        },
    },
    beforeCreate () {
        this.lastEmittedValue = null
        this.isDragging = false
        this.handleDragRatio = 0.5
        this.canClick = true
        this.updateFPS = 60
    },
    created () {
        this.setupContainer()

        window.addEventListener('mousemove', this.onDrag)
        window.addEventListener('mouseup', this.stopDrag)

        let lastUpdateTimestamp = Date.now()
        const update = () => {
            const newUpdateTimeStamp = Date.now()
            const millisecondsElapsed = Date.now() - lastUpdateTimestamp

            if (millisecondsElapsed > 1000 / this.updateFPS && this.container && this.$el) {
                const size = this.container.style[this.domNames.maxSize] ? Math.max(parseInt(this.container.style[this.domNames.maxSize], 10), this.container[this.domNames.clientSize]) : this.container[this.domNames.clientSize]

                if (size !== this.size) {
                    this.size = size
                }

                const totalSize = this.container[this.domNames.scrollSize]
                if (totalSize !== this.totalSize) {
                    this.totalSize = totalSize
                }

                const scrollbarSize = this.$el.style[this.domNames.maxSize] ? Math.max(parseInt(this.$el.style[this.domNames.maxSize], 10), this.$el[this.domNames.clientSize]) : this.$el[this.domNames.clientSize]
                if (scrollbarSize !== this.scrollbarSize ) {
                    this.scrollbarSize = scrollbarSize
                }

                lastUpdateTimestamp = newUpdateTimeStamp
            }

            if (!this._isDestroyed) {
                requestAnimationFrame(update)
            }
        }

        update()
    },
    beforeDestroy () {
        window.removeEventListener('mousemove', this.onDrag)
        window.removeEventListener('mouseup', this.stopDrag)
    },
    methods: {
        setupContainer () {
            if (this.container && !this.passive) {
                this.container.addEventListener('wheel', (ev) => {
                    window.requestAnimationFrame(() => {
                        const distance = this.axis === 'x' ? ev.deltaX : ev.deltaY
                        this.set(this.scrollOffset + distance)
                    })
                    ev.preventDefault()
                    ev.stopPropagation()
                })
                this.container.addEventListener('scroll', this.updateScrollOffsetOnScrollEvent)
            }
        },
        updateScrollOffsetOnScrollEvent () {
            // this is used to properly update (with native animation) scrollOffset,
            // when scrolling is done programatically (eg.: scrollIntoView, scrollTo, ...)
            const scrollOffset = this.axis === 'x' ? this.container.scrollLeft : this.container.scrollTop
            this.set(scrollOffset)
        },
        click (ev) {
            if (this.canClick) {
                const elementBox = this.$el.getBoundingClientRect()
                const ratio = this.axis === 'x' ? (ev.pageX - elementBox.left) / elementBox.width : (ev.pageY - elementBox.top) / elementBox.height
                this.setHandle(ratio, 0.5)
            }
        },
        startDrag (ev) {
            this.isDragging = true
            this.canClick = false

            const elementBox = this.$el.getBoundingClientRect()
            this.handleDragRatio = this.axis === 'x' ? (ev.pageX - (elementBox.left + this.handlePosition)) / this.handleSize : (ev.pageY - (elementBox.top + this.handlePosition)) / this.handleSize
            this.$emit('start-drag')
        },
        onDrag (ev) {
            if (this.isDragging) {
                const elementBox = this.$el.getBoundingClientRect()
                const ratio = this.axis === 'x' ? (ev.pageX - elementBox.left) / elementBox.width : (ev.pageY - elementBox.top) / elementBox.height
                this.setHandle(ratio, this.handleDragRatio)
            }
        },
        stopDrag (ev) {
            this.isDragging = false
            this.$nextTick(() => {
                this.canClick = true
            })
            this.$emit('stop-drag')
        },
        setHandle (ratio, handleRatio) {
            this.set(ratio * this.totalSize - handleRatio * this.size)
        },
        set (value) {
            const boundedValue = Math.max(0, Math.min(this.totalSize - this.size, value))
            this.container[this.domNames.scrollOffset] = boundedValue
            this.lastEmittedValue = boundedValue
            this.$emit('scroll', boundedValue)
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';

.scrollbar {
    position: absolute;
    user-select: none;

    &--horizontal {
        bottom: 5px;
        left: 0;
        right: 0;
        height: 5px;

        & .scrollbar__handle {
            height: 100%;
        }
    }

    &--vertical {
        right: 5px;
        top: 0;
        bottom: 0;
        width: 5px;

        & .scrollbar__handle {
            width: 100%;
        }
    }

    &__handle {
        border-radius: 5px;

        &--passive {
            transition: transform 100ms;
        }

        &--dark {
            background-color: @charcoal;
        }

        &--light {
            background-color: @cement;
        }
    }
}
</style>
