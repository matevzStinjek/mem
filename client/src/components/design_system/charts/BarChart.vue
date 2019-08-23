<template>
    <div class="bar-chart">
        <svg :viewBox="`0 0 ${width} ${height}`" fill="none" xmlns="http://www.w3.org/2000/svg" class="bar-chart__svg">
            <chart-grid :width="width" :height="height" :vertical-axes="verticalAxes" :horizontal-axes="horizontalAxes"
                        :grid-padding="gridPadding" :ax-padding-x="axPaddingX" :ax-padding-y="axPaddingY" :label-padding-x="labelPaddingX" :label-padding-y="labelPaddingY">
            </chart-grid>
            <template v-for="(bar, idx) in bars">
                <rect :key="`${idx}bar`" v-bind="bar.bar" class="bar-chart__bar" :style="{ fill: getChartColor(bars.length - idx - 1, bars.length) }"></rect>
                <foreignObject :key="'tooltip-container' + idx" class="bar-chart__tooltip-container" :x="bar.bar.x + bar.bar.width" :y="bar.bar.y" :height="bar.bar.height">
                    <div class="bar-chart__label-wrapper">
                        <slot name="label" :bar="bar.bar">
                            {{ bar.bar.value }}
                        </slot>
                    </div>
                </foreignObject>

                <rect v-if="bar.benchmark" :key="`${idx}benchmark`" v-bind="bar.benchmark" class="bar-chart__benchmark"></rect>
                <foreignObject v-if="bar.benchmark" :key="'tooltip-container-benchmark' + idx" class="bar-chart__tooltip-container" :x="bar.benchmark.x + bar.benchmark.width" :y="bar.benchmark.y" :height="bar.benchmark.height">
                    <div class="bar-chart__label-wrapper">
                        <slot name="label" :bar="bar.benchmark">
                            {{ bar.benchmark.value }}
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
import colors from '!less-vars-loader!../less/colors.less'

export default {
    components: {
        ChartGrid,
    },
    mixins: [ChartColorsMixin, ChartGridMixin],
    props: {
        series: { type: Array, required: true },
        width: { type: Number, default: 200 },
        height: { type: Number, default: 200 },
        gridPadding: { type: Number, default: 20 },
        axPaddingY: { type: Number, default: 10 },
        axPaddingX: { type: Number, default: 10 },
        labelPaddingX: { type: Number, default: 0 },
        labelPaddingY: { type: Number, default: 0 },
        verticalAxesOverride: { type: Array, required: false },
        barHeight: { type: Number, default: 24 },
    },
    computed: {
        mappedSeries () {
            return this.series.reduce((agg, current) => [current, ...agg], [])  // Reverse without modifying this.series
        },
        verticalAxes () {
            const seriesMax = this.mappedSeries.reduce((max, serie) => Math.max(max, serie.value), -Infinity)
            return this.verticalAxesOverride || [{ id: 0, value: 0, label: "0" }, { id: 1, value: seriesMax, label: seriesMax.toString() }]
        },
        horizontalAxes () {
            const step = this.height / this.series.length
            return this.mappedSeries.map((serie, idx) => ({ ...serie, value: step * idx, labelColor: colors.ash }))
        },
        bars () {
            const step = this.height / this.series.length

            return this.mappedSeries.map((serie, idx) => {
                const bar = {
                    value: serie.value,
                    x: this.mapX(0),
                    y: this.mapY(step * idx) - this.barHeight / 2,
                    width: this.mapX(serie.value) - this.mapX(0),
                    height: serie.benchmark ? this.barHeight * 0.4 : this.barHeight,
                }
                if (serie.benchmark) {
                    return {
                        bar,
                        benchmark: {
                            ...bar,
                            value: serie.benchmark,
                            y: bar.y + bar.height +  0.2 * this.barHeight,
                            width: this.mapX(serie.benchmark) - this.mapX(0),
                            height: this.barHeight * 0.4,
                        },
                    }
                }
                return { bar }
            })
        },
    },
}
</script>

<style scoped lang="less">
@import (reference) '../less/variables';

.bar-chart {
    &__benchmark {
        fill: @cement;
    }

    &__tooltip-container {
        overflow: visible;
    }

    &__label-wrapper {
        padding-left: 10px;
        height: 100%;
        .regular-font();
        font-size: 14px;
        color: @ash;
        white-space: nowrap;
        display: flex;
        align-items: center;
    }
}
</style>
