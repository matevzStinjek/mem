<template>
    <div>
        <div v-for="step in calculationSteps" ref="calculationSteps" :key="step.id + '-hidden'" :class="{ 'active': step.isActive, 'green': isValid !== false } | prefix('dialog-header__element--')" class="dialog-header__element dialog-header__element--calculation">{{ step.label | middleEllipsis(32) }}</div>

        <div :class="['dialog-header--' + theme, 'dialog-header--' + dialogViewState]" class="dialog-header">
            <div v-show="stepIndex > 0 && hasBackButton" ref="backButton" class="dialog-header__back" tabindex="0" @click="previousStep" @keyup.enter.stop="previousStep">
                <icon class="dialog-header__back-svg" name="left-arrow" />
            </div>

            <div v-show="showHeader" ref="headerContent" class="dialog-header__content" :class="{ 'dialog-header__content--single-step': shownSteps.length === 1 }" :style="shownSteps.length > 1 ? headerStyle : null">
                <div v-for="step in shownSteps" :id="step.id" :key="step.id" :class="{ 'active': step.isActive, 'green': isValid !== false, 'single-step': shownSteps.length === 1 } | prefix('dialog-header__element--')" class="dialog-header__element">{{ step.label | middleEllipsis(shownSteps.length > 1 ? 32 : step.label.length) }}</div>
            </div>

            <div v-show="hasCloseButton" ref="closeButton" :class="{'dialog-header__close--light': theme === 'light'}" class="dialog-header__close" tabindex="0" @click="closeDialog" @keyup.enter.stop="closeDialog">
                <icon class="dialog-header__close-svg" name="close" />
            </div>
        </div>
    </div>
</template>

<script>
import Icon from './Icon.vue'

export default {
    components: {
        Icon,
    },
    props: {
        theme: { type: String, default: 'dark' },
        dialogViewState: { type: String, required: true },
        steps: { type: Array, required: true },
        currentStepId: { type: String, required: false },
        isValid: { type: Boolean, default: false },
        hasBackButton: { type: Boolean, default: true },
        hasCloseButton: { type: Boolean, default: true },
    },
    data () {
        return {
            headerOffset: null,
        }
    },
    computed: {
        shownSteps () {
            let isBeforeCurrent = true
            return this.steps.map(step => {
                if (step.id === this.currentStepId) {
                    isBeforeCurrent = false
                }
                const label = isBeforeCurrent ? step.activeLabel : step.passiveLabel

                return {
                    id: step.id,
                    label: label,
                    isActive: step.id === this.currentStepId || this.currentStepId === null || this.currentStepId === undefined,
                }
            }).filter(s => s.label)
        },
        stepIndex () {
            if (!this.currentStepId) {
                return 0
            }
            return this.shownSteps.map(s => s.id).indexOf(this.currentStepId)
        },
        calculationSteps () {
            return this.shownSteps.slice(0, this.stepIndex + 1)
        },
        showHeader () {
            return this.headerOffset !== null
        },
        headerStyle () {
            return `left: ${Math.floor(this.headerOffset)}px`
        },
    },
    watch: {
        calculationSteps () {
            this.$nextTick(() => this.transitionHeader())
        },
    },
    beforeDestroy () {
        window.removeEventListener('resize', () => this.transitionHeader())
    },
    mounted () {
        this.$nextTick(() => {
            this.transitionHeader()
            window.addEventListener('resize', () => this.transitionHeader())
            setTimeout(() => this.transitionHeader(), 100) // safari initially returns wrong width for first step
        })
    },
    methods: {
        previousStep () {
            this.$emit('previous-step')
        },
        closeDialog () {
            this.$emit('close-dialog')
        },
        transitionHeader () {
            let headerStart = 0

            const stepElements = this.$refs.calculationSteps
            if (stepElements) {
                const stepSpacing = 30
                for (const stepElement of stepElements) {
                    if (stepElement.className.indexOf('dialog-header__element--active') == -1) {
                        headerStart += stepElement.clientWidth + stepSpacing
                    } else {
                        headerStart += stepElement.clientWidth / 2
                        break
                    }
                }
            }

            this.headerOffset = (document.body.clientWidth / 2) - headerStart
        },
        focus () {
            if (this.stepIndex > 0 && this.hasBackButton) {
                this.$refs.backButton.focus()
            } else if (this.hasCloseButton) {
                this.$refs.closeButton.focus()
            }
        },
    },
}
</script>

<style scoped lang="less">
@import (reference) './less/common';
@import './less/typography';

@step-animation-time: 0.2s;
@open-close-animation-time-header: 0.21s;
@closing-animation-time-header: @default-transition-time;
@open-animation-time-header-delay: 0.1s;
@close-animation-time-header-delay: 0.06s;

.dialog-header {
    position: absolute;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    opacity: 0;
    .regular-font();

    &--opening,
    &--open {
        animation: open-header-animation @open-close-animation-time-header ease-out @open-animation-time-header-delay;
        animation-fill-mode: forwards;
    }

    @keyframes open-header-animation {
        from {
            transform: translate3d(0, -60px, 0);
            opacity: 0;
        }

        to {
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }
    }

    &--close-animation {
        animation: close-animation-header-animation @open-close-animation-time-header ease-in @close-animation-time-header-delay;
        animation-fill-mode: backwards;
    }

    @keyframes close-animation-header-animation {
        from {
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }

        to {
            transform: translate3d(0, -60px, 0);
            opacity: 0;
        }
    }

    &--closing {
        animation: closing-header-animation @closing-animation-time-header ease-in;
        animation-fill-mode: backwards;
    }

    @keyframes closing-header-animation {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    &__content {
        position: absolute;
        .regular-font();
        transition: all @step-animation-time ease-out;
        top: 55px;

        .breakpoint-height-lte-870({ top: 35px; })
    }

    &__content--single-step {
        width: 100%;
        text-align: center;
    }

    &__element {
        float: left;
        margin-right: 30px;
        letter-spacing: 0.5px;
        font-size: 11px;
        line-height: 11px;
        .regular-font();
        user-select: none;
        cursor: default;
        transition: all @step-animation-time ease-out;
    }

    &__element--calculation {
        transition: none;
        visibility: hidden;
    }

    &__element--active {
        font-size: 18px;
        line-height: 8px;
    }

    &__element--single-step {
        width: 100%;
    }

    &__back {
        position: absolute;
        left: 50px;
        top: 45px;
        width: 30px;
        height: 30px;
        cursor: pointer;

        .breakpoint-height-lte-870({ top: 25px; });

        &:hover,
        &:focus { filter: brightness(150%); }
        &:focus { outline: none; }
    }

    &__close {
        position: absolute;
        right: 30px;
        top: 30px;
        width: 60px;
        height: 60px;
        padding: 18px;
        box-sizing: border-box;
        cursor: pointer;

        .breakpoint-height-lte-870({ top: 10px; });

        &:hover:not(&--light),
        &:focus:not(&--light) { filter: brightness(150%); }

        &:focus { outline: none; }
    }

    .dialog-header__back-svg.dialog-header__back-svg {
        width: 30px;
        height: 22px;
    }

    .dialog-header__close-svg.dialog-header__close-svg {
        width: 24px;
        height: 24px;
    }
}

.dialog-header--dark {
    .dialog-header__element {
        color: @ash;

        &--active {
            color: @white;
        }

        &--green {
            color: @green;
        }
    }

    .dialog-header__back-svg {
        color: @cement;
    }

    .dialog-header__close-svg {
        color: @cement;
    }
}

.dialog-header--light {
    .dialog-header__element {
        color: @smog;

        &--active {
            color: @smog;
        }

        &--green {
            color: @green;
        }
    }

    .dialog-header__back-svg {
        color: @charcoal;
    }

    .dialog-header__close-svg {
        color: @charcoal;
    }
}
</style>
