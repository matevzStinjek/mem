<template>
    <div class="line-chart" :style="{width: `${width}px`, height: `${height}px`}">
        <svg :viewBox="`0 0 ${width} ${height}`" fill="none" xmlns="http://www.w3.org/2000/svg" class="line-chart__svg">
            <defs>
                <filter id="circle-shadow" height="140%">
                    <feDropShadow dx="0" dy="1.6" stdDeviation="1.3" flood-opacity="0.25"/>
                </filter>
            </defs>

            <chart-grid :width="width" :height="height" :vertical-axes="verticalAxes" :horizontal-axes="horizontalAxes" :display-vertical-axes="displayVerticalAxes"
                        :grid-padding="gridPadding" :ax-padding-x="axPaddingX" :ax-padding-y="axPaddingY" :label-padding-x="labelPaddingX" :label-padding-y="labelPaddingY">
            </chart-grid>
            <template v-if="withArea">
                <path v-for="(polygon, idx) in polygons" :key="`${idx}-polygon`" v-bind="polygon" :class="polygonClass" :style="{ fill: getChartColor(idx, lines.length), opacity: cumulative ? 1 : 0.15 }"></path>
            </template>
            <template v-if="!cumulative">
                <path v-for="(line, idx) in lines" :key="idx" v-bind="line" class="line-chart__line" :style="{ stroke: getChartColor(idx, lines.length) }"></path>
            </template>
            <template v-if="benchmark">
                <line v-bind="benchmarkProps" class="line-chart__benchmark" />
                <text :x="benchmarkProps.x2" :y="benchmarkProps.y1" class="line-chart__benchmark-label">{{ benchmarkLabel || benchmark }}</text>
            </template>
            <g v-for="(rect,idx) in hoverHitAreas" :key="'g' + idx">
                <rect class="line-chart__hover-area" v-bind="rect"></rect>
                <circle r="7.5" fill="#fff" :cx="rect.point.x" :cy="rect.point.y" class="line-chart__point-circle"/>
                <circle :cx="rect.point.x" :cy="rect.point.y" r="2.5" class="line-chart__point-fill" :style="{ fill: getChartColor(rect.point.lineIndex, lines.length) }" />
                <foreignObject class="line-chart__tooltip-container" height="1" width="1" :x="rect.point.x - 3" :y="rect.point.y - 15">
                    <tooltip>
                        <span class="line-chart__tooltip-label">{{ rect.point.series.label }}</span>
                        <span class="line-chart__tooltip-value">{{ rect.point.valueLabel }}</span>
                    </tooltip>
                </foreignObject>
            </g>
        </svg>
    </div>
</template>

<script>
import ChartGrid from './ChartGrid.vue'
import { ChartColorsMixin, ChartGridMixin } from './mixins.js'
import Tooltip from './ChartTooltip.vue'

export default {
    components: {
        ChartGrid,
        Tooltip,
    },
    mixins: [ChartGridMixin, ChartColorsMixin],
    props: {
        series: { type: Array, required: true },
        gridPadding: { type:Number, default: 20 },
        axPaddingY: { type:Number, default: 10 },
        axPaddingX: { type: Number, default: 10 },
        labelPaddingX: { type: Number, default: 30 },
        labelPaddingY: { type: Number, default: 0 },
        withArea: { type: Boolean, default: false },
        cumulative: { type: Boolean, default: false },
        mapXToAxes: { type: Boolean, default: false },
        displayVerticalAxes: { type: Boolean, default: true },
        verticalAxesOverride: { type: Array, required: false },
        horizontalAxesOverride: { type: Array, required: false },
        benchmark: { type: Number, required: false },
        benchmarkLabel: { type: String, default: null },
        width: { type: Number, default: 200 },
        height: { type: Number, default: 200 },
    },
    computed: {
        mappedSeries () {
            if (this.cumulative) {
                const sumSerieValues = (s1, s2) => s1.map((item, idx) => item + s2[idx])

                return this.series.reduce((cumulative, serie) => {
                    const seriesSum = cumulative.length > 0 ? cumulative[0].values : Array(this.maxValuesLength).fill(0)
                    return [{ ...serie, values: sumSerieValues(seriesSum, serie.values), valueLabels: serie.values }, ...cumulative]
                }, [])
            }
            return this.series
        },
        maxValuesLength () {
            return Math.max(...this.series.map(serie => serie.values.length))
        },
        verticalAxes () {
            return (this.verticalAxesOverride || this.series).map((serie, idx) => ({ ...serie, value: serie.value || idx }))
        },
        horizontalAxes () {
            const seriesMax = this.mappedSeries.reduce((max, serie) => Math.max(max, ...serie.values), -Infinity)
            return this.horizontalAxesOverride || [{ id: 0, value: 0, label: "0" }, { id: 1, value: seriesMax, label: seriesMax.toString() }]
        },
        linesPoints () {
            return this.mappedSeries.map(serie => {
                return serie.values.map((value, idx) => {
                    const relativeX = this.mapXToAxes ? this.verticalAxes[idx].value : this.minX + idx / (this.maxValuesLength - 1) * (this.maxX - this.minX)
                    const valueLabel = serie.valueLabels ? serie.valueLabels[idx]: value
                    const formattedValueLabel = valueLabel < 1 ? `${(100 * valueLabel).toFixed(1)}%` : valueLabel.humanize()

                    return { x: this.mapX(relativeX), y: this.mapY(value), valueLabel: formattedValueLabel }
                })
            })
        },
        lines () {
            return this.linesPoints.map(points => ({ d:`M${points.map(point => `${point.x} ${point.y}`).join('L')}` }))
        },
        polygons () {
            return this.linesPoints.map(points => {
                const polygonPoints = [
                    `${this.mapX(0)} ${this.mapY(0)}`, // polygon bottom left
                    ...points.map(point => `${point.x} ${point.y}`),
                    `${Math.max(...points.map(point => point.x))} ${this.mapY(0)}`, // polygon bottom right
                ]
                return { d:`M${polygonPoints.join('L')}` }
            })
        },
        hoverHitAreas () {
            const maxLengthLine = this.linesPoints.reduce((points, current) => current.length > points.length ? current: points, [])
            const step = this.mapXToAxes ? this.mapX(this.verticalAxes[1].value) - this.mapX(this.verticalAxes[0].value) : (this.mapX(this.maxX) - this.mapX(this.minX)) / (this.maxValuesLength - 1)

            return maxLengthLine.map((point, idx) => {
                const points = this.linesPoints
                    .map((linesPoints, lineIndex) => linesPoints[idx] ? { ...linesPoints[idx], lineIndex, series: this.mappedSeries[lineIndex] } : null)
                    .filter(point => !!point)
                const yValues = points.map(point => point.y).sort((a, b) => b - a)

                return points.map(point => {
                    const isLowestPoint = point.y === yValues[yValues.length - 1]
                    const nextY = yValues.indexOf(point.y) > 0 ? yValues[yValues.indexOf(point.y) - 1] : this.mapY(this.minY)

                    if (isLowestPoint) {
                        return { x: point.x, y: 0, height:  nextY, width: step, point: point }
                    } else {
                        return { x: point.x, y: point.y, height:  nextY - point.y, width: step, point: point }
                    }
                }, [])
            }).map((areas, idx) => {
                return idx === 0 ? areas : areas.map(rect => ({ ...rect, x: rect.x - step / 2 }))
            }).reduce((agg, current) => [...agg, ...current], [])
        },
        benchmarkProps () {
            const leftPadding = this.gridPadding / 2 + this.axPaddingX / 2 + this.labelPaddingX
            const relativeValue = this.maxX / (this.maxX - this.minX)

            return {
                x1: this.mapX(this.minX),
                x2: (this.width - leftPadding) * relativeValue + leftPadding,
                y1: this.mapY(this.benchmark),
                y2: this.mapY(this.benchmark),
            }
        },
        polygonClass () {
            return this.cumulative ? 'line-chart__cumulative-polygon' : 'line-chart__polygon'
        },
    },
}
</script>

<style scoped lang="less">
@import (reference) '../less/variables';

.line-chart {

    &__svg {
        overflow: visible;
    }

    &__line {
        stroke-width: 3;
        stroke-linecap: round;
    }

    &__polygon {
        stroke: none;
    }

    &__hover-area {
        pointer-events: all;
        &:hover ~ * {
            visibility: visible;
        }
    }

    &__tooltip-container {
        visibility: hidden;
        overflow: visible;
        pointer-events: none;
        position: relative;
    }

    &__point-circle {
        pointer-events: none;
        visibility: hidden;
        filter: url(#circle-shadow);
    }

    &__point-fill {
        pointer-events: none;
        visibility: hidden;
    }

    &__tooltip-label {
        color: @charcoal;
        line-height: 16px;
        font-size: 12px;
        .regular-font();
    }

    &__tooltip-value {
        color: @charcoal;
        font-size: 16px;
        .bold-font();
    }

    &__benchmark{
        stroke: @smog;
        stroke-dasharray: 4;
        stroke-width: 1;
    }

    &__benchmark-label {
        .regular-font();
        stroke: @smog;
        font-size: 11px;
        text-anchor: end;
        dominant-baseline: ideographic;
    }
}
</style>
