<template>
    <div class="column-chart">
        <svg :viewBox="`0 0 ${width} ${height}`" fill="none" xmlns="http://www.w3.org/2000/svg" class="column-chart__svg">
            <chart-grid :width="width" :height="height" :vertical-axes="verticalAxes" :horizontal-axes="horizontalAxes"
                        :grid-padding="gridPadding" :ax-padding-x="axPaddingX" :ax-padding-y="axPaddingY" :label-padding-x="labelPaddingX" :label-padding-y="labelPaddingY">
            </chart-grid>
            <rect v-for="(column, idx) in columns" :key="idx" v-bind="column" class="column-chart__column" :style="{ fill: getChartColor(idx, columns.length) }"></rect>
        </svg>
    </div>
</template>

<script>
import ChartGrid from './ChartGrid.vue'
import { ChartColorsMixin, ChartGridMixin } from './mixins.js'

export default {
    components: {
        ChartGrid,
    },
    mixins: [ChartColorsMixin, ChartGridMixin],
    props: {
        series: { type: Array, required: true },
        gridPadding: { type:Number, default: 20 },
        axPaddingY: { type:Number, default: 10 },
        axPaddingX: { type: Number, default: 10 },
        labelPaddingX: { type: Number, default: 0 },
        labelPaddingY: { type: Number, default: 0 },
        horizontalAxesOverride: { type: Array, required: false },
    },
    computed: {
        verticalAxes () {
            const step = this.height / this.series.length
            return this.series.map((serie, idx) => ({ ...serie, value: step * idx }))
        },
        horizontalAxes () {
            const seriesMax = this.series.reduce((max, serie) => Math.max(max, serie.value), -Infinity)
            return this.horizontalAxesOverride || [{ id: 0, value: 0, label: "0" }, { id: 1, value: seriesMax, label: seriesMax.toString() }]
        },
        columns () {
            const step = this.width / this.series.length

            return this.series.map((serie, idx) => {
                return {
                    x: this.mapX(step * idx) - this.columnWidth/2,
                    y: this.mapY(serie.value),
                    width: this.columnWidth,
                    height: this.mapY(0) - this.mapY(serie.value),
                }
            })
        },
    },
    beforeCreate () {
        this.width = 200
        this.height = 200
        this.columnWidth = 45
    },
}
</script>

