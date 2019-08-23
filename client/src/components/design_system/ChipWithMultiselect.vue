<template>
    <div v-click-outside="close">
        <chip
            :is-active="value.length > 0"
            :is-pressed="isOpen"
            :label="chipLabel"
            :metadata="metadata"
            theme="dark"
            @click="chipClick">
        </chip>
        <inline-dialog v-if="isOpen">
            <multiselect
                :value="value"
                :label="searchLabel"
                :auto-reorder="false"
                :is-searchable="isSearchable"
                :init-search-query="searchQuery"
                :focus-search-on-init="true"
                :can-select-and-clear-all="canSelectAndClearAll"
                :can-clear-all="canClearAll"
                :options="options"
                :initial-offset="12"
                :size="size"
                theme="light"
                @search="searchChange"
                @input="selectionChange">
                <div slot-scope="{ item }" class="multiselect__list-item">
                    <slot :item="item">
                        <middle-ellipsis-list-item
                            :label="item.label"
                            :metadata="item.metadata"
                            size="condensed"
                            theme="light" />
                    </slot>
                </div>
            </multiselect>
        </inline-dialog>
    </div>
</template>

<script>
import Chip from './Chip.vue'
import InlineDialog from './InlineDialog.vue'
import Multiselect from './Multiselect.vue'
import MiddleEllipsisListItem from './MiddleEllipsisListItem.vue'

export default {
    components: {
        Chip,
        InlineDialog,
        Multiselect,
        MiddleEllipsisListItem,
    },
    props: {
        size: { type: String, default: 'normal' }, // condensed | normal
        value: { type: Array, required: true },
        options: { type: Array, required: true },
        chipLabel: { type: String, required: true },
        searchLabel: { type: String, required: true },
        isSearchable: { type: Boolean, default: false },
        canSelectAndClearAll: { type: Boolean, default: false },
        canClearAll: { type: Boolean, default: false },
    },
    data () {
        return {
            isOpen: false,
            searchQuery: '',
        }
    },
    computed: {
        metadata () {
            return `${this.value.length}/${this.options.reduce((acc, option) => acc + (option.items ? option.items.length : 1), 0)}`
        },
    },
    methods: {
        chipClick () {
            this.isOpen = !this.isOpen
        },
        close () {
            this.isOpen = false
        },
        selectionChange (selected) {
            this.$emit('input', selected)
        },
        searchChange (searchQuery) {
            this.searchQuery = searchQuery
            this.$emit('search', searchQuery)
        },
    },
}
</script>

<style lang="less" scoped>
.inline-dialog {
    margin-top: 10px;
    padding: 2px 0 10px 0;

    .multiselect__list-item {
        width: 100%;
        padding-right: 5px;
    }
}
</style>

<style lang="less">
// overrides
.inline-dialog {
    .multiselect__options {
        padding: 0;
        margin-top: 10px;
    }

    .search-input {
        margin: 0 15px;
        padding-top: 10px;
    }

    .scrollable-list__sticky {
        width: calc(100% - 20px);
    }

    .middle-ellipsis-list-item--normal {
        height: 26px;
    }
}
</style>
