<template>
    <div class="text-line" @mousemove="onHover" @mouseleave="hideTooltip">
        <div ref="measurement" class="text-line__measurement">{{ text }}</div>
        <div class="text-line__text">
            <template v-if="highlightText">
                <span v-for="(part, index) in getParts(text)" :key="index" :style="part.bold ? { fontWeight: 'bold' } : {}">{{ part.text }}</span>
            </template>
            <template v-else>
                {{ truncatedText }}
            </template>
        </div>
    </div>
</template>

<script>
import { getTextHighlightParts } from './helpers/utils'
import TooltipMixin from './helpers/tooltip_mixin'

function endEllipsis (value, length) {
    const ellipsisLength = 3
    return value.substring(0, length - ellipsisLength) + '…'
}

function middleEllipsis (value, breakAt, middle = breakAt / 2) {
    if (value.length > breakAt) {
        const ellipsisLength = 4
        return (
            value.substring(0, breakAt - middle - ellipsisLength) +
            '…' +
            value.substring(value.length - middle, value.length)
        )
    }
    return value
}

export default {
    mixins: [TooltipMixin],
    props: {
        theme: { type: String, default: 'dark' },
        text: { type: String, required: true },
        tooltipText: { type: String, required: false },
        tooltipTitle: { type: String, required: false },
        truncate: { type: Boolean, default: true },
        truncateMiddle: { type: Boolean, default: false },
        highlightText: { type: String, required: false },
    },
    data () {
        return {
            width: 0,
            actualWidth: 0,
        }
    },
    computed: {
        maximumLength () {
            if (this.actualWidth > 0 && this.width < this.actualWidth) {
                const ratio = this.width / this.actualWidth
                return Math.floor(this.text.length * ratio)
            }
            return this.text.length
        },
        truncatedText () {
            if (this.maximumLength < this.text.length) {
                if (this.truncateMiddle) {
                    return middleEllipsis(this.text, this.maximumLength)
                } else if (this.truncate) {
                    return endEllipsis(this.text, this.maximumLength)
                }
            }
            return this.text
        },
        isTooltipAvailable () {
            return this.tooltipText || this.tooltipTitle || this.maximumLength < this.text.length
        },
    },
    mounted () {
        this.width = parseInt(window.getComputedStyle(this.$el).width, 10)
        this.actualWidth = parseInt(window.getComputedStyle(this.$refs.measurement).width, 10)
    },
    methods: {
        onHover () {
            if (this.isTooltipAvailable) {
                this.showTooltip(this.$el, this.tooltipText, this.tooltipTitle || this.text)
            }
        },
        getParts (text) {
            return getTextHighlightParts(text, this.highlightText)
        },
    },
}
</script>

<style lang="less" scoped>
@import './less/typography';

.text-line {
    .regular-font();
    width: 100%;
    position: relative;

    &__measurement {
        position: absolute;
        visibility: hidden;
        height: auto;
        width: auto;
        white-space: nowrap;
    }

    &__text {
        white-space: nowrap;
        overflow: hidden;
        cursor: default;
    }
}
</style>
