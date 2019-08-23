<template>
    <div :class="[theme] | prefix('scrollable-list--')" class="scrollable-list" @keydown.up.prevent @keydown.down.prevent v-on="$listeners">
        <div class="scrollable-list__sticky">
            <div :style="{ marginLeft: `${initialOffset}px` }" class="scrollable-list__sticky-slot">
                <slot name="sticky"></slot>
            </div>
            <div v-if="enableScrollTop && items.length > 0 && scrollTop > 0" class="scrollable-list__scroll-top" tabindex="0" @click="scrollToTop" @keyup.enter.stop="scrollToTop" @keyup.space.prevent.stop="scrollToTop">SCROLL UP</div>
        </div>

        <div class="scrollable-list__list-wrap">
            <div ref="scrollable" :style="{ maxHeight: `${maxHeight}px` }" :class="{ 'with-overlay': showOverlay, 'with-bottom-slot': !!$slots['sticky-bottom'], native: useNativeScroll } | prefix('scrollable-list__list--')" class="scrollable-list__list" @scroll="throttledOnScroll" @keydown.space.prevent>
                <default-list
                    v-show="items.length > 0"
                    ref="list"
                    :items="items"
                    :value="value"
                    :highlight-query="highlightQuery"
                    :transition-sorting="transitionSorting"
                    :no-group-rendering="noGroupRendering"
                    :set-active-on-hover="setActiveOnHover"
                    :initial-offset="initialOffset"
                    :always-show-active="alwaysShowActive"
                    :items-have-avatars="itemsHaveAvatars"
                    :size="size"
                    :theme="theme"
                    :class="{ 'with-overlay': showOverlay, 'with-bottom-slot': !!$slots['sticky-bottom'] } | prefix('scrollable-list__default-list--')"
                    class="scrollable-list__default-list"
                    @activate="onActivate"
                    v-on="$listeners">

                    <template v-if="$scopedSlots.default" slot-scope="{ item }">
                        <slot :item="item"></slot>
                    </template>

                    <slot slot="before" name="before"></slot>
                    <slot slot="after" name="after"></slot>
                </default-list>
            </div>

            <scrollbar
                v-model="scrollTop"
                axis="y"
                :passive="useNativeScroll"
                :theme="theme"
                :container="$refs.scrollable">
            </scrollbar>
        </div>

        <div class="scrollable-list__sticky-bottom">
            <slot name="sticky-bottom"></slot>
        </div>
    </div>
</template>

<script>
import { find } from './helpers/items_utils'
import throttle from 'lodash.throttle'
import DefaultList from './DefaultList.vue'
import Scrollbar from './Scrollbar.vue'

export default {
    components: {
        DefaultList,
        Scrollbar,
    },
    props: {
        size: { type: String, default: 'normal' },
        theme: { type: String, default: 'dark' },
        numItems: { type: Number, required: true },
        items: { type: Array, required: true },
        value: { type: String },
        highlightQuery: { type: String },
        transitionSorting: { type: Boolean, default: false },
        noGroupRendering: { type: Boolean, default: false },
        setActiveOnHover: { type: Boolean, default: true },
        enableScrollTop: { type: Boolean, default: false },
        showOverlay: { type: Boolean, default: false },
        initialOffset: { type: Number, default: 0 },
        itemsHaveAvatars: { type: Boolean, default: false },
        alwaysShowActive: { type: Boolean, default: false },
        canLoadMore: { type: Boolean, default: false },
        useNativeScroll: { type: Boolean, default: false },
    },
    data () {
        return {
            isListReady: false,
            scrollTop: 0,
        }
    },
    computed: {
        overlayHeight () {
            return this.showOverlay ? 10 : 0
        },
        itemHeight () {
            return this.isListReady ? this.$refs.list.assumedItemHeight : 0
        },
        maxHeight () {
            return this.numItems * this.itemHeight + 2 * this.overlayHeight
        },
    },
    mounted () {
        this.$nextTick(() => {
            window.addEventListener('resize', () => this.positionSelectList)
            this.positionSelectList()
            this.isListReady = true
        })
    },
    beforeDestroy () {
        window.removeEventListener('resize', () => this.positionSelectList)
    },
    methods: {
        onScroll (e) {
            if (this.useNativeScroll) {
                window.requestAnimationFrame(() => {
                    this.scrollTop = this.$refs.scrollable.scrollTop
                })
            }

            if (this.canLoadMore) {
                const scrollable = this.$refs.scrollable
                this.scrollTop = scrollable.scrollTop
                if (scrollable.scrollTop + scrollable.clientHeight >= scrollable.scrollHeight) {
                    this.$emit('load-more')
                }
            }
        },
        throttledOnScroll: throttle(function (e) {
            return this.onScroll(e)
        }, 200),
        scrollToTop () {
            this.scrollTop = 0
        },
        positionSelectList () {
            if (this.value) {
                const item = find(this.items, x => x.id === this.value)
                this.scrollTo(item ? item.key || item.id : this.value)
            }
        },
        scrollTo (itemId) {
            const itemElement = this.$el.querySelector(`[data-item-id="${itemId}"]`)

            if (itemElement) {
                const rootY = this.$refs.scrollable.getBoundingClientRect().top + document.documentElement.scrollTop
                const itemY = itemElement.getBoundingClientRect().top + this.$refs.scrollable.scrollTop

                this.scrollTop = itemY - rootY - (this.maxHeight - this.itemHeight) / 2

                this.$nextTick(() => {
                    const rootY = this.$refs.scrollable.getBoundingClientRect().top
                    const itemY = itemElement.getBoundingClientRect().top
                    const shownY = itemY - rootY + this.itemHeight / 2

                    this.$emit('scroll', shownY)
                })
            }
        },
        onActivate (item, isInitial) {
            if (!isInitial) {
                const currentScroll = this.$refs.scrollable.scrollTop
                const computedScrollableStyle = getComputedStyle(this.$refs.scrollable)
                const verticalPadding = parseFloat(computedScrollableStyle.paddingTop) + parseFloat(computedScrollableStyle.paddingBottom)
                const rootY = this.$refs.scrollable.getBoundingClientRect().top + document.documentElement.scrollTop
                const itemElement = this.$el.querySelector(`[data-item-id="${item.key || item.id}"]`)
                const itemY = itemElement ? itemElement.getBoundingClientRect().top : 0

                const upTarget = currentScroll + itemY - rootY - this.overlayHeight
                const downTarget = currentScroll + itemY - rootY - this.maxHeight + this.itemHeight - verticalPadding + this.overlayHeight

                if (currentScroll > upTarget) {
                    this.scrollTop = upTarget
                } else if (currentScroll < downTarget) {
                    this.scrollTop = downTarget
                }
            }
        },
        selectActiveItem (ev) {
            this.$refs.list.selectActiveItem(ev)
        },
        focus () {
            this.$refs.list.focus()
        },
        onKeyDown (ev) {
            this.$refs.list.onKeyDown(ev)
        },
        startUsingKeyboard () {
            this.$refs.list.startUsingKeyboard()
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

@overlay-height: 10px;
@scrollbar-width: 5px;
@sticky-bottom-height: 50px;

.scrollable-list {
    .regular-font();
    width: 100%;

    &__list-wrap {
        position: relative;
        overflow: hidden;
    }

    &__sticky {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: ~"calc(100% - 6px)";
    }

    &__sticky-bottom {
        position: absolute;
        bottom: 0;
        width: 100%;
    }

    &__list {
        overflow: hidden;
        box-sizing: content-box;
        width: 100%;

        &--native {
            overflow: scroll;
        }

        &--with-overlay {
            mask-image: linear-gradient(transparent 0%, black @overlay-height, black calc(100% - @overlay-height), transparent 100%);
        }

        &--with-bottom-slot {
            min-height: @sticky-bottom-height;
            mask-image: linear-gradient(transparent 0%, black @overlay-height, black calc(100% - @sticky-bottom-height - @overlay-height), transparent calc(100% - @sticky-bottom-height), transparent 100%);
        }
    }

    &__default-list {
        &--with-overlay {
            padding: @overlay-height 0;
        }

        &--with-bottom-slot {
            padding: @overlay-height 0 @sticky-bottom-height 0;
        }
    }

    &__scroll-top {
        font-size: 11px;
        color: @smog;
        cursor: pointer;

        &:focus {
            outline: none;
            color: black;
        }
    }
}
</style>
