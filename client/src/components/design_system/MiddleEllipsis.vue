<template>
    <div ref="middle-ellipsis" class="middle-ellipsis">
        <div ref="measurement-width" class="middle-ellipsis__measurement">{{ text }}</div>
        <div :title="titleText" class="middle-ellipsis__text">{{ truncatedText }}</div>
    </div>
</template>

<script>
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
    props: {
        text: {
            required: true,
            type: String,
        },
        showNativeTooltip: {
            required: false,
            type: Boolean,
            default: true,
        },
    },
    data () {
        return {
            actualWidth: 0,
        }
    },
    computed: {
        truncatedText () {
            const length = this.text.length
            if (this.actualWidth > 0 && length > 4) {
                const percent = this.width / this.actualWidth
                const breakAt = Math.floor(length * percent)

                if (percent < 1) {
                    return middleEllipsis(this.text, breakAt)
                }
            }

            return this.text
        },
        titleText () {
            return this.showNativeTooltip ? this.text : null
        },
    },
    mounted () {
        this.width = parseInt(window.getComputedStyle(this.$refs['middle-ellipsis']).width, 10)
        this.actualWidth = parseInt(window.getComputedStyle(this.$refs['measurement-width']).width, 10)
    },
}
</script>

<style lang="less" scoped>
@import './less/typography';

.middle-ellipsis {
    .regular-font();
    width: 100%;

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
    }
}
</style>
