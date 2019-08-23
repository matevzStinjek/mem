<template>
    <div :class="['selectbox--' + size, 'selectbox--' + theme]" :title="disabledText" :id="label | slugify" class="selectbox" tabindex="0" @focus="setFocus()" @blur="clearFocus()"
         @keyup="$emit('keyup', $event)" @keyup.esc.stop="handleEsc" @keyup.up="openSelectList()" @keyup.down="openSelectList()" @keyup.enter="openSelectList()" @keyup.left.stop @keyup.right.stop @keyup.delete.stop>

        <div :class="cssStates | prefix('selectbox__label-text--')" class="selectbox__label-text">
            {{ mappedLabelText }}
        </div>

        <div @click="openSelectList()" data-id="selectbox-select-row">
            <slot name="activator" :openSelectList="openSelectList">
                <div :class="cssStates | prefix('selectbox__select-row--')" class="selectbox__select-row">
                    <div class="selectbox__loader" v-if="isLoading">
                        <icon name="loading" class="spin selectbox__loader-icon"></icon>
                    </div>
                    <default-list-item :label="selectedLabelText" :metadata="selectedMetadataText" :title="states.disabled ? mappedDisabledText : selectedLabelText" :theme="theme" :size="size" :disabled="disabled" class="selectbox__current-item" />
                    <div class="selectbox__arrow-wrapper">
                        <div :class="cssStates | prefix('selectbox__arrow-down--')" class="selectbox__arrow-down"></div>
                    </div>
                </div>
            </slot>
        </div>

        <div :class="cssStates | prefix('selectbox__helper-text--')" class="selectbox__helper-text">
            {{ mappedHelperText }}
        </div>

        <div v-click-outside="closeSelectList" v-if="isOpen" ref="menu" class="selectbox__select-list-wrap selectbox__select-list-wrap--override" @click="$refs.list ? $refs.list.focus() : null">
            <div class="selectbox__list-frame"></div>
            <div :class="{ 'with-search': isSearchable } | prefix('selectbox__select-list--')" class="selectbox__select-list">
                <div class="selectbox__select-list-content" data-id="selectbox-select-list">
                    <div v-if="isSearchable" class="selectbox__search-wrapper" @click.stop>
                        <search-input ref="search" v-model="searchText" :is-loading="isSearchLoading" :size="size" label="Search" theme="light" @input="setSearch" />
                    </div>

                    <div class="selectbox__scrollable-list-wrap">
                        <div v-if="listItems.length === 0 && !isSearchLoading" class="selectbox__no-items-text">
                            {{ noItemsText }}
                        </div>
                        <scrollable-list v-else-if="canSelectMultiple" ref="list" :value="mappedScrollableListValue" :items="listItems" :num-items="isSearchable ? 6 : 8" :size="size" theme="light" class="selectbox__scrollable-list" @select="selectValue" @scroll="onScroll" :items-have-avatars="itemsHaveAvatars" :use-native-scroll="useNativeScroll">
                            <div slot-scope="{ item }" style="width: 100%;">
                                <checkbox
                                    :disabled="item.disabled"
                                    :title-text="item.tooltipTitle ? '' : item.label"
                                    :disabled-text="item.disabledText"
                                    :value="value.includes(item.id)"
                                    :size="size"
                                    theme="light"
                                    tabindex="-1">

                                    <slot :item="item">
                                        <default-list-item :label="item.label" :metadata="item.metadata" :disabled="item.disabled" :size="size" :avatar-initials="item.avatarInitials" :avatar-icon="item.avatarIcon" :avatar-image="item.avatarImage" theme="light"></default-list-item>
                                    </slot>
                                </checkbox>
                            </div>
                        </scrollable-list>
                        <scrollable-list v-else ref="list" :value="value" :items="listItems" :num-items="isSearchable ? 6 : 8" :size="size" theme="light" class="selectbox__scrollable-list" @select="selectValue" @scroll="onScroll" :items-have-avatars="itemsHaveAvatars">
                            <div slot-scope="{ item }" style="width: 100%;">
                                <slot :item="item">
                                    <default-list-item :label="item.label" :metadata="item.metadata" :disabled="item.disabled" :size="size" :avatar-initials="item.avatarInitials" :avatar-icon="item.avatarIcon" :avatar-image="item.avatarImage" theme="light"></default-list-item>
                                </slot>
                            </div>
                        </scrollable-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import debounce from 'lodash.debounce'
import SearchInput from './SearchInput.vue'
import ScrollableList from './ScrollableList.vue'
import DefaultListItem from './DefaultListItem.vue'
import Checkbox from './Checkbox.vue'
import Icon from './Icon.vue'
import * as itemsUtils from './helpers/items_utils.js'

export default {
    components: {
        SearchInput,
        ScrollableList,
        DefaultListItem,
        Checkbox,
        Icon,
    },
    props: {
        label: { type: String, required: false, default: '' },
        placeholder: { type: String, required: false, default: 'Please select' },
        disabled: { type: Boolean, default: false },
        helperText: { type: String, required: false, default: '' },
        disabledText: { type: String, required: false, default: '' },
        errorText: { type: String, required: false, default: '' },
        warningText: { type: String, required: false, default: '' },
        options: { type: Array, required: true },
        value: { type: [String, Array], required: false, default: '' },
        getOptions: { type: Function, required: false, default: null },
        isSearchable: { type: Boolean, required: false, default: false },
        isUnselectable: { type: Boolean, required: false, default: false },
        showSelectedMetadata: { type: Boolean, required: false, default: false },
        size: { type: String, required: false, default: 'normal' },
        theme: { type: String, required: false, default: 'dark' },
        canSelectMultiple: { type: Boolean, default: false },
        trackName: { type: String, required: false },
        isLoading: { type: Boolean, default: false },
        itemsHaveAvatars: { type: Boolean, default: false },
        useNativeScroll: { type: Boolean, default: false },
        setSearchDebounce: { type: Number, default: 400 },
        noItemsText: { type: String, default: 'No items' },
        openAsDropdown: { type: Boolean, default: false },
    },
    data () {
        return {
            isOpen: false,
            focused: false,
            searchTextValue: '',
            isSearchLoading: false,
            asyncOptions: [],
        }
    },
    computed: {
        searchText: {
            get () {
                return this.searchTextValue
            },
            set (v) {
                this.$emit('update-search', v)
                if (!v) {
                    this.searchTextValue = ''
                } else {
                    this.setSearch(v)
                }
            },
        },
        states () {
            return {
                error: !!this.errorText,
                warning: !!this.warningText,
                disabled: this.disabled || this.isLoading,
                selected: this.value !== null,
                focused: this.focused,
                loading: this.isLoading,
            }
        },
        cssStates () {
            return {
                ...this.states,
                'with-metadata': this.selectedMetadataText !== '',
                'with-search': this.isSearchable,
            }
        },
        mappedLabelText () {
            return this.label
        },
        selected () {
            return this.value && itemsUtils.find(this.options, o => o.id === this.value)
        },
        selectedLabelText () {
            return this.selected ? this.selected.label : this.placeholder
        },
        selectedMetadataText () {
            return this.showSelectedMetadata && this.selected ? this.selected.metadata : ''
        },
        mappedHelperText () {
            if (this.errorText) {
                return this.errorText
            } else if (this.warningText) {
                return this.warningText
            } else {
                return this.helperText ? this.helperText : ''
            }
        },
        mappedDisabledText () {
            return this.disabled ? this.disabledText : ''
        },
        mappedScrollableListValue () {
            return this.canSelectMultiple ? null : this.value
        },
        listItems () {
            if (this.getOptions !== null) {
                return this.asyncOptions
            }

            let options = this.options

            if (this.isUnselectable) {
                options = [{ id: 'CLEAR_SELECTION', label: this.placeholder }].concat(options)
            }

            const cleanQuery = (this.searchText || '').trim(' ').toLowerCase()
            if (cleanQuery.length > 0) {
                options = itemsUtils.filter(options, (option) => {
                    return (option.label && option.label.toLowerCase().indexOf(cleanQuery) >= 0) ||
                        (option.metadata && option.metadata.toLowerCase().indexOf(cleanQuery) >= 0)
                })
            }

            options = itemsUtils.map(options, option => {
                if (!option.items) {
                    return option
                }

                return {
                    ...option,
                    label: option.label.toUpperCase(),
                }
            })

            return options
        },
    },
    watch: {
        searchText () {
            this.debouncedLoadAsyncOptions()
        },
    },
    created () {
        this.setSearch = debounce(this.setSearchText, this.setSearchDebounce)
    },
    methods: {
        setFocus () {
            if (!this.disabled) {
                this.focused = true
                this.$root.$emit('tracking-event', { type: 'input', label: this.trackName || this.label, trigger: 'focus' })
                this.$emit('focus')
            }
        },
        clearFocus () {
            this.focused = false
        },
        handleEsc () {
            const wasOpen = this.isOpen

            this.closeSelectList()

            if (wasOpen) {
                this.$el.focus()
            } else {
                this.$el.blur()
                this.clearFocus()
            }
        },
        openSelectList () {
            if (this.isOpen) {
                this.$refs.list.focus()
            } else {
                if (this.disabled) {
                    return
                }

                this.searchTextValue = ''
                this.isOpen = true

                if (!this.focused) {
                    this.$emit('focus')
                }

                this.$nextTick(() => {
                    if (this.isSearchable) {
                        this.$refs.search.focus()
                        this.loadAsyncOptions()
                    } else {
                        this.$refs.list.focus()
                    }

                    this.onScroll(0)
                })
            }
        },
        closeSelectList () {
            if (!this.disabled) {
                this.focused = true
            }

            this.isOpen = false
            this.activeId = null

            this.$el.focus()
        },
        selectValue (v) {
            if (v.id === 'CLEAR_SELECTION') {
                this.$emit('input', null)
            } else {
                this.$emit('input', v.id, v)
            }
            if (!this.canSelectMultiple) {
                this.closeSelectList()
            }
        },
        onScroll (shownY) {
            if (!this.$slots['activator'] && this.$refs.list) {
                const rootY = this.$el.getBoundingClientRect().top
                const menuRect = this.$refs.menu.getBoundingClientRect()
                const menuY = menuRect.top
                const menuHeight = menuRect.height
                const listY = this.$refs.list.$el.getBoundingClientRect().top
                const headerHeight = listY - menuY

                const targetOffset = -shownY - headerHeight + 30
                const minOffset = -rootY
                const maxOffset = -rootY - menuHeight + document.documentElement.clientHeight

                this.$refs.menu.style.top = this.openAsDropdown ? '0px' : `${Math.max(minOffset, Math.min(maxOffset, targetOffset))}px`
            }
        },
        setSearchText (text) {
            this.searchTextValue = text
        },
        loadAsyncOptions () {
            if (this.getOptions !== null) {
                this.isSearchLoading = true
                this.getOptions(this.searchText || '')
                    .then(options => {
                        this.asyncOptions = options
                    })
                    .catch(console.error)
                    .finally(() => {
                        this.isSearchLoading = false
                    })
            }
        },
        debouncedLoadAsyncOptions: debounce(function () {
            return this.loadAsyncOptions()
        }, 400),
        onKeyDown (ev) {
            this.$refs.list.onKeyDown(ev)
        },
    },
}
</script>

<style lang="less">
@import (reference) './less/common';

.selectbox {
    &__select-row--error .default-list-item .default-list-item__label {
        color: @red;
    }

    &__select-row .default-list-item--light .default-list-item__label--disabled {
        color: @cement;
    }

    &__select-row:hover:not(&__select-row--error) {
        .default-list-item--light .default-list-item__label:not(.default-list-item__label--disabled) {
            color: black;
        }

        .default-list-item--dark .default-list-item__label:not(.default-list-item__label--disabled) {
            color: white;
        }
    }
}
</style>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

* {
    box-sizing: border-box;
}

.selectbox {
    position: relative;
    width: 100%;
    .regular-font();
    outline: none;

    &__label-text {
        height: 13px;
        display: flex;
        align-items: center;
        font-size: 11px;
        letter-spacing: 0.5px;
        color: @ash;

        &--focused { color: @blue; }

        &--disabled { color: @charcoal; }
    }

    &__loader {
        position: absolute;
        left: 0;
    }

    &__loader-icon {
        color: @blue;
    }

    &__helper-text {
        height: 17px;
        display: flex;
        align-items: flex-end;
        font-size: 11px;
        letter-spacing: 0.5px;
        color: @ash;

        &--error { color: @red; }
        &--warning { color: @yellow; }
        &--disabled { color: @charcoal; }
    }

    &__arrow-wrapper {
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    &__arrow-down {
        border-width: 5px 6px 0 6px;
        border-style: solid;
        transition: border-color @default-transition-time ease-out;
        border-color: @cement transparent transparent transparent;

        &--disabled {
            border-style: dashed;
            border-top-color: @charcoal;
        }
    }

    &__select-row {
        position: relative;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-width: 0 0 2px 0;
        border-style: solid;
        cursor: pointer;
        transition: border-color @default-transition-time ease-out;
        border-color: @charcoal;
        transition: border-color @default-transition-time ease-out, padding-left @default-transition-time ease-out;

        &:hover:not(&--disabled):not(&--focused) {
            border-style: solid;
            border-color: @smog;

            .selectbox__arrow-wrapper .selectbox__arrow-down { border-top-color: @white; }
        }

        &--focused {
            border-color: @blue;

            .selectbox__arrow-wrapper .selectbox__arrow-down { border-top-color: @blue; }
        }

        &--disabled {
            cursor: auto;
            border-style: dashed;
            border-color: @charcoal;

            .default-list-item {
                cursor: default;
            }
        }

        &--loading {
            padding-left: 26px;
        }
    }

    &__current-item {
        width: calc(~'100% - 20px');
    }

    &__select-list-wrap {
        position: absolute;
        top: 0;
        left: -15px;
        width: calc(~'100% + 2 * 15px');
        z-index: @z-highest;

        &--with-search {
            top: -15px;
            left: -45px;
            width: calc(~'100% + 45px + 15px');
        }
    }

    &__list-frame {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transform-origin: center center;
        background-color: @white;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
        animation: list-frame-open 0.3s ease-out;
        animation-fill-mode: forwards;

        @keyframes list-frame-open {
            from {
                background-color: transparent;
                transform: scale3d(1, 0.8, 1);
            }

            66.67% {
                transform: scale3d(1, 1.025, 1);
                background-color: @white;
            }

            90% {
                background-color: @white;
            }

            to {
                transform: scale3d(1, 1, 1);
                background-color: @white;
            }
        }
    }

    &__select-list {
        .regular-font();
    }

    &__select-list-content {
        position: relative;
        opacity: 0;
        animation: list-content-open 0.2s ease-out 0.1s forwards;

        @keyframes list-content-open {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    }

    &__search-wrapper {
        margin: 15px 15px 0 15px;
    }

    &__scrollable-list-wrap {
        width: 100%;
    }

    &__no-items-text {
        color: @charcoal;
        font-size: 16px;
        padding: 15px 45px;
    }
}

.selectbox--light {
    .selectbox__label-text {
        color: @smog;

        &--disabled {
            color: @cement;
        }

        &--focused {
            color: @blue;
        }
    }

    .selectbox__arrow-down {
        border-top-color: @charcoal;

        &--disabled {
            border-top-color: @cement;
        }
    }

    .selectbox__select-row {
        border-color: @cement;

        &:hover:not(.selectbox__select-row--focused):not(.selectbox__select-row--disabled) {
            border-color: @smog;

            .selectbox__arrow-wrapper .selectbox__arrow-down { border-top-color: @void; }
        }

        &--focused {
            border-color: @blue;
        }

        .selectbox__arrow-down--focused { border-top-color: @blue; }
    }

    .selectbox__helper-text {
        color: @smog;

        &--error { color: @red; }
        &--warning { color: @yellow; }
        &--disabled { color: @cement; }
    }
}

.selectbox--condensed {
    .selectbox {
        &__label-text {
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        &__helper-text {
            height: 12px;
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        &__arrow-down { border-width: 3px 3.5px 0 3.5px; }

        &__select-row {
            height: 22px;
        }

        &__select-list-wrap {
            left: -10px;
            width: calc(~'100% + 2 * 10px');

            &--with-search {
                top: -10px;
                left: -35px;
                width: calc(~'100% + 35px + 10px');
            }
        }

        &__search-wrapper {
            margin: 10px 10px 12px 6px;
        }
    }
}

.selectbox--phat {
    .selectbox {
        .selectbox__label-text {
            height: 21px;
            font-size: 14px;
        }

        .selectbox__helper-text {
            height: 17px;
            font-size: 12px;
        }

        &__select-row {
            height: 45px;
        }

        &__select-list-wrap {
            &--with-search { top: -18px; }
        }

        .selectbox__loader-icon {
            width: 24px;
            height: 24px;
        }
    }
}
</style>

<style lang="less">
.selectbox.selectbox {
    .input-field__message-wrap {
        display: none;
    }
}

.selectbox__scrollable-list {
    .scrollable-list__list::-webkit-scrollbar {
        display: none;
    }
}

.selectbox--condensed {
    .selectbox__select-list--with-search {
        .default-list__item {
            padding-left: 36px;
        }
    }

    .selectbox__scrollable-list {
        margin-top: 15px;

        .scrollable-list__list {
            padding-bottom: 15px;
        }
    }
}

.selectbox--normal {
    .selectbox__select-list--with-search {
        .default-list__item {
            padding-left: 44px;
        }
    }

    .selectbox__scrollable-list {
        margin-top: 10px;

        .scrollable-list__list {
            padding-bottom: 10px;
        }
    }
}

.selectbox--phat {
    .selectbox__select-list--with-search {
        .default-list__item {
            padding-left: 44px;
        }
    }

    .selectbox__scrollable-list {
        margin-top: 15px;

        .scrollable-list__list {
            padding-bottom: 15px;
        }
    }
}
</style>
