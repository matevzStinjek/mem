import colors from '!less-vars-loader!../less/colors.less'
import charColors from '!less-vars-loader!./colors.less'

export const ChartColorsMixin = {
    methods: {
        getChartColor (index, seriesLength) {
            if (seriesLength === 1) {
                return colors.purple
            } else if (seriesLength === 2) {
                return [colors.purple, colors.cyan][index]
            } else if (seriesLength === 3) {
                return [charColors['hot-magenta'], colors.purple, colors.cyan][index]
            } else if (seriesLength === 4) {
                return [colors.orange, charColors['hot-magenta'], colors.purple, colors.cyan][index]
            } else if (seriesLength === 5) {
                return [colors.orange, charColors['hot-magenta'], colors.purple, charColors['deep-blue'], colors.cyan][index]
            } else if (seriesLength === 6) {
                return [colors.orange, charColors['hot-magenta'], colors.purple, charColors['deep-blue'], charColors.azure, colors.cyan][index]
            } else if (seriesLength === 7) {
                return [colors.orange, charColors['hot-magenta'], charColors['electric-violet'], colors.purple, charColors['deep-blue'], charColors.azure, colors.cyan][index]
            }
        },
    },
}

export const ChartGridMixin = {
    computed: {
        minY () {
            return this.horizontalAxes.reduce((min, ax) => Math.min(min, ax.value), Infinity)
        },
        maxY () {
            return this.horizontalAxes.reduce((max, ax) => Math.max(max, ax.value), -Infinity)
        },
        minX () {
            return this.verticalAxes.reduce((min, ax) => Math.min(min, ax.value), Infinity)
        },
        maxX () {
            return this.verticalAxes.reduce((max, ax) => Math.max(max, ax.value), -Infinity)
        },
    },
    methods: {
        mapX (value) {
            const leftPadding = this.gridPadding / 2 + this.axPaddingX / 2 + this.labelPaddingX
            const rightPadding = this.gridPadding / 2 + this.axPaddingX / 2
            const relativeValue = value / (this.maxX - this.minX)
            return (this.width - leftPadding - rightPadding) * relativeValue + leftPadding
        },
        mapY (value) {
            const bottomPadding = this.gridPadding / 2 + this.axPaddingY / 2 + this.labelPaddingY
            const topPadding = this.gridPadding / 2 + this.axPaddingY / 2
            const relativeValue = value / (this.maxY - this.minY)
            return this.height - (this.height - bottomPadding - topPadding) * relativeValue - bottomPadding
        },
    },
}
