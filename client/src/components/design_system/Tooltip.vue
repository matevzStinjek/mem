<template>
    <div ref="tooltip" :style="transform ? { transform: transform } : {}" :class="[theme, { visible: show, hoverable: isRelative }] | prefix('hover-tooltip--')" class="hover-tooltip" @animationstart="onAnimationStart">
        <p v-if="title" :class="{ 'hover-tooltip__title--with-slot': $slots.default.length > 0 && $slots.default[0].text.length > 0 }" class="hover-tooltip__title">{{ title }}</p>
        <slot></slot>
    </div>
</template>

<script>
export default {
    props: {
        theme: { type: String, default: 'dark' },
        title: { type: String, required: false },
        show: { type: Boolean, default: false },
        isRelative: { type: Boolean, default: true },
    },
    data () {
        return {
            translateX: null,
            translateY: null,
        }
    },
    computed: {
        transform () {
            if (this.translateX || this.translateY) {
                return `translate(${Math.round(this.translateX || 0)}px, ${Math.round(this.translateY || 0)}px)`
            }
        },
    },
    methods: {
        onAnimationStart () {
            if (this.isRelative) {
                if (this.$refs.tooltip) {
                    const tooltipBox = this.$refs.tooltip.getBoundingClientRect()
                    const delta = window.innerWidth - (tooltipBox.x - this.translateX + tooltipBox.width + 10)
                    this.translateX = delta < 0 ? delta : null
                }
            }
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.hover-tooltip {
    position: absolute;
    visibility: hidden;
    opacity: 0;
    top: 100%;
    left: 0;
    color: @white;
    background-color: @charcoal;
    border-radius: 3px;
    font-size: 11px;
    text-align: left;
    padding: 10px 15px;
    z-index: @z-highest;
    max-width: 460px;
    word-wrap: break-word;
    .regular-font();
    transition: transform 200ms ease;

    &__title {
        font-weight: bold;
        margin: 0;

        &--with-slot {
            margin-bottom: 5px;
        }
    }

    &--dark {
        color: @charcoal;
        background-color: white;
    }
}

.hover-tooltip:hover,
:hover > .hover-tooltip--hoverable,
.hover-tooltip--visible {
    animation: 0.2s fadeIn;
    animation-delay: 0.8s;
    animation-fill-mode: forwards;
}

@keyframes fadeIn {
    0% {
        visibility: hidden;
        opacity: 0;
    }

    100% {
        visibility: visible;
        opacity: 1;
    }
}
</style>
