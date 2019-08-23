<template>
    <div class="scrollable-gradient" :class="[`theme-${theme}`, { 'scrollable-gradient--hidden': hide } ]">
        <div :class="startGradientClass | prefix('scrollable-gradient__')" :style="startGradientStyle"></div>
        <slot></slot>
        <div :class="endGradientClass | prefix('scrollable-gradient__')" :style="endGradientStyle"></div>
    </div>
</template>

<script>
export default {
    props: {
        axis: {
            type: String,
            default: 'y',
            validator: function (value) {
                return ['x', 'y'].includes(value)
            },
        },
        theme: { type: String, default: 'dark' },
        startSize: { type: Number, default: 35 },
        endSize: { type: Number, default: 35 },
        maxScroll: { type: Number, required: true },
        currentScroll: { type: Number, required: true },
        hide: { type: Boolean },
    },
    computed: {
        startGradientClass () {
            return this.axis === 'y' ? 'top' : 'left'
        },
        endGradientClass () {
            return this.axis === 'y' ? 'bottom' : 'right'
        },
        startGradientStyle () {
            return {
                height: this.axis === 'y' ? `${this.startGradientSize}px` : '100%',
                width: this.axis === 'y' ? '100%' : `${this.startGradientSize}px`,
            }
        },
        endGradientStyle () {
            return {
                height: this.axis === 'y' ? `${this.endGradientSize}px` : '100%',
                width: this.axis === 'y' ? '100%' : `${this.endGradientSize}px`,
            }
        },
        startGradientSize () {
            return Math.min(this.currentScroll, this.startSize)
        },
        endGradientSize () {
            return Math.min(this.maxScroll - this.currentScroll, this.endSize)
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) '~design-system/less/common';

.scrollable-gradient {
    display: inherit;
    width: inherit;
    height: inherit;
    position: relative;

    &__top,
    &__bottom,
    &__left,
    &__right {
        content: "";
        position: absolute;
        pointer-events: none;
        z-index: @z-default;
        opacity: 1;
        transition: opacity @default-transition-time;

        .scrollable-gradient--hidden > & {
            opacity: 0;
        }
    }

    // Vertical axis
    &__top {
        top: 0;

        .theme-dark > & {
            @transparent: fade(@void, 0%); // this hack is needed for Safari
            background: ~"linear-gradient(to top, @{transparent}, @{void})";
        }
        .theme-light > & {
            @transparent: fade(@fog, 0%); // this hack is needed for Safari
            background: ~"linear-gradient(to top, @{transparent}, @{fog})";
        }
    }

    &__bottom {
        bottom: 0;

        .theme-dark > & {
            @transparent: fade(@void, 0%); // this hack is needed for Safari
            background: ~"linear-gradient(to bottom, @{transparent}, @{void})";
        }
        .theme-light > & {
            @transparent: fade(@fog, 0%); // this hack is needed for Safari
            background: ~"linear-gradient(to bottom, @{transparent}, @{fog})";

        }
    }

    // Horizontal axis
    &__left {
        left: 0;

        .theme-dark > & {
            @transparent: fade(@void, 0%); // this hack is needed for Safari
            background: ~"linear-gradient(to left, @{transparent}, @{void})";
        }
        .theme-light > & {
            @transparent: fade(@fog, 0%); // this hack is needed for Safari
            background: ~"linear-gradient(to left, @{transparent}, @{fog})";
        }
    }

    &__right {
        right: 0;

        .theme-dark > & {
            @transparent: fade(@void, 0%); // this hack is needed for Safari
            background: ~"linear-gradient(to right, @{transparent}, @{void})";
        }
        .theme-light > & {
            @transparent: fade(@fog, 0%); // this hack is needed for Safari
            background: ~"linear-gradient(to right, @{transparent}, @{fog})";

        }
    }
}
</style>
