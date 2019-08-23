<template>
    <div class="stack-chart" :style="{width: `${width}px`, height: `${height}px`}">
        <svg :viewBox="`0 0 ${width} ${height}`" fill="none" xmlns="http://www.w3.org/2000/svg" class="stack-chart__svg">
            <chart-grid :width="width" :height="height" :vertical-axes="[]" :horizontal-axes="horizontalAxes"
                        :grid-padding="gridPadding" :ax-padding-x="axPaddingX" :ax-padding-y="axPaddingY" :label-padding-x="labelPaddingX" :label-padding-y="labelPaddingY">
            </chart-grid>

            <template v-for="(stack, idx) in stacks">
                <rect :key="idx" v-bind="stack" class="stack-chart__stack" :style="{ fill: getChartColor(idx, stacks.length) }"></rect>
                <line :key="'line' + idx" class="stack-chart__label-line"
                      :x1="rightOriented ? stack.x + stack.width : stack.x - 10"
                      :x2="rightOriented ? stack.x + stack.width + 10 : stack.x"
                      :y1="stack.y + stack.height / 2"
                      :y2="stack.y + stack.height / 2"></line>
                <foreignObject :x="rightOriented ? stack.x + stack.width : stack.x" :y="stack.y + stack.height / 2" :key="'label' + idx" class="stack-chart__label-container">
                    <div class="stack-chart__label-wrapper" :class="{ right: rightOriented } | prefix('stack-chart__label-wrapper--')">
                        <slot :stack="stack">
                            {{ stack.label }}
                        </slot>
                    </div>
                </foreignObject>
            </template>
        </svg>
    </div>
</template>

<script>
import ChartGrid from './ChartGrid.vue'
import { ChartColorsMixin, ChartGridMixin } from './mixins.js'

export default {
    components: { ChartGrid },
    mixins: [ChartGridMixin, ChartColorsMixin],
    props: {
        width: { type: Number, required: true },
        height: { type: Number, required: true },
        gridPadding: { type: Number, default: 0 },
        axPaddingY: { type: Number, default: 10 },
        axPaddingX: { type: Number, default: 0 },
        labelPaddingX: { type: Number, default: 40 },
        labelPaddingY: { type: Number, default: 0 },
        series: { type: Array, required: true },
        stackWidth: { type: Number, default: 100 },
        rightOriented: { type: Boolean, default: false },
    },
    computed: {
        seriesSum () {
            return this.series.reduce((sum, serie) => sum + serie.value, 0)
        },
        horizontalAxes () {
            return [{ value: 0, label: "0" }, { value: this.seriesSum, label: "100%" }]
        },
        stacks () {
            return this.series.reduce((stacks, serie) => {
                const currentSum = stacks.reduce((sum, stack) => sum + stack.value, 0)

                const stack = {
                    ...serie,
                    x: this.width - this.stackWidth,
                    width: this.stackWidth,
                    y: this.mapY(this.maxY - currentSum),
                    height: this.mapY(this.minY) - this.mapY(serie.value),
                }
                return [...stacks, stack]
            }, [])
        },
    },
}
</script>

<style scoped lang="less">
@import (reference) '../less/variables';

.stack-chart {
    position: relative;

    &__svg {
        overflow: visible;
    }

    &__label-container {
        position: relative;
        overflow: visible;
    }

    &__label-wrapper {
        position: absolute;
        right: 0;
        transform: translateY(-50%);
        padding: 0 20px;
        .regular-font();
        font-size: 14px;
        color: @ash;

        &--right {
            right: auto;
            left: 0;
        }
    }

    &__label-line {
        stroke: @cement;
    }
}
</style>
