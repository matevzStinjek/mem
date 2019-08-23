<template>
    <div>
        <div :class="['new-dialog--' + theme, 'new-dialog--' + dialogViewState]" class="new-dialog">
            <div ref="overlay" :class="'new-dialog__overlay--' + dialogViewState" class="new-dialog__overlay"></div>
            <div :class="'new-dialog__content-wrapper--' + dialogViewState" class="new-dialog__content-wrapper">
                <div v-if="steps && steps.length > 0 || title" class="new-dialog__header-wrapper">
                    <dialog-header
                        ref="header"
                        :theme="theme"
                        :dialog-view-state="dialogViewState"
                        :steps="steps && steps.length > 0 ? steps : [{ passiveLabel: title, activeLabel: title }]"
                        :current-step-id="stepId"
                        :is-valid="isValid"
                        :has-back-button="hasBackButton"
                        :has-close-button="hasCloseButton"
                        @previous-step="$emit('previous-step')"
                        @close-dialog="closeDialog">
                    </dialog-header>
                </div>
                <div ref="content" :class="'new-dialog__content--' + dialogViewState" class="new-dialog__content" tabindex="-1">
                    <slot></slot>
                </div>

                <div class="new-dialog__last-focusable-element" tabindex="0" @focus="refocus"></div>
            </div>
        </div>
    </div>
</template>

<script>
import DialogHeader from './DialogHeader.vue'

export default {
    components: {
        dialogHeader: DialogHeader,
    },
    props: {
        theme: { type: String, default: 'dark' },
        stepId: { type: String, required: false },
        blurElement: { type: HTMLElement, required: false },
        steps: { type: Array, required: false },
        title: { type: String, required: false },
        isValid: { type: Boolean, default: false },
        hasBackButton: { type: Boolean, default: true },
        hasCloseButton: { type: Boolean, default: true },
        isClosingAllowed: { type: Boolean, default: true },
        closed: { type: Boolean, default: false },
    },
    data () {
        return {
            dialogViewState: 'opening',
        }
    },
    watch: {
        closed (v) {
            if (v && this.dialogViewState === 'open') {
                this.closeDialogWithoutEmit()
            }
        },
    },
    mounted () {
        this.$nextTick(() => {
            window.addEventListener('keyup', this.keyUpEventListener)

            if (this.blurElement) {
                this.blurElement.style['-webkit-transition'] = '-webkit-filter 250ms ease-out'
            }

            const onAnimationEnd = (e) => {
                this.$refs.overlay.removeEventListener('animationend', onAnimationEnd)

                if (this.dialogViewState === 'opening') {
                    if (this.blurElement) {
                        this.blurElement.style['-webkit-filter'] = 'blur(5px)'
                        this.blurElement.style.filter = 'blur(5px)'
                    }

                    this.dialogViewState = 'open'
                }
            }
            this.$refs.overlay.addEventListener('animationend', onAnimationEnd)

            this.$refs.content.focus()
        })
    },
    beforeDestroy () {
        window.removeEventListener('keyup', this.keyUpEventListener)
        this.removeBlur()
    },
    methods: {
        closeDialog () {
            if (this.isClosingAllowed) {
                this.$emit('close')
                this.closeDialogWithoutEmit()
            }
        },
        closeDialogWithoutEmit () {
            this.dialogViewState = 'closing'
            this.removeBlur()
        },
        removeBlur () {
            if (this.blurElement) {
                this.blurElement.style['-webkit-filter'] = ''
                this.blurElement.style.filter = ''
            }

            const onAnimationEnd = (e) => {
                this.$refs.overlay.removeEventListener('animationend', onAnimationEnd)

                if (this.blurElement) {
                    this.blurElement.style['-webkit-transition'] = 'inherit'
                }

                this.$emit('closed')
            }
            this.$refs.overlay.addEventListener('animationend', onAnimationEnd)
        },
        keyUpEventListener (event) {
            switch (event.keyCode) {
            // ESC
            case 27:
                event.preventDefault()
                event.stopPropagation()
                this.closeDialog()
                break
            // Backspace or Left Arrow
            case 8:
            case 37:
                event.preventDefault()
                event.stopPropagation()
                this.$emit('previous-step')
                break
            // Right Arrow
            case 39:
                event.preventDefault()
                event.stopPropagation()
                this.$emit('next-step')
                break
            }
        },
        refocus () {
            if (this.$refs.header) {
                this.$refs.header.focus()
            }
            this.$emit('refocus')
        },
    },
}
</script>

<style scoped lang="less">
@import (reference) './less/common';
@import './less/typography';

@open-close-animation-time-background: 0.27s;
@close-animation-time-background-delay: 0.27s;
@closing-animation-time-background: 0.3s;
@closing-animation-time-background-delay: 0.1s;
@opening-animation-time-content: 0.2s;
@closing-animation-time-content: 0.2s;

.new-dialog {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    box-sizing: border-box;
    z-index: @z-high;

    &--closed {
        display: none;
    }

    &__overlay {
        position: absolute;
        left: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        background-color: @void;
        will-change: transform;
        transform: scaleY(0); // Revert to scale3d after switching to webpack (https://celtra.atlassian.net/browse/MAB-10812)
        opacity: 0;

        &--opening,
        &--open {
            animation-duration: @open-close-animation-time-background;
            animation-timing-function: ease-out;
            animation-fill-mode: forwards;
            animation-name: open-overlay-animation;
        }

        &--close-animation {
            animation-duration: @open-close-animation-time-background;
            animation-timing-function: ease-in;
            animation-delay: @close-animation-time-background-delay;
            animation-fill-mode: backwards;
            animation-name: close-overlay-animation;
        }

        &--closing {
            animation-duration: @closing-animation-time-background;
            animation-timing-function: ease-in;
            animation-delay: @closing-animation-time-background-delay;
            animation-fill-mode: backwards;
            animation-name: close-overlay-animation;
        }

        @keyframes open-overlay-animation {
            to {
                opacity: 1;
                transform: scaleY(1); // Revert to scale3d after switching to webpack (https://celtra.atlassian.net/browse/MAB-10812)
            }
        }

        @keyframes close-overlay-animation {
            from {
                opacity: 1;
                transform: scaleY(1); // Revert to scale3d after switching to webpack (https://celtra.atlassian.net/browse/MAB-10812)
            }

            to {
                transform: scaleY(0); // Revert to scale3d after switching to webpack (https://celtra.atlassian.net/browse/MAB-10812)
                opacity: 0;
            }
        }
    }

    &__content-wrapper {
        position: absolute;
        display: flex;
        flex-direction: column;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 1;
        overflow-y: auto;
        overflow-x: hidden;
        .regular-font();
    }

    &__header-wrapper {
        height: 120px;
        flex: none;

        .breakpoint-height-lte-870({ height: 80px; })
    }

    &__content {
        padding: 30px 60px 0 60px;
        box-sizing: border-box;
        padding-bottom: 120px;
        flex: 1 0 auto;
        justify-content: center;
        display: inline-flex;
        flex-direction: column;

        &:focus {
            outline: none;
        }

        .breakpoint-height-lte-870({ padding-bottom: 80px; });

        &--opening {
            animation: opening-content-animation @opening-animation-time-content ease-in;
            animation-fill-mode: both;
        }

        &--closing {
            animation: closing-content-animation @closing-animation-time-content ease-in;
            animation-fill-mode: both;
        }

        @keyframes opening-content-animation {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes closing-content-animation {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    }

    &__last-focusable-element {
        user-select: none;
        opacity: 0;
    }
}

.new-dialog--light {
    .new-dialog__overlay {
        background-color: @white;
    }
}
</style>

<style lang="less">
@import (reference) './less/common';

@step-animation-time: 0.2s;

.step-next-leave-active { animation: step-next-leave-animation @step-animation-time ease-in; }
.step-next-enter-active { animation: step-next-enter-animation @step-animation-time ease-out; }
.step-previous-leave-active { animation: step-previous-leave-animation @step-animation-time ease-in; }
.step-previous-enter-active { animation: step-previous-enter-animation @step-animation-time ease-out; }

@keyframes step-next-leave-animation {
    0% {
        transform: translate3d(0, 0, 0);
        opacity: 1;
    }

    50% {
        transform: translate3d(-150px, 0, 0);
        opacity: 0.7;
    }

    100% {
        transform: translate3d(-300px, 0, 0);
        opacity: 0;
    }
}

@keyframes step-next-enter-animation {
    0% {
        transform: translate3d(1000px, 0, 0);
        opacity: 0;
    }

    5% {
        transform: translate3d(950px, 0, 0);
        opacity: 0.05;
    }

    10% {
        transform: translate3d(300px, 0, 0);
        opacity: 0.1;
    }

    100% {
        transform: translate3d(0, 0, 0);
        opacity: 1;
    }
}

@keyframes step-previous-leave-animation {
    from {
        transform: translate3d(0, 0, 0);
        opacity: 1;
    }

    to {
        transform: translate3d(300px, 0, 0);
        opacity: 0;
    }
}

@keyframes step-previous-enter-animation {
    from {
        transform: translate3d(-300px, 0, 0);
        opacity: 0;
    }

    to {
        transform: translate3d(0, 0, 0);
        opacity: 1;
    }
}
</style>

<style lang="less">
@import (reference) './less/common';

.new-dialog__step-wrapper {
    width: 100%;
    position: relative;
    flex: auto;
    display: inline-flex;
    flex-direction: column;
}

.new-dialog__button-container {
    position: relative;
    left: 0;
    width: 100%;
    height: 120px;
    flex: none;
    text-align: center;
    margin-bottom: -90px;
    margin-top: 30px;

    .breakpoint-height-lte-870({
        height: 80px;
        margin-bottom: -50px;
    })
}
</style>
