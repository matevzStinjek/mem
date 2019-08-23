<template>
    <div :class="[theme, size] | prefix('slider--')" class="slider">
        <div class="slider__row">
            <div class="slider__label">
                {{ label }}
            </div>
        </div>

        <div class="slider__row">
            <div v-if="!isRange" class="slider__input">
                <input-element
                    :type="isWholeNumber ? 'number' : 'float'"
                    :size="size"
                    :disabled="disabled"
                    :value="value"
                    :min-number-cap="0"
                    :max-number-cap="max"
                    :is-valid="isValidInput"
                    :theme="theme"
                    :alignment="alignment"
                    :locale="locale"
                    :decimal-precision="decimalPrecision"
                    :track-name="trackName"
                    @input="handleInput"
                    @keydown.up.stop="handleStepIncrease"
                    @keydown.down.stop="handleStepDecrease"
                ><span v-if="unit" slot="right">{{ unit }}</span></input-element>
            </div>

            <div ref="bar" :class="{disabled: disabled, changed: isChanged, dragging: isDragging, 'dragging--left': draggingSide === 'left', 'dragging--right': draggingSide === 'right'} | prefix('slider-bar--')" class="slider-bar" tabindex="0" @mousedown="startDrag" @keydown.left.stop="handleStepDecrease" @keydown.down.stop="handleStepDecrease" @keydown.right.stop="handleStepIncrease" @keydown.up.stop="handleStepIncrease" @keyup.left.stop @keyup.right.stop>
                <div class="slider-bar__container">
                    <div class="slider-ruler">
                        <div ref="min" :class="labelsActiveClass.min | prefix('slider-ruler__label--')" class="slider-ruler__label">{{ minLabelValue }}</div>

                        <div class="slider-ruler__ticks">
                            <div v-for="n in ticksCount" :class="tickClass(n) | prefix('slider-ruler__tick--')" :key="n" class="slider-ruler__tick"></div>
                        </div>

                        <div ref="max" :class="labelsActiveClass.max | prefix('slider-ruler__label--')" class="slider-ruler__label">{{ maxLabelValue }}</div>
                    </div>

                    <div class="slider-bar__rail">
                        <div class="slider-rail slider-rail--passive"></div>
                        <div :class="{'slider-rail--exceeds': limit && value > limitValue}" :style="railStyle" class="slider-rail slider-rail--active"></div>
                        <div :style="{left: `${position}px`}" class="slider-rail__handle slider-rail__handle--left">
                            <div class="slider-rail__handle-dot slider-rail__handle-dot--left"></div>
                        </div>
                        <div v-if="isRange" :style="{left: `${positionRight}px`}" class="slider-rail__handle slider-rail__handle--right">
                            <div class="slider-rail__handle-dot slider-rail__handle-dot--right"></div>
                        </div>
                    </div>
                </div>

                <div class="slider-helper-text"></div>
            </div>
        </div>
    </div>
</template>

<script>
import Input from './Input.vue'

export default {
    components: {
        inputElement: Input,
    },
    props: {
        min: { type: Number },
        max: { type: Number },
        step: { type: Number },
        value: { type: [Number, Object] },
        isRange: { type: Boolean, default: false },
        label: { type: String },
        theme: { type: String, default: 'dark' },
        size: { type: String, default: 'normal' },
        alignment: { type: String, default: 'left' },
        locale: { type: String, default: 'en-US' },
        limit: { type: Number, required: false },
        minLabel: { type: String, required: false },
        maxLabel: { type: String, required: false },
        unit: { type: String, required: false },
        disabled: { type: Boolean, default: false },
        trackName: { type: String, required: false },
    },
    data () {
        return {
            isDomReady: false,
            isChanged: false,
            isDragging: false,
            draggingSide: null,
            lastDraggedSide: null,
        }
    },
    computed: {
        limitValue () {
            return this.limit || this.max
        },
        minLabelValue () {
            return this.minLabel || this.min.toLocaleString(this.locale)
        },
        maxLabelValue () {
            return this.maxLabel || this.limitValue.toString().toLocaleString(this.locale)
        },
        stepsCount () {
            return (this.limitValue - this.min) / this.step
        },
        position () {
            return this.value ? this.calculatePosition(this.isRange ? this.value.min : this.value) : 0
        },
        positionRight () {
            return this.isRange ? this.calculatePosition(this.value ? this.value.max : this.max) : null
        },
        sliderWidth () {
            return this.isDomReady ? this.$refs.bar.clientWidth : 0
        },
        sliderOffset () {
            return this.isDomReady ? this.$refs.bar.getBoundingClientRect().x : 0
        },
        decimalPrecision () {
            const decimals = this.step.toString().split('.')[1]
            return decimals ? decimals.length : 0
        },
        isWholeNumber () {
            return this.decimalPrecision === 0
        },
        activeIndexRange () {
            const tickIndex = (value) => (value - this.min) / (this.limitValue - this.min) * (this.ticksCount - 1)

            if (this.isRange) {
                const indexFrom = this.value ? tickIndex(this.value.min) : tickIndex(this.min)
                const indexTo = this.value ? tickIndex(this.value.max) : tickIndex(this.max)
                return [indexFrom, indexTo]
            } else {
                const indexTo = tickIndex(this.value || 0)
                return [0, indexTo]
            }
        },
        labelsActiveClass () {
            const isNormalSize = this.size === 'normal'

            return {
                min: { active: this.activeIndexRange[0] === 0 && isNormalSize },
                max: { active: this.activeIndexRange[1] >= this.ticksCount - 1 && isNormalSize },
            }
        },
        railStyle () {
            return  {
                width: `${this.isRange ? this.positionRight - this.position : this.position}px`,
                left: `${this.isRange ? this.position : 0}px`,
            }
        },
    },
    beforeCreate () {
        this.ticksCount = 11
    },
    created () {
        if (this.decimalPrecision > 1) {
            throw new Error('Max one decimal place is supported.')
        }

        const validateStep = value => {
            return (value - this.min) / this.step % 1 === 0
        }

        const isStepValid = validateStep(this.max)
        if (!isStepValid) {
            throw new Error('Difference between max and min is not divisible by step.')
        }

        if (this.value) {
            let isValueValid, isValueInRange
            if (this.isRange) {
                if (this.value.min > this.value.max) {
                    throw new Error('Starting range (value[0]) can\'t be greater than ending range (value[1])')
                }

                isValueValid = validateStep(this.value.min) && validateStep(this.value.max)
                isValueInRange = this.value.min >= this.min || this.value.max <= this.max
            } else {
                isValueValid = validateStep(this.value)
                isValueInRange = this.value >= this.min || this.value <= this.max
            }

            if (!isValueValid) {
                throw new Error('Value is not a valid step.')
            }

            if (!isValueInRange) {
                throw new Error('Value must be between min and max.')
            }
        } else {
            this.$emit('input', this.isRange ? { min: this.min, max: this.max } : this.min)
        }
    },
    mounted () {
        this.isDomReady = true
    },
    methods: {
        calculatePosition (value) {
            const index = (value - this.min) / this.step
            return Math.max(0, Math.min(index, this.stepsCount)) / this.stepsCount * this.sliderWidth
        },
        calculateValue (clientX) {
            let ratio = (clientX - this.sliderOffset) / this.sliderWidth
            ratio = Math.min(Math.max(0, ratio), 1)
            const edgeStepOffset = 0.5 / this.stepsCount

            return (Math.floor((ratio - edgeStepOffset) * this.stepsCount) + 1) * this.step + this.min
        },
        roundValue (value) {
            const roundingFactor = Math.pow(10, this.decimalPrecision)
            return Math.round(value * roundingFactor) / roundingFactor
        },
        startDrag (e) {
            if (this.disabled) {
                return
            }

            this.isChanged = true
            this.isDragging = true

            const clickedValue = this.calculateValue(e.clientX)
            // if slider isn't a range slider draggingSide defaults to left, else check for the closer knob. if both knobs are the same, it defautls to the left one, hence an additional check is needed
            const getCloserKnob = (value) => (this.isRange && (Math.abs(this.value.max - value) < Math.abs(this.value.min - value)) || this.value.max < value) ? 'right' : 'left'
            this.draggingSide = this.lastDraggedSide = getCloserKnob(clickedValue)

            this.setPosition(e)
            window.addEventListener('mouseup', this.stopDrag)
            window.addEventListener('mousemove', this.setPosition)
            this.$refs.bar.focus()
        },
        setPosition (e) {
            const value = this.calculateValue(e.clientX)
            const roundedValue = this.roundValue(value)

            if (this.isRange) {
                // const boundedRoundedValue = Math.min(Math.max(roundedValue, this.value.min), this.value.max)
                if (this.draggingSide === 'left') {
                    if (roundedValue <= this.value.max) {
                        this.$emit('input', { min: roundedValue, max: this.value.max })
                    } else {
                        this.$emit('input', { min: this.value.max, max: roundedValue })
                        this.draggingSide = 'right'

                    }
                } else {
                    if (roundedValue > this.value.min) {
                        this.$emit('input', { min: this.value.min, max: roundedValue })
                    } else {
                        this.$emit('input', { min: roundedValue, max: this.value.min })
                        this.draggingSide = 'left'
                    }
                }
            } else {
                this.$emit('input', roundedValue)
            }

            e.preventDefault()
        },
        stopDrag () {
            this.isDragging = false
            this.draggingSide = null
            window.removeEventListener('mouseup', this.stopDrag)
            window.removeEventListener('mousemove', this.setPosition)
        },
        handleStepIncrease () {
            if (this.isRange) {
                if (this.lastDraggedSide === 'left') {
                    if (this.value.min < this.value.max) {
                        const incrementedNumber = this.incrementNumber(this.value.min, this.min)
                        this.handleInput({ min: incrementedNumber, max: this.value.max })
                    }
                } else {
                    if (this.value.max < this.max) {
                        const incrementedNumber = this.incrementNumber(this.value.max, this.value.min)
                        this.handleInput({ min: this.value.min, max: incrementedNumber })
                    }
                }
            } else {
                if (this.value < this.max) {
                    const incrementedNumber = this.incrementNumber(this.value, this.min)
                    this.handleInput(incrementedNumber)
                }
            }
        },
        handleStepDecrease () {
            if (this.isRange) {
                if (this.lastDraggedSide === 'left') {
                    if (this.value.min > this.min) {
                        const decrementedNumber = this.decrementNumber(this.value.min, this.value.max)
                        this.handleInput({ min: decrementedNumber, max: this.value.max })
                    }
                } else {
                    if (this.value.max > this.value.min) {
                        const decrementedNumber = this.decrementNumber(this.value.max, this.max)
                        this.handleInput({ min: this.value.min,  max: decrementedNumber })
                    }
                }
            } else {
                if (this.value > this.min) {
                    const decrementedNumber = this.decrementNumber(this.value, this.max)
                    this.handleInput(decrementedNumber)
                }
            }
        },
        incrementNumber (value, limit) {
            this.isChanged = true
            return (value < limit) ? limit : this.roundValue(value + this.step)
        },
        decrementNumber (value, limit) {
            this.isChanged = true
            return (value > limit) ? limit : this.roundValue(value - this.step)
        },
        handleInput (value) {
            this.$emit('input', value)
        },
        isValidInput (value) {
            return (value === undefined || value === null  || value < this.min || value > this.max) ? '' : null
        },
        tickClass (index) {
            let hidden = false
            if (this.isDomReady) {
                const labelPadding = 6
                const position = (index - 1) / (this.ticksCount - 1) * this.sliderWidth
                // threshold for ticks disappearing under labels
                const minThreshold = this.$refs.min.clientWidth + labelPadding
                const maxThreshold = this.sliderWidth - this.$refs.max.clientWidth - labelPadding
                hidden = position <= minThreshold || position >= maxThreshold
            }

            return {
                'active': index - 1 >= this.activeIndexRange[0] && index - 1 <= this.activeIndexRange[1],
                'hidden': hidden,
                'middle': index === (this.ticksCount + 1) / 2,
            }
        },
    },
}
</script>

<style lang="less">
@import (reference) './less/common';
@import './less/typography';

@opening-animation-time-content: 0.2s;

.slider {
    .regular-font();
    margin-bottom: 15px;
    height: 60px;

    &__row {
        display: flex;
    }

    &__label {
        height: 13px;
        font-size: 11px;
        color: @ash;
        .regular-font();
    }

    &__input {
        width: 50px;
        margin-right: 10px;
    }
}

.slider-helper-text {
    height: 17px;
}

.slider-ruler {
    position: relative;
    display: flex;
    justify-content: space-between;
    padding-top: 8px;

    &__tick {
        width: 1px;
        height: 4px;
        margin-top: 3px;
        background-color: @charcoal;
        transition: background-color @default-transition-time ease-out;

        &--hidden {
            visibility: hidden;
        }

        &--middle {
            height: 8px;
        }
    }

    &__label {
        color: @ash;
        font-size: 10px;
        .regular-font();
        transition: color @default-transition-time ease-out;
    }

    &__ticks {
        position: absolute;
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
}

.slider-rail {
    height: 2px;
    position: absolute;

    &--active {
        background-color: @blue;
        width: 0;
    }

    &--passive {
        background-color: @charcoal;
        width: 100%;
        transition: background-color @default-transition-time ease-out;
    }

    &--exceeds {
        background: linear-gradient(90deg, @blue 0%, @blue 100%);
    }

    &__handle {
        position: absolute;
        bottom: -1px;
        left: 0;
        transform: translateX(-50%) translateY(50%);
        width: 14px;
        height: 14px;
        box-sizing: border-box;
        border-radius: 50%;
        background-color: @cement;
        border: 2px solid @void;
        transition: transform @opening-animation-time-content ease-out;

        &-dot {
            width: 2px;
            height: 2px;
            border-radius: 50%;
            background-color: @void;
            position: absolute;
            bottom: 0;
            top: 0;
            right: 0;
            left: 0;
            margin: auto;
            opacity: 0;
            transition: opacity @opening-animation-time-content ease-out;
        }
    }
}

.slider-bar {
    flex-grow: 1;
    min-width: 190px;
    height: 47px;
    outline: none;
    cursor: pointer;

    &__container {
        position: relative;
        height: 29px;
    }

    &__rail {
        width: 100%;
        position: absolute;
        bottom: 0;
    }

    &--changed .slider-rail__handle {
        background-color: @white;
    }

    &:not(.slider-bar--disabled):hover {
        .slider-ruler__tick {
            background-color: @ash;
        }

        .slider-rail--passive {
            background-color: @smog;
        }
    }

    &--dragging:not(.slider-bar--disabled):focus {
        .slider-ruler__tick--active {
            background-color: @blue;
        }

        .slider-ruler__label--active {
            color: @blue;
        }

        .slider-rail--passive {
            background-color: @charcoal;
        }
    }

    &--dragging--left:not(.slider-bar--disabled):focus {
        .slider-rail__handle {
            &--left {
                transform: scale(2, 2) translate(-3.5px, 3.5px);
            }

            &-dot--left {
                opacity: 1;
            }
        }
    }

    &--dragging--right:not(.slider-bar--disabled):focus {
        .slider-rail__handle {
            &--right {
                transform: scale(2, 2) translate(-3.5px, 3.5px);
            }

            &-dot--right {
                opacity: 1;
            }
        }
    }

    &--disabled {
        cursor: default;

        .slider-ruler__label {
            color: @charcoal;
        }

        .slider-rail {
            &--active {
                background-color: @smog;
            }

            &__handle {
                background-color: @cement;
            }

            &--exceeds {
                background: linear-gradient(90deg, @smog 0%, @cement 100%);
            }
        }
    }
}

// sizes
.slider--condensed {
    height: 46px;

    .slider {
        &__label {
            font-size: 10px;
        }

        &__input {
            order: 1;
            margin-right: 0;
            margin-left: 10px;
        }
    }

    .slider-bar {
        height: 33px;
        max-width: 320px;
        min-width: 140px;

        &__container {
            height: 20px;
        }
    }

    .slider-ruler {
        padding-top: 4px;

        &__ticks {
            display: none;
        }
    }

    .slider-helper-text {
        height: 12px;
    }
}

// themes
.slider--light {
    .slider-rail {
        &--passive {
            background-color: @cement;
        }

        &__handle {
            background-color: @void;
            border-color: @white;

            &-dot {
                background-color: @white;
            }
        }
    }

    .slider-ruler {
        &__label {
            color: @smog;
        }

        &__tick {
            background-color: @cement;
        }
    }

    .slider-bar {
        &:not(.slider-bar--disabled):hover {
            .slider-ruler {
                &__label {
                    color: @smog;
                }

                &__tick {
                    background-color: @smog;
                }
            }
        }

        &--dragging:not(.slider-bar--disabled):focus {
            .slider-ruler__tick--active {
                background-color: @blue;
            }

            .slider-ruler__label--active {
                color: @blue;
            }

            .slider-rail {
                &--passive {
                    background-color: @smog;
                }
            }
        }

        &--disabled {
            .slider-ruler__label {
                color: @cement;
            }

            .slider-rail {
                &--active {
                    background-color: @smog;
                }

                &__handle {
                    background-color: @cement;
                }
            }
        }
    }
}
</style>
