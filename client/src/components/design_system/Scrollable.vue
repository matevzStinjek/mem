<template>
    <div
        class="scrollable"
        @mouseover="onHover(true)"
        @mouseleave="onHover(false)"
    >
        <scrollable-gradient
            :theme="theme"
            :axis="axis"
            :hide="isScrollableGradientHidden"
            :start-size="gradientStartSize"
            :end-size="gradientEndSize"
            :max-scroll="getMaxScrollOffset()"
            :current-scroll="scrollOffset">
            <div class="scrollable__wrapper" ref="scrollable" :class="scrollableClasses | prefix('scrollable__wrapper--')">
                <slot></slot>
                <scrollbar
                    v-model="scrollOffset"
                    :theme="theme"
                    :container="$refs.scrollable"
                    :class="scrollbarClasses | prefix('scrollbar--')"
                    :axis="axis"
                    @visible="onScrollbarVisible"
                    @start-drag="onStartDrag"
                    @stop-drag="onStopDrag"
                    class="scrollbar"
                />
            </div>
        </scrollable-gradient>
    </div>
</template>

<script>
import Scrollbar from './Scrollbar.vue'
import ScrollableGradient from './ScrollableGradient.vue'

export default {
    components: {
        Scrollbar,
        ScrollableGradient,
    },
    props: {
        axis: {
            type: String,
            default: 'y',
            validator: function (value) {
                return ['x', 'y'].includes(value)
            },
        },
        theme: { type: String, default: 'dark' },
        hideScrollbar: { type: Boolean, default: false },
        gradientStartSize: { type: Number },
        gradientEndSize: { type: Number },
        isPadded: { type: Boolean, default: false },
    },
    data () {
        return {
            scrollOffset: 0,
            isHovering: false,
            isPressed: false,
            isScrollable: false,
        }
    },
    computed: {
        scrollableClasses () {
            return {
                padded: this.isPadded,
            }
        },
        scrollbarClasses () {
            return {
                visible: this.isScrollbarVisible,
            }
        },
        isScrollbarVisible () {
            return this.isScrollable && !this.hideScrollbar && (this.isHovering || this.isPressed)
        },
        isScrollableGradientHidden () {
            return !this.isScrollable
        },
        publicMethods () {
            return {
                scrollBy: this.scrollBy,
                scrollTo: this.scrollTo,
            }
        },
    },
    watch: {
        scrollOffset (value) {
            this.$emit('scroll', value)
            this.$emit('scroll-position', this.getCurrentScrollPosition())
        },
    },
    mounted () {
        this.domObserver = new MutationObserver(() => {
            this.$nextTick(() => {
                this.scrollOffset = this.getCurrentScrollOffset()
            })
        })
        this.$nextTick(() => {
            this.scrollOffset = 0.01 // trigger scrollbar re-calculation
            this.domObserver.observe(this.$refs.scrollable, { childList: true, subtree: true })
        })
    },
    beforeDestroy () {
        this.domObserver.disconnect()
    },
    methods: {
        getCurrentScrollOffset () {
            const scrollable = this.$refs.scrollable || {}

            const scrollOffset = this.axis === 'y'
                ? scrollable.scrollTop
                : scrollable.scrollLeft

            return scrollOffset || 0
        },
        getMaxScrollOffset () {
            const scrollable = this.$refs.scrollable || {}

            const maxScroll = this.axis === 'y'
                ? scrollable.scrollHeight - scrollable.clientHeight
                : scrollable.scrollWidth -  scrollable.clientWidth

            return maxScroll || 0
        },
        getCurrentScrollPosition () {
            let scrollPosition

            if (this.scrollOffset === 0) {
                scrollPosition = 'start'
            } else if (this.scrollOffset === this.getMaxScrollOffset()) {
                scrollPosition = 'end'
            } else {
                scrollPosition = 'middle'
            }

            return scrollPosition
        },
        onHover (isHovering) {
            this.isHovering = isHovering
        },
        onStartDrag () {
            if (this.isScrollbarVisible) {
                this.isPressed = true
            }
        },
        onStopDrag () {
            this.isPressed = false
        },
        onScrollbarVisible (isVisible) {
            this.isScrollable = isVisible
            this.$emit('is-scrollable', isVisible)
        },
        scrollBy (pixels) {
            const position = this.getCurrentScrollOffset() + pixels
            this.scrollTo(position)
        },
        scrollTo (position) {
            const isScrollToSupported = this.$refs.scrollable.scrollTo
            const offset = this.keepScrollInBoundaries(position)

            if (isScrollToSupported) {
                const direction = this.getScrollingDirection()

                this.$refs.scrollable.scrollTo({ [direction]: offset, behavior: 'smooth' })
            } else {
                this.scrollOffset = offset
            }
        },
        getScrollingDirection () {
            return this.axis === 'y' ? 'top' : 'left'
        },
        keepScrollInBoundaries (offset) {
            return Math.min(Math.max(offset, 0), this.getMaxScrollOffset())
        },
    },
}

</script>

<style lang="less" scoped>
@import (reference) '~design-system/less/common';

.scrollable {
    width: 100%;
    height: 100%;
    display: flex;
    position: relative;
}

.scrollable__wrapper {
    width: 100%;
    overflow: hidden;
    min-height: 0; // Firefox hack

    &--padded {
        padding-right: 15px;
    }
}

.scrollbar {
    z-index: @z-lowest;
    opacity: 0;
    transition: opacity @default-transition-time;

    &--visible {
        opacity: 1;
    }
}
</style>
