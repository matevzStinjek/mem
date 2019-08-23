<template>
    <div :title="tooltipText" class="pie-chart">
        <svg class="pie-chart__svg" viewBox="-1 -1 2 2">
            <circle :class="{'pie-chart__background--grey': isValueZero}" cx="0" cy="0" r="1" class="pie-chart__background"/>
            <path :d="slicePath" class="pie-chart__share"></path>
        </svg>
    </div>
</template>

<script>

export default {
    props: {
        ratio: { type: Number },
        tooltip: { type: String, default: null },
    },
    computed: {
        value () {
            return Math.max(0, Math.min(1, this.ratio))
        },
        slicePath () {
            return `M 1 0
                    A 1 1 0 ${this.largeArcFlag} 1 ${this.x} ${this.y}
                    L 0,0`
        },
        x () {
            return Math.cos(2 * Math.PI * this.value)
        },
        y () {
            return Math.sin(2 * Math.PI * this.value)
        },
        largeArcFlag () {
            return this.value > 0.5 ? 1 : 0
        },
        isValueZero () {
            return this.value === 0
        },
        tooltipText () {
            // Remove trailing zeros
            return this.tooltip ? this.tooltip : `${(this.value * 100).toFixed(1).replace(/\.?0*$/,'')} %`
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.pie-chart {
    display: flex;
    align-items: center;

    &__svg {
        height: 18px;
        width: 18px;
        transform: rotate(-0.25turn);
    }

    &__background {
        fill: @green-disabled;

        &--grey {
            fill: @cement;
        }
    }

    &__share {
        fill: @green;
    }
}
</style>
