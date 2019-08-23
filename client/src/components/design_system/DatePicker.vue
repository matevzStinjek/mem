<template>
    <div v-click-outside="clickOutside" :class="[states, theme] | prefix('date-picker--')" class="date-picker" tabindex="0" @focus="openCalendar()">
        <div v-if="label" :class="size | prefix('date-picker__label--')" class="date-picker__label">{{ isEmpty ? '' : label }}</div>
        <div :class="[size, { disabled: disabled }] | prefix('date-picker__date--')" class="date-picker__date" @click="openCalendar">
            <span class="date-picker__date-text">{{ formattedDate }}</span>
            <icon :style="{ width: caretSize }" name="caret" class="icon-appendix"></icon>
        </div>
        <div :class="states | prefix('date-picker__border-overlay--')" class="date-picker__border-overlay"></div>
        <div :class="size | prefix('date-picker__error-message--')" class="date-picker__error-message">{{ error }}</div>

        <div v-if="isOpen" class="date-picker__popup" @keyup.esc.stop="isOpen = false">
            <template v-if="hasInput">
                <date-range-input
                    v-if="isRange"
                    ref="input"
                    :theme="theme"
                    :size="size"
                    :value="value"
                    :min-date="minDate"
                    :max-date="maxDate"
                    :date-format="dateFormat"
                    :date-format-focus="dateFormatFocus"
                    :date-before-min-date-error-message="dateBeforeMinDateErrorMessage"
                    :date-after-max-date-error-message="dateAfterMaxDateErrorMessage"
                    :label="label"
                    :track-name="trackName"
                    class="date-picker__date-input"
                    @input="$emit('input', $event)"
                    @blur="onBlur">
                </date-range-input>
                <date-input
                    v-else
                    ref="input"
                    :theme="theme"
                    :size="size"
                    :value="value"
                    :min-date="minDate"
                    :max-date="maxDate"
                    :date-format="dateFormat"
                    :date-format-focus="dateFormatFocus"
                    :date-before-min-date-error-message="dateBeforeMinDateErrorMessage"
                    :date-after-max-date-error-message="dateAfterMaxDateErrorMessage"
                    :label="label"
                    :track-name="trackName"
                    class="date-picker__date-input"
                    @input="$emit('input', $event)"
                    @blur="onBlur">
                </date-input>
            </template>

            <calendar
                :theme="theme"
                :size="size"
                :value="value"
                :is-range="isRange"
                :min-date="minDate"
                :max-date="maxDate"
                :label="label"
                :track-name="trackName"
                class="date-picker__calendar"
                @input="$emit('input', $event)"
                @confirm="isOpen = false"
                @blur="onBlur"
            ></calendar>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import Calendar from './Calendar.vue'
import DateInput from './DateInput.vue'
import DateRangeInput from './DateRangeInput.vue'
import Icon from './Icon.vue'

export default {
    components: {
        Calendar,
        DateInput,
        DateRangeInput,
        Icon,
    },
    props: {
        theme: { type: String, default: 'dark' },
        size: { type: String, default: 'normal' },
        label: { type: String },
        value: { type: [Date, Object] },
        disabled: { type: Boolean, default: false },
        isRange: { type: Boolean, default: false },
        isValid: { type: Function },
        error: { type: String },
        minDate: { type: Date },
        maxDate: { type: Date },
        hasInput: { type: Boolean, default: true },
        dateFormat: { type: String },
        dateFormatFocus: { type: String, required: false },
        dateBeforeMinDateErrorMessage: { type: String, required: false },
        dateAfterMaxDateErrorMessage: { type: String, required: false },
        trackName: { type: String, default: 'datePicker' },
    },
    data () {
        return {
            isOpen: false,
        }
    },
    computed: {
        states () {
            return {
                disabled: this.disabled,
                valid: this.isValid,
                error: this.error,
            }
        },
        isEmpty () {
            if (this.isRange) {
                return !(this.value && this.value.from && this.value.to)
            }
            return !this.value
        },
        formattedDate () {
            if (this.isEmpty) {
                return this.label || (this.isRange ? 'Set date range' : 'Set date')
            }

            if (this.isRange) {
                const from = this.value && this.value.from ? moment(this.value.from).format(this.dateFormat) : '?'
                const to = this.value && this.value.to ? moment(this.value.to).format(this.dateFormat) : '?'
                return `${from} - ${to}`
            }

            return this.value ? moment(this.value).format(this.dateFormat) : '?'
        },
        caretSize () {
            return this.size === 'condensed' ? '7px' : '12px'
        },
    },
    methods: {
        clickOutside () {
            this.isOpen = false
        },
        openCalendar () {
            if (!this.disabled && !this.isOpen) {
                this.isOpen = true
                if (this.hasInput) {
                    this.$nextTick(() => {
                        this.$refs.input.focus()
                    })
                }
                this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'open-calendar' })
            }
        },
        onBlur (ev) {
            if ((ev.relatedTarget === null || !this.$el.contains(ev.relatedTarget) || ev.relatedTarget === this.$el) && this.isOpen) {
                this.$nextTick(() => {
                    this.isOpen = false
                })
            }
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.date-picker {
    width: 100%;
    position: relative;
    .regular-font();
    outline: none;

    &__label {
        color: @smog;
        font-size: 11px;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        min-height: 13px;

        &--condensed {
            font-size: 10px;
        }

        &--phat {
            font-size: 14px;
        }
    }

    &__date {
        width: 100%;
        font-size: 18px;
        padding-bottom: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;

        &--condensed {
            font-size: 14px;
        }

        &--phat {
            font-size: 22px;
        }

        &--disabled {
            cursor: default;
            color: @cement;
            border-bottom: 2px dashed @cement;
        }
    }

    &__date-text {
        margin-right: 22px;
    }

    &__popup {
        position: absolute;
        top: -200px;
        left: -80px;
        background-color: white;
        padding: 30px 15px;
        box-shadow: 1px 2px 5px 0 rgba(0, 0, 0, 0.25);
        z-index: @z-default;
        outline: none;
    }

    &__date-input {
        margin-bottom: 30px;
    }

    &__calendar {
        margin: 0 auto;
    }

    &__error-message {
        color: @red;
        font-size: 11px;
        min-height: 14px;
        text-align: left;

        &.date-picker__error-message--condensed {
            font-size: 10px;
        }

        &.date-picker__error-message--phat {
            font-size: 12px;
        }
    }

    &__border-overlay {
        position: relative;
        top: -2px;
        margin-bottom: 1px;
        width: 0;
        height: 2px;
        transition: width 0.25s ease-out;

        &--valid {
            width: 100%;
            background-color: @green;
        }

        &--error {
            width: 100%;
            background-color: @red;
        }

        &--disabled {
            opacity: 0;
            width: 100%;
            background-color: @charcoal;
        }
    }
}

.icon-appendix.icon-wrapper {
    position: relative;
    margin: 0 4px 0 auto;
    top: 1px;
}

/* DARK THEME */
.date-picker--dark {
    .date-picker__date {
        color: @white;
        border-bottom: 2px solid @charcoal;
    }
}

/* LIGHT THEME */
.date-picker--light {
    .date-picker__date {
        color: @charcoal;
        border-bottom: 2px solid @cement;
    }
}
</style>

<style lang="less">
.date-picker {
    &__popup {
        .input-field__overlay {
            display: none;
        }

        .input-field__label-text {
            visibility: hidden;
        }
    }
}
</style>
