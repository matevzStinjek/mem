<template>
    <g>
        <svg :viewBox="`0 0 ${width} ${height}`" fill="none" xmlns="http://www.w3.org/2000/svg" >
            <g v-for="ax in mappedVerticalAxes" :key="ax.key">
                <line v-bind="ax.line" class="grid__line" v-if="displayVerticalAxes"></line>
                <text v-if="ax.label" v-bind="ax.labelProps" class="grid__label" text-anchor="middle">{{ ax.label }}</text>
            </g>
            <g v-for="ax in mappedHorizontalAxes" :key="`${ax.key}-horizontal`">
                <line v-bind="ax.line" class="grid__line"></line>
                <text v-if="ax.label" v-bind="ax.labelProps" class="grid__label grid__label--horizontal" :style="{fill: ax.labelColor}">{{ ax.label }}</text>
            </g>
        </svg>
    </g>
</template>

<script>
import { ChartGridMixin } from './mixins.js'
export default {
    mixins: [ChartGridMixin],
    props: {
        width: { type: Number, required: true },
        height: { type: Number, required: true },
        gridPadding: { type: Number, required: true },
        axPaddingX: { type: Number, required: true },
        axPaddingY: { type: Number, required: true },
        labelPaddingX: { type: Number, required: true },
        labelPaddingY: { type: Number, required: true },
        displayVerticalAxes: { type: Boolean, default: true },
        verticalAxes: { type: Array, required: true },
        horizontalAxes: { type: Array, required: true },
    },
    computed: {
        mappedVerticalAxes () {
            return this.verticalAxes.map((ax, idx) => {
                const line = { x1: this.mapX(ax.value), y1: 0, x2: this.mapX(ax.value), y2: this.height - this.labelPaddingY }
                const labelProps = { x: line.x1, y: this.height - 2 }
                return { ...ax, line, labelProps, key:idx }
            }).filter(ax => !ax.hidden)
        },
        mappedHorizontalAxes () {
            return this.horizontalAxes.map((ax, idx) => {
                const line = { x1: this.labelPaddingX, y1: this.mapY(ax.value), x2: this.width, y2: this.mapY(ax.value) }
                const labelProps = { y: line.y1 + 4, x: line.x1 - 5 }
                return { ...ax, line, labelProps, key: idx }
            }).filter(ax => !ax.hidden)
        },
    },
}
</script>

<style scoped lang="less">
@import (reference) '../less/variables';

.grid {
    &__line {
        stroke: @void;
        opacity: 0.1;
    }

    &__label {
        .regular-font();
        font-size: 11px;
        fill: @dust;

        &--horizontal {
            text-anchor: end;
        }
    }
}
</style>
