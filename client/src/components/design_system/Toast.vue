<template>
    <transition name="slide">
        <div v-show="isShown" :class="{'toast-element--hidden' : !isShown, 'toast-element--dark': theme === 'dark'}" class="toast-element">
            <div class="toast-element__label">
                <slot></slot>
            </div>
            <div class="toast-element__action-label" @click="$emit('action')">
                <slot name="action"></slot>
            </div>
        </div>
    </transition>
</template>

<script>

export default {
    props: {
        theme: { type: String, default: 'dark' },
        alwaysVisible: { type: Boolean, default: false },
    },
    data () {
        return {
            isShown: false,
        }
    },
    watch: {
        alwaysVisible (v) {
            this.isShown = v
        },
    },
    mounted () {
        this.$nextTick(() => {
            if (this.alwaysVisible) {
                this.isShown = true
            }
        })
    },
    beforeDestroy () {
        if (this.timeoutId) {
            clearTimeout(this.timeoutId)
        }
    },
    methods: {
        show (timeout = 3500) {
            this.isShown = true
            if (this.timeoutId) {
                clearTimeout(this.timeoutId)
            }
            this.timeoutId = setTimeout(this.hide, timeout)
        },
        hide () {
            this.isShown = false
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.toast-element {
    .regular-font();
    user-select: none;
    min-width: 200px;
    max-width: 900px;
    margin-right: 128px; // so it looks more left-aligned when on small screens
    height: 56px;
    background-color: @fog;
    border-radius: 2px;
    bottom: 70px;
    margin-left: 24px;
    filter: drop-shadow(3px 3px 10px fade(@slate, 30));
    z-index: @z-sky;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;

    &--hidden {
        transform: translateY(96px);
    }

    &__label {
        line-height: 20px;
        max-height: 40px;
        font-size: 14px;
        color: @ink;
        .regular-font();
        .line-clamp-limit(2);
        padding-left: 24px;
        padding-right: 24px;
    }

    &__action-label {
        cursor: pointer;
        .medium-font();
        padding-right: 24px;
        line-height: 56px;
        height: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-transform: uppercase;
        font-size: 12px;
        color: @ash;
        transition: color @transition-20 ease;

        &:hover { color: @charcoal; }
        &:active { color: @void; }
    }

    &--dark {
        background-color: @ink;
        filter: drop-shadow(3px 3px 10px fade(@slate, 30));

        .toast-element__label {
            color: @silver;
        }

        .toast-element__action-label {
            color: @dust;

            &:hover { color: @cement; }
            &:active { color: @fog; }
        }
    }
}

.slide-enter-active {
    animation: slide @transition-15 ease-out forwards;
}
.slide-leave-active {
    animation: slide @transition-35 ease-in reverse;
}
@keyframes slide {
    0% {
        transform: translatey(96px);
        opacity: 0;
    }
    100% {
        transform: translatey(0px);
        opacity: 1;
    }
}
</style>
