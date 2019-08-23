<template>
    <div :class="[theme, size] | prefix('default-list--')" class="default-list" tabindex="0" @wheel="hideTooltip" @focus="onFocus" @blur="onBlur" @keydown.up.prevent.stop="onKeyDown" @keydown.down.prevent.stop="onKeyDown" @keyup.enter.stop="selectActiveItem" @keyup.space.stop="selectActiveItem" @keyup.esc="blur" @mouseenter="isHovered = true" @mouseleave="isHovered = false">
        <slot name="before"></slot>

        <transition-group :name="transitionSorting && !firstRender ? 'default-list__item' : 'default-list__item-transitionless'" :duration="250" tag="div">
            <div
                v-for="item in shownItemsWithData"
                :key="item.key"
                :data-item-id="item.key || item.id"
                :style="item.height ? { height: `${item.height}px` } : {}"
                :class="item.modifiers | prefix('default-list__item--')"
                :title="item.nativeTitle"
                class="default-list__item"
                @click="clickItem(item.id, $event)"
                @mousemove="onItemHover($event, item)"
                @mouseleave="hideTooltip">
                <div v-if="item.isLeaf || noGroupRendering" :class="item.modifiers | prefix('default-list__item-content--')" :style="item.offset > 0 ? { paddingLeft: `${item.offset}px` } : {}" class="default-list__item-content">
                    <slot :item="item">
                        <default-list-item
                            :label="item.label"
                            :metadata="item.metadata"
                            :icon="item.icon"
                            :disabled="item.disabled"
                            :selected="item.id === value"
                            :highlight-query="highlightQuery"
                            :size="size"
                            theme="light" />
                    </slot>
                </div>
                <div v-else :style="item.offset > 0 ? { paddingLeft: `${item.offset}px` } : {}" class="default-list__item-content">
                    <slot :item="item" name="group">
                        <div v-if="item.label" class="default-list__group">{{ item.label }}</div>
                    </slot>
                </div>
            </div>
        </transition-group>

        <slot name="after"></slot>

        <div class="default-list__hidden-slots">
            <div ref="hiddenSlot">
                <slot :item="assumedItem">
                    <default-list-item :size="size" v-bind="assumedItem" />
                </slot>
            </div>
            <div ref="hiddenGroupSlot">
                <slot :item="assumedItem" name="group">
                    <div class="default-list__group">{{ assumedItem.label }}</div>
                </slot>
            </div>
        </div>
    </div>
</template>

<script>
import DefaultListItem from './DefaultListItem.vue'
import Tooltip from './Tooltip.vue'
import { flatten } from './helpers/items_utils'
import TooltipMixin from './helpers/tooltip_mixin'

export default {
    components: {
        DefaultListItem,
        Tooltip,
    },
    mixins: [TooltipMixin],
    props: {
        size: { type: String, default: 'normal' },
        theme: { type: String, default: 'dark' },
        items: { type: Array, required: true },
        value: { type: String },
        highlightQuery: { type: String },
        transitionSorting: { type: Boolean, default: false },
        noGroupRendering: { type: Boolean, default: false },
        setActiveOnHover: { type: Boolean, default: true },
        initialOffset: { type: Number, default: 0 },
        alwaysShowActive: { type: Boolean, default: false },
        itemsHaveAvatars: { type: Boolean, default: false },
    },
    data () {
        return {
            firstRender: true,
            isFocused: false,
            isUsingKeyboard: false,
            isHovered: false,
            activeId: null,
            itemHeight: null,
            groupHeight: null,
        }
    },
    computed: {
        flatItems () {
            return flatten(this.items)
        },
        flatSelectableItems () {
            return this.flatItems.filter(x => x.isLeaf && !x.disabled)
        },
        shownItemsWithData () {
            const activeId = this.alwaysShowActive || this.isUsingKeyboard && this.isFocused || (this.setActiveOnHover && this.isHovered) ? this.activeId : null

            return this.flatItems.map(item => {
                const isLeaf = item.isLeaf || this.noGroupRendering
                return {
                    ...item,
                    offset: this.initialOffset + this.getOffset(item),
                    height: this.transitionSorting ? (isLeaf ? this.assumedItemHeight : this.assumedGroupHeight) : null,
                    modifiers: {
                        leaf: isLeaf,
                        active: (item.key || item.id) === activeId,
                        'with-tooltip': !!item.tooltip,
                    },
                }
            })
        },
        assumedItemHeight () {
            return this.itemsHaveAvatars ? 60 : this.itemHeight || 30
        },
        assumedGroupHeight () {
            return this.groupHeight || 30
        },
    },
    watch: {
        flatSelectableItems (v) {
            const hasActiveId = this.activeId && v.find(item => (item.key || item.id) === this.activeId)
            if (!hasActiveId) {
                const defaultActiveItem = this.getDefaultActiveItem()
                if (defaultActiveItem) {
                    this.activeId = defaultActiveItem.key
                }
            }
        },
    },
    mounted () {
        this.$nextTick(() => {
            this.itemHeight = this.$refs.hiddenSlot.clientHeight
            this.groupHeight = this.$refs.hiddenGroupSlot.clientHeight
            this.firstRender = false
        })

        const checkSlotHeights = () => {
            if (this.itemHeight > 0 || this.groupHeight > 0) {
                clearInterval(this.intervalId)
                this.intervalId = null
            } else {
                this.itemHeight = this.$refs.hiddenSlot.clientHeight
                this.groupHeight = this.$refs.hiddenGroupSlot.clientHeight
            }
        }
        this.intervalId = setInterval(checkSlotHeights, 100)
    },
    beforeCreate () {
        this.assumedItem = { label: 'A', metadata: 'A', id: 'assumedItemId' }
    },
    created () {
        const activeItem = this.getDefaultActiveItem()
        if (activeItem) {
            this.activeId = activeItem.key
            this.$emit('activate', activeItem, true)
        }
    },
    beforeDestroy () {
        if (this.intervalId) {
            clearInterval(this.intervalId)
        }
    },
    methods: {
        getDefaultActiveItem () {
            if (this.flatSelectableItems.length === 0) {
                return null
            }

            let activeItem = this.flatSelectableItems[0]
            if (this.value) {
                const currentItem = this.flatSelectableItems.find(x => x.id === this.value)
                if (currentItem) {
                    activeItem = currentItem
                }
            }
            if (!activeItem.key) {
                activeItem.key = activeItem.id
            }
            return activeItem
        },
        onFocus (ev) {
            if (!this.isFocused) {
                this.isFocused = true
            }
        },
        onBlur (ev) {
            if (!this.$el.contains(ev.relatedTarget)) {
                this.isFocused = false
                this.$emit('blur', ev)
            }
        },
        blur () {
            this.$el.blur()
        },
        clickItem (itemId, ev) {
            this.isUsingKeyboard = false
            this.selectItem(itemId, ev)
        },
        selectItem (itemId, ev) {
            if (itemId) {
                const item = this.flatItems.find(x => x.key === itemId || x.id === itemId)
                if (item && !item.disabled && item.isLeaf) {
                    this.isFocused = true
                    this.activeId = item.key || item.id
                    this.$emit('select', item, ev)
                }
            }
        },
        selectActiveItem (ev) {
            this.selectItem(this.activeId, ev)
        },
        onItemHover (ev, item) {
            if (this.setActiveOnHover && item.isLeaf && (ev.movementX !== 0 || ev.movementY !== 0)) {
                this.activeId = item.key || item.id
                this.$emit('activate', item)
            }

            if (item.tooltip) {
                const element = this.$el.querySelector(`[data-item-id="${item.key || item.id}"]`)
                this.showTooltip(element, item.tooltip, item.tooltipTitle)
            }
        },
        focus () {
            this.$el.focus()
            this.startUsingKeyboard()
        },
        onKeyDown (ev) {
            const directionByKey = {
                'ArrowUp': -1,
                'ArrowDown': 1,
            }
            const direction = directionByKey[ev.code]
            if (!direction || this.flatSelectableItems.length === 0) {
                return
            }

            let item
            if (!this.isUsingKeyboard) {
                item = this.startUsingKeyboard()
            } else {
                const findId = this.activeId || this.value
                let activeIndex = this.flatSelectableItems.findIndex(x => x.key === findId || x.key === 'S_' + findId || x.id === findId)
                activeIndex += direction
                if (activeIndex > this.flatSelectableItems.length - 1) {
                    activeIndex = 0
                } else if (activeIndex < 0) {
                    activeIndex = this.flatSelectableItems.length - 1
                }
                item = this.flatSelectableItems[activeIndex]
            }

            this.activeId = (item.key || nextItem.id)
            this.$emit('activate', item)
        },
        startUsingKeyboard () {
            this.isUsingKeyboard = true

            let activeItem = this.flatSelectableItems.find(x => x.key === this.activeId || x.key === 'S_' + this.activeId || x.id === this.activeId)
            if (!activeItem) {
                activeItem = this.getDefaultActiveItem()
            }
            return activeItem
        },
        getOffset ({ depth, isLeaf }) {
            if (depth === 0) {
                return 0
            }

            const offset = 20
            if (this.areGroupsSelectable) {
                return depth * offset
            } else {
                if (isLeaf && !this.noGroupRendering) {
                    return (depth - 1) * offset
                } else {
                    return depth * offset
                }
            }
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

* {
    box-sizing: border-box;
}

.default-list {
    outline: none;
    width: 100%;
    .regular-font();

    &__hidden-slots {
        visibility: hidden;
        position: absolute;
    }

    &__item {
        padding: 0 15px;
        width: 100%;
        display: flex;
        align-items: center;

        &-enter-active,
        &-leave-active,
        &-move {
            pointer-events: none;
            transition: height 250ms ease-in, opacity 250ms ease-in;
        }

        &-enter,
        &-leave-to {
            height: 0 !important;
            opacity: 0;
        }
    }

    &__item-transitionless {
        &-leave-active {
            display: none;
        }
    }

    &__item-content {
        width: 100%;
        height: 100%;
        display: flex;

        &--with-tooltip:hover {
            position: relative;
        }
    }

    &__group {
        display: flex;
        align-items: center;
        font-size: 11px;
        letter-spacing: 0.25px;
        .regular-font();
        color: @smog;
    }
}

.default-list--dark {
    .default-list__item--active {
        background-color: @ink;
    }
}

.default-list--light {
    .default-list__item--active {
        background-color: @fog;
    }
}

.default-list--condensed {
    .default-list__group {
        height: 20px;
    }
}

.default-list--normal {
    .default-list__group {
        height: 30px;
    }
}

.default-list--phat {
    .default-list__group {
        height: 30px;
    }
}
</style>
