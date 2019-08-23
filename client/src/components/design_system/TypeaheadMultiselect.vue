<template>
    <div class="typeahead-multiselect">
        <typeahead ref="typeahead" v-model="text" :get-suggestions="getAvailableSuggestions" :num-items="numSuggestionItems" :no-items-text="noItemsText" :label="label" :is-valid="isValid" :theme="theme" :track-name="trackName" @select="selectItem"></typeahead>

        <div ref="list" :style="{ maxHeight: `${numItems * 35}px` }" class="typeahead-multiselect__item-list">
            <div v-for="item in value" :key="item.id" class="typeahead-multiselect__item">
                <div class="typeahead-multiselect__item-label" @mouseenter="onHoverText($event, item.label)" @mouseleave="hideTooltip()">{{ textEllipsis(item.label) }}</div>

                <div class="typeahead-multiselect__item-metadata" @mouseenter="onHoverText($event, item.metadata)" @mouseleave="hideTooltip()">
                    <span class="typeahead-multiselect__item-metadata-text">{{ textEllipsis(item.metadata) }}</span>
                    <icon name="remove" class="typeahead-multiselect__item-remove" @click="removeItem(item)" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Icon from './Icon.vue'
import Typeahead from './Typeahead.vue'
import TooltipMixin from './helpers/tooltip_mixin'

export default {
    components: {
        Icon,
        Typeahead,
    },
    mixins: [TooltipMixin],
    props: {
        label: { type: String, required: true },
        value: { type: Array, required: true },
        getSuggestions: { type: Function, required: true },
        noItemsText: { type: String, default: 'No items' },
        theme: { type: String, default: 'dark' },
        isValid: { type: Function, required: false },
        numSuggestionItems: { type: Number, default: 10 },
        numItems: { type: Number, default: 10 },
        maxLength: { type: Number, default: 30 },
        trackName: { type: String, default: 'typeaheadMultiselect' },
    },
    data () {
        return {
            text: '',
        }
    },
    methods: {
        onHoverText (ev, text) {
            if (text.length > this.maxLength) {
                this.showTooltip(ev.target, text)
            }
        },
        selectItem (item) {
            this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'select-item' })
            this.$emit('input', this.value.concat([item]))
            this.text = ''
            this.$refs.typeahead.focus()
        },
        removeItem (item) {
            this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'remove-item' })
            this.$emit('input', this.value.filter(x => x.id !== item.id))
        },
        getAvailableSuggestions (v) {
            const suggestions = this.getSuggestions(v)
            const ids = this.value.map(x => x.id)
            return suggestions.filter(x => !ids.includes(x.id))
        },
        textEllipsis (text) {
            return text.length > this.maxLength ? text.substring(0, this.maxLength - 3) + '...' : text
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.typeahead-multiselect {
    .regular-font();

    &__item-list {
        overflow-y: auto;
        overflow-x: hidden;
        min-height: 70px;
    }

    &__item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        height: 20px;
    }

    &__tooltip-wrap {
        position: relative;
    }

    &__item-label {
        font-size: 14px;
        color: @charcoal;
    }

    &__item-metadata {
        display: flex;
        align-items: center;
    }

    &__item-metadata-text {
        font-size: 14px;
        color: @smog;
    }

    &__item-remove {
        margin-left: 15px;
        cursor: pointer;
        color: @charcoal;
        transition: color @default-transition-time;

        &:hover {
            color: black;
        }
    }
}

::-webkit-scrollbar {
    width: 5px;
}

::-webkit-scrollbar-track {
    background-color: transparent;
}

::-webkit-scrollbar-thumb {
    border-radius: 5px;
    background-color: @cement;
}

::-webkit-scrollbar-corner {
    background-color: transparent;
}
</style>
