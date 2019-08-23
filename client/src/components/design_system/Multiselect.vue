<template>
    <div :class="[theme] | prefix('multiselect--')" class="multiselect" @keyup="$emit('keyup', $event)" @click="$refs.list && $refs.list.focus()">
        <div v-if="isSearchable" class="multiselect__search-with-icon" @click.stop>
            <search-input
                ref="search"
                v-model="searchQuery"
                :label="label"
                :theme="theme"
                :size="searchSize || size"
                @keyup.down="$refs.list && $refs.list.focus()"
                @keyup="$emit('keyup', $event)" />
        </div>

        <div class="multiselect__options">
            <scrollable-list
                ref="list"
                :items="listItems"
                :num-items="numItems"
                :theme="theme"
                :transition-sorting="transitionSorting && !disableTransition"
                :no-group-rendering="areGroupsSelectable"
                :initial-offset="initialOffset"
                :set-active-on-hover="false"
                :enable-scroll-top="enableScrollTop"
                :items-have-avatars="itemsHaveAvatars"
                :show-overlay="true || showListOverlay"
                :can-load-more="true"
                class="multiselect__default-list"
                @select="onSelect"
                @load-more="loadAsyncOptions">

                <checkbox-element
                    v-if="listItems.length > 0 && (canSelectAndClearAll || canClearAll)"
                    slot="sticky"
                    :value="enabledValueLength === 0 ? false : enabledValueLength === allPossibleIds.length ? true : null"
                    :disabled="!canSelectAndClearAll && enabledValueLength === 0"
                    :size="size"
                    :theme="theme"
                    class="multiselect__select-all"
                    @input="setMultiple">

                    <span v-if="enabledValueLength === 0" class="multiselect__change-multiple-label">Select all</span>
                    <span v-else class="multiselect__change-multiple-label">Clear all ({{ enabledValueLength }})</span>
                </checkbox-element>

                <div slot-scope="{ item }" style="width: 100%;">
                    <checkbox-element
                        :disabled="item.disabled"
                        :title-text="item.tooltipTitle ? '' : item.label"
                        :disabled-text="item.disabledText"
                        :value="isChecked(item)"
                        :size="size"
                        :theme="theme"
                        tabindex="-1"
                        class="multiselect__checkbox">

                        <slot :item="item">
                            <default-list-item :label="item.label" :metadata="item.metadata" :disabled="item.disabled" :size="size" :theme="theme" :avatar-initials="item.avatarInitials" :avatar-icon="item.avatarIcon" class="multiselect__default-list-item"></default-list-item>
                            <span v-if="item.toggleText" class="multiselect__toggle-text" @click.stop.prevent="$emit('toggle-item', {id: item.id, active: !item.toggleValue})">{{ item.toggleText }}</span>
                            <checkbox-element v-if="item.toggleValue !== undefined" class="multiselect__toggle" :is-toggle="true" size="condensed" style="margin-top: 0" :value="item.toggleValue" :disabled="item.toggleDisabled" @input="$emit('toggle-item', {id: item.id, active: $event})" :stop-click-propagation="true"></checkbox-element>
                        </slot>
                    </checkbox-element>
                </div>

                <search-status v-if="isLoading || listItems.length === 0" slot="sticky-bottom" :theme="theme" :is-loading="isLoading" :is-empty="listItems.length === 0" />
            </scrollable-list>
        </div>
    </div>
</template>

<script>
import SearchInput from './SearchInput.vue'
import SearchStatus from './SearchStatus.vue'
import Checkbox from './Checkbox.vue'
import ScrollableList from './ScrollableList.vue'
import DefaultListItem from './DefaultListItem.vue'
import * as itemsUtils from './helpers/items_utils.js'
import debounce from 'lodash.debounce'

export default {
    components: {
        SearchInput,
        SearchStatus,
        checkboxElement: Checkbox,
        ScrollableList,
        DefaultListItem,
    },
    props: {
        value: { type: Array },
        options: { type: Array },
        autoReorder: { type: Boolean, default: true },
        isSearchable: { type: Boolean, default: false },
        initSearchQuery: { type: String, default: '' },
        focusSearchOnInit: { type: Boolean, default: false },
        hasScrollTop: { type: Boolean, default: true },
        canSelectAndClearAll: { type: Boolean, default: false },
        canClearAll: { type: Boolean, default: false },
        showListOverlay: { type: Boolean, default: false },
        areGroupsSelectable: { type: Boolean, default: false },
        transitionSorting: { type: Boolean, default: true },
        initialOffset: { type: Number, default: 0 },
        getOptions: { type: Function, required: false },
        label: { type: String, default: 'Search' },
        theme: { type: String, default: 'dark' },
        size: { type: String, default: 'normal' },
        searchSize: { type: String, required: false },
        numItems: { type: Number, default: 10 },
        loadAsyncDebounce: { type: Number, default: 0 },
        trackName: { type: String, default: 'multiselect' },
        itemsHaveAvatars: { type: Boolean, default: false },
        enableScrollTop: { type: Boolean, default: true },
        reloadQueryOptionsOnSelect: { type: Boolean, default: false },
    },
    data () {
        return {
            disableTransition: false,
            isLoading: false,
            searchQueryData: this.initSearchQuery,
            queryOptions: [],
        }
    },
    computed: {
        searchQuery: {
            get () {
                return this.searchQueryData
            },
            set (v) {
                const canDisableTransition = this.allOptions.length > this.numItems
                if (canDisableTransition) {
                    this.disableTransition = true
                }
                this.searchQueryData = v
                this.getOptionsPage = 0
                this.gotAllOptions = false
                this.isLoading = false
                this.$emit('search', v)
                this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'search' })
                this.debouncedLoadAsyncOptions()

                if (canDisableTransition) {
                    this.$nextTick(() => {
                        this.disableTransition = false
                    })
                }
            },
        },
        allOptions () {
            const result = itemsUtils.search(this.options, this.searchQuery)

            for (const queryItem of this.queryOptions) {
                if (!result.find(x => x.id === queryItem.id)) {
                    result.push(queryItem)
                }
            }

            return result
        },
        flatOptions () {
            return itemsUtils.flatten(this.allOptions)
        },
        flatNonFilteredOptions () {
            return itemsUtils.flatten(this.options)
        },
        allPossibleIds () {
            return this.flatOptions.filter(item => item.isLeaf && !item.disabled).map(item => item.id)
        },
        disabledValueIds () {
            return this.value.filter(id => {
                const item = this.flatNonFilteredOptions.find(x => x.id === id)
                return item && item.disabled
            })
        },
        enabledValueLength () {
            return this.value.length - this.disabledValueIds.length
        },
        listItems () {
            let result = this.allOptions

            if (this.autoReorder) {
                if (!this.areGroupsSelectable) {
                    const selectedItems = this.value.map(itemId => {
                        const item = itemsUtils.find(result, x => !x.items && x.id === itemId)
                        if (!item) {
                            return null
                        }
                        return {
                            ...item,
                            key: this.noTransitionKeys[item.id] || `S_${item.key || item.id}`,
                        }
                    }).filter(x => x)
                    const unselectedItems = itemsUtils.map(itemsUtils.filter(result, item => {
                        return !item.items && !this.value.includes(item.id)
                    }), item => {
                        if (this.noTransitionKeys[item.id]) {
                            return {
                                ...item,
                                key: this.noTransitionKeys[item.id],
                            }
                        } else {
                            return item
                        }
                    })

                    result = selectedItems.concat(unselectedItems)
                }

                result = itemsUtils.sortBy(result, item => {
                    if (item.items) {
                        if (item.items.every(x => x.disabled)) {
                            return -1
                        }
                        if (!this.areGroupsSelectable) {
                            return 0
                        }
                    }

                    const isChecked = this.isChecked(item)
                    if (isChecked === true) {
                        return 2
                    } else if (isChecked === null) {
                        return 1
                    } else if (item.disabled) {
                        return -1
                    }
                    return 0
                })
            }

            return result
        },
    },
    created () {
        this.noTransitionKeys = {}
        this.getOptionsPage = 0
        this.gotAllOptions = false
        this.debouncedLoadAsyncOptions = debounce(this.loadAsyncOptions, this.loadAsyncDebounce)
    },
    mounted () {
        this.loadAsyncOptions()
        this.$nextTick(() => {
            if (this.focusSearchOnInit && this.$refs.search) {
                this.$refs.search.focus()
            }
        })
    },
    methods: {
        onSelect (item) {
            this.setChecked(item, this.isChecked(item) ? false : true)
            this.$refs.list.focus()
            if (this.reloadQueryOptionsOnSelect) {
                this.getOptions(this.searchQuery).then(result => this.queryOptions = result)
            }
        },
        setMultiple (value) {
            const ids = value ? this.disabledValueIds.concat(this.allPossibleIds) : this.disabledValueIds
            this.disableTransition = true
            this.$emit('input', ids)
            this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'select-bulk', data: { isSelectAll: value, isClearAll: !value } })
            this.$refs.list.focus()
            this.$nextTick(() => {
                this.disableTransition = false
            })
        },
        loadAsyncOptions () {
            if (this.getOptions && !this.isLoading && !this.gotAllOptions) {
                this.isLoading = true
                const query = this.searchQuery
                this.getOptions(query, this.getOptionsPage).then(result => {
                    if (this.searchQuery === query) {
                        this.disableTransition = true

                        if (this.getOptionsPage === 0) {
                            this.queryOptions = []
                        }

                        let gotAnyNewOptions = false
                        for (const item of result) {
                            if (!this.queryOptions.find(x => x.id === item.id)) {
                                this.queryOptions.push(item)
                                gotAnyNewOptions = true
                            }
                        }
                        if (!gotAnyNewOptions) {
                            this.gotAllOptions = true
                        }
                        this.getOptionsPage += 1

                        this.isLoading = false

                        this.$nextTick(() => {
                            this.disableTransition = false
                        })
                    }
                })
            }
        },
        setChecked (option, isChecked) {
            if (!option.disabled) {
                if (option.isLeaf !== false) {
                    const valueWithout = this.value.filter(id => id !== option.id)

                    if (this.autoReorder) {
                        const firstUnselectedItem = this.flatOptions.find(x => !valueWithout.includes(x.id))
                        let disableTransition = false
                        if (isChecked) {
                            disableTransition = firstUnselectedItem && firstUnselectedItem.id === option.id
                        } else {
                            disableTransition = this.value.length > 0 && this.value[this.value.length - 1] === option.id && firstUnselectedItem && firstUnselectedItem.id === option.id
                        }

                        if (disableTransition) {
                            this.noTransitionKeys[option.id] = this.noTransitionKeys[option.id] || (option.key || option.id)
                        } else {
                            if (this.noTransitionKeys[option.id]) {
                                this.noTransitionKeys[option.id] = this.noTransitionKeys[option.id].startsWith('S_') ? option.id : 'S_' + option.id
                            }
                        }
                    }

                    if (isChecked) {
                        this.$emit('input', valueWithout.concat([option.id]))
                    } else {
                        this.$emit('input', valueWithout)
                    }
                    this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'select', data: { id: option.id, isChecked } })
                } else {
                    const leafIds = option.leafItems.filter(item => !item.disabled).map(item => item.id)
                    const valueWithout = this.value.filter(id => !leafIds.includes(id))
                    if (isChecked) {
                        this.$emit('input', valueWithout.concat(leafIds))
                    } else {
                        this.$emit('input', valueWithout)
                    }
                    this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'select-group', data: { id: option.id, isChecked } })
                }
            }
        },
        isChecked (option) {
            if (!option.items && option.isLeaf !== false) {
                return this.value.includes(option.id)
            } else {
                let allChecked = true
                let someChecked = false
                const leafItems = option.leafItems || itemsUtils.getLeafItems(option)
                const leafIds = leafItems.filter(item => !item.disabled).map(item => item.id)
                for (const id of leafIds) {
                    if (!this.value.includes(id)) {
                        allChecked = false
                    } else {
                        someChecked = true
                    }
                }
                return allChecked ? true : (!someChecked ? false : null)
            }
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.multiselect {
    .regular-font();
    height: fit-content;
    display: flex;
    flex-direction: column;

    &__search-with-icon {
        flex: none;
        display: flex;
    }

    &__options {
        position: relative;
        flex: auto;
        overflow-x: hidden;
        margin-top: 5px;
        padding-left: 5px;
        padding-right: 5px;
        clip-path: inset(0 0 0 0);
    }

    .multiselect__change-multiple.multiselect__change-multiple {
        height: 24px;
        margin-top: 0;
        display: flex;
        align-items: center;
    }

    &__change-multiple-label {
        color: @smog;
        font-size: 12px;
    }

    &__checkbox {
        width: 100%;
    }

    &__metadata-title {
        color: @smog;
        font-size: 12px;
    }

    &__toggle-text {
        font-size: 12px;
        line-height: 12px;
        margin-right: 8px;
        user-select: none;
    }
}

.multiselect__option > .multiselect__checkbox {
    margin-top: 0;
    margin-left: -5px;
}
</style>

<style lang="less">
.multiselect__default-list {
    .default-list__item.default-list__item.default-list__item {
        padding: 0;
    }

    .multiselect__select-all.multiselect__select-all {
        margin-top: 0;
        height: auto;
    }

    .multiselect__checkbox.multiselect__checkbox {
        margin-top: 0;
        height: auto;
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;

        .checkbox-element__check-row {
            height: auto;
        }
    }

    .multiselect__checkbox:hover {
        &.checkbox-element--light .default-list-item__label:not(.default-list-item__label--disabled) {
            color: black;
        }

        &.checkbox-element--light .multiline-list-item__label:not(.multiline-list-item__label--disabled) {
            color: black;
        }

        &.checkbox-element--dark .default-list-item__label:not(.default-list-item__label--disabled) {
            color: white;
        }

        &.checkbox-element--dark .multiline-list-item__label:not(.multiline-list-item__label--disabled) {
            color: white;
        }
    }
}
</style>
