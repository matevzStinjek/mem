<template>
    <div :class="['radio-element--' + size, 'radio-element--' + theme]" :data-id="value | slugify" :title="labelText" class="radio-element" tabindex="0" @keyup.enter.stop="click" @keyup.space.prevent.stop="click" @focus="setFocus(true)" @blur="setFocus(false)" @keyup.esc.stop="blur">
        <div :class="states | prefix('radio-element__radio-row--')" :title="labelText" :data-testilda-checked="states.checked" class="radio-element__radio-row" @click="click">
            <div :class="states | prefix('radio-element__oval-wrapper--')" class="radio-element__oval-wrapper">
                <slot name="icon">
                    <div :class="states | prefix('radio-element__oval--')" class="radio-element__oval"></div>
                    <div :class="states | prefix('radio-element__inner-oval--')" class="radio-element__inner-oval"></div>
                </slot>
            </div>

            <div :class="states | prefix('radio-element__label-text--')" class="radio-element__label-text">
                {{ labelText }}
            </div>
        </div>

        <div v-if="infoText.length > 0" :class="states | prefix('radio-element__helper-text--')" class="radio-element__helper-text">
            {{ infoText }}
        </div>
    </div>
</template>

<script>
export default {
    model: {
        prop: 'selectedValue',
    },
    props: {
        value: { type: String, required: true },
        selectedValue: { type: String, required: true },
        disabled: { type: Boolean, default: false },
        size: { type: String, required: false, default: 'normal' },
        theme: { type: String, required: false, default: 'dark' },
        helperText: { type: String, required: false, default: '' },
        disabledText: { type: String, required: false, default: '' },
        warningText: { type: String, required: false, default: '' },
        errorText: { type: String, required: false, default: '' },
        trackName: { type: String, required: false },
        trackType: { type: String, default: 'input' },
    },
    data () {
        return {
            focused: false,
        }
    },
    computed: {
        labelText () {
            return this.disabled && this.disabledText.length > 0 ? this.disabledText : (this.$slots.default && this.$slots.default[0].text.replace(/^\s+|\s+$/g, '') || '')
        },
        infoText () {
            if (this.errorText) {
                return this.errorText
            } else if (this.warningText) {
                return this.warningText
            } else {
                return this.helperText ? this.helperText : ''
            }
        },
        states () {
            return {
                error: !!this.errorText,
                warning: !!this.warningText && !this.errorText,
                disabled: this.disabled,
                checked: this.selectedValue === this.value,
                focused: this.focused,
            }
        },
    },
    methods: {
        setFocus (isFocused) {
            this.focused = isFocused
            if (isFocused) {
                this.$root.$emit('tracking-event', { type: this.trackType, label: this.trackName || this.labelText, trigger: 'focus' })
                this.$emit('focus')
            }
        },
        blur () {
            this.$el.blur()
        },
        click () {
            if (!this.disabled) {
                this.$root.$emit('tracking-event', { type: this.trackType, label: this.trackName || this.labelText, trigger: 'click', data: { value: this.value } })
                this.$emit('input', this.value)
                this.$emit('focus')
                this.focused = false
            }
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

* {
    box-sizing: border-box;
}

.radio-element {
    height: 26px + 12px;
    margin-top: 7px;
    .regular-font();

    .radio-element__radio-row {
        height: 26px;
        display: flex;
        align-items: center;
        cursor: pointer;

        &--disabled {
            cursor: auto;
        }

        &--focused,
        &:hover {
            .radio-element__oval-wrapper:not(.radio-element__oval-wrapper--checked):not(.radio-element__oval-wrapper--disabled) {
                svg > * {
                    stroke: @cement;
                    fill: @cement;
                }
            }

            .radio-element__oval-wrapper:not(.radio-element__oval-wrapper--disabled) > svg {
                transform: scale3d(1.2, 1.2, 1);
            }

            .radio-element__oval:not(.radio-element__oval--checked):not(.radio-element__oval--disabled) {
                border-color: @cement;
            }

            .radio-element__oval:not(.radio-element__oval--disabled) {
                transform: scale3d(1.25, 1.25, 1);
            }

            .radio-element__label-text:not(.radio-element__label-text--checked):not(.radio-element__label-text--disabled) {
                color: @white;
            }

            .radio-element__inner-oval:not(.radio-element__inner-oval--disabled) {
                transform: scale3d(1.5, 1.5, 1);
            }
        }
    }

    .radio-element__oval-wrapper {
        width: 24px;
        height: 24px;
        display: flex;
        position: relative;
        align-items: center;
        justify-content: center;

        svg {
            transition: transform @default-transition-time ease-out;
            width: 20px;
            height: 20px;
        }

        svg > * {
            stroke: @cement;
            fill: @cement;
            transition: fill @default-transition-time ease-out, stroke @default-transition-time ease-out;
        }

        &--checked {
            svg > * {
                stroke: @blue;
                fill: @blue;
            }
        }

        &--disabled {
            svg > * {
                stroke: @charcoal;
                fill: @charcoal;
            }
        }
    }

    .radio-element__oval {
        width: 16px;
        height: 16px;
        border-width: 1px;
        border-style: solid;
        border-radius: 100%;
        border-color: @smog;
        transition: transform @default-transition-time ease-out, border-color @default-transition-time ease-out;

        &--checked {
            border-color: @blue;
        }

        &--disabled {
            border-color: @charcoal;
        }
    }

    .radio-element__inner-oval {
        /* Some browsers (Safari) won't center an absolutely positioned div with flex */
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        width: 8px;
        height: 8px;
        border-radius: 100%;
        background-color: @smog;
        transition: all @default-transition-time ease-out;
        transform: scale3d(0, 0, 1);
        opacity: 0;

        &--checked {
            background-color: @blue;
            transform: scale3d(1, 1, 1);
            opacity: 1;
        }

        &--disabled {
            background-color: @charcoal;
        }
    }

    .radio-element__label-text {
        padding: 0 15px 0 11px;
        white-space: nowrap;
        color: @cement;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 18px;
        .regular-font();
        transition: color @default-transition-time ease-out;
        height: 100%;

        &--error {
            color: @red;
        }

        &--checked {
            color: @blue;
        }

        &--disabled {
            color: @charcoal;
        }
    }

    .radio-element__helper-text {
        height: 12px;
        color: @ash;
        padding-left: 35px;
        display: flex;
        align-items: flex-start;
        font-size: 11px;
        letter-spacing: 0.5px;

        &--warning {
            color: @yellow;
        }

        &--disabled {
            color: @charcoal;
        }

        &--error {
            color: @red;
        }
    }

    &:focus {
        outline: none;
    }
}

.radio-element--phat {
    height: 34px + 17px;
    margin-top: 9px;

    .radio-element__radio-row {
        height: 34px;

        &--focused,
        &:hover {
            .radio-element__oval:not(.radio-element__oval--disabled) {
                transform: scale3d(1.333, 1.333, 1);
            }

            .radio-element__inner-oval:not(.radio-element__inner-oval--disabled) {
                transform: scale3d(1.4, 1.4, 1);
            }
        }
    }

    .radio-element__oval-wrapper {
        width: 30px;
        height: 30px;
    }

    .radio-element__oval {
        width: 18px;
        height: 18px;
        border-radius: 100%;
    }

    .radio-element_inner-oval {
        width: 10px;
        height: 10px;
        border-radius: 100%;
    }

    .radio-element__label-text {
        padding: 0 15px 0 9px;
        font-size: 22px;
    }

    .radio-element__helper-text {
        height: 17px;
        padding-left: 39px;
        align-items: center;
        font-size: 12px;
    }
}

.radio-element--condensed {
    height: 20px;
    margin-top: 10px;

    .radio-element__helper-text {
        height: 15px;
        padding-left: 27px;
        align-items: flex-end;
        font-size: 10px;
        letter-spacing: 0.5px;
    }

    .radio-element__radio-row {
        height: 20px;

        &--focused,
        &:hover {
            .radio-element__oval:not(.radio-element__oval--disabled) {
                transform: translateY(-1px) scale3d(1.286, 1.286, 1);
            }

            .radio-element__inner-oval:not(.radio-element__inner-oval--disabled) {
                transform: translateY(-1px) scale3d(1.667, 1.667, 1);
            }
        }
    }

    .radio-element__oval-wrapper {
        width: 20px;
        height: 20px;
    }

    .radio-element__oval {
        transform: translateY(-1px);
        width: 14px;
        height: 14px;
        border-radius: 100%;
    }

    .radio-element__inner-oval {
        transform: translateY(-1px);
        width: 6px;
        height: 6px;
        border-radius: 100%;
    }

    .radio-element__label-text {
        padding: 0 10px 0 7px;
        font-size: 14px;
    }
}

.radio-element--light.radio-element--light {
    .radio-element__radio-row {
        .radio-element__label-text {
            color: @charcoal;

            &--disabled {
                color: @cement;
            }
        }

        &--focused,
        &:hover {
            .radio-element__oval:not(.radio-element__oval--checked):not(.radio-element__oval--disabled) {
                border-color: @ink;
            }

            .radio-element__label-text:not(.radio-element__label-text--checked):not(.radio-element__label-text--disabled) {
                color: @charcoal;
            }
        }
    }
}
</style>
