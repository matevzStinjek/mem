<template>
    <div v-click-outside="close" class="typeahead">
        <input-element ref="input" v-bind="inputData" :error="inputError" :autogrow="autogrow" :label="label" :track-name="trackName" class="typeahead__input" @keydown="onKeyDown" @keyup="updateCursorIndex" @focus="onInputFocus" @input="onInput" @blur="onBlur" @click="updateCursorIndex"/>

        <template v-if="showSuggestions">
            <scrollable-list v-if="$scopedSlots.default && hasSuggestions" ref="list" :items="suggestions" :num-items="10" :highlight-query="value" :style="aboveStyles" class="typeahead__suggestions" theme="light" :always-show-active="true" @select="onSelect" @blur="onBlur">
                <template slot-scope="{ item }">
                    <slot :item="item"></slot>
                </template>
                <icon v-if="isLoading" name="loading" class="spin typeahead__scrollable-list-icon" slot="sticky"/>
            </scrollable-list>
            <!-- duplication of scrollable-list line below is solving issue of current vue inability to prevent passing empty default scoped slots more than one level deep -->
            <scrollable-list v-else-if="hasSuggestions" ref="list" :items="suggestions" :num-items="10" :highlight-query="value" :style="aboveStyles" class="typeahead__suggestions" theme="light" :always-show-active="true" @select="onSelect" @blur="onBlur">
                <icon v-if="isLoading" name="loading" class="spin typeahead__scrollable-list-icon" slot="sticky"/>
            </scrollable-list>
            <div v-else-if="isLoading" class="typeahead__no-items-text" :style="aboveStyles">
                <icon name="loading" class="spin" />
            </div>
            <div v-else-if="noItemsText" class="typeahead__no-items-text" :style="aboveStyles">{{ noItemsText }}</div>
        </template>

        <scrollable-list ref="hiddenList" :items="suggestions" :num-items="10" class="typeahead__hidden-suggestions">
            <template v-if="$scopedSlots.default" slot-scope="{ item }">
                <slot :item="item"></slot>
            </template>
            <icon name="loading" class="spin typeahead__scrollable-list-icon" slot="sticky"/>
        </scrollable-list>
    </div>
</template>

<script>
import Input from './Input.vue'
import Icon from './Icon.vue'
import ScrollableList from './ScrollableList.vue'
import debounce from 'lodash.debounce'

export default {
    components: {
        inputElement: Input,
        ScrollableList,
        Icon,
    },
    props: {
        label: { type: String },
        value: { type: [String, Number], default: '' },
        autogrow: { type: Boolean },
        getSuggestions: { type: Function, required: true },
        noItemsText: { type: String },
        isValid: { type: Function },
        isAsync: { type: Boolean },
        trackName: { type: String, default: 'typeahead' },
        immediatelyShowSuggestions: { type: Boolean },
        suggestionsCharacter: { type: String },
        addNewEnabled: { type: Boolean },
    },
    data () {
        return {
            isLoading: false,
            isOpen: false,
            asyncSuggestions: [],
            cursorIndex: 0,
            aboveStyles: {},
        }
    },
    computed: {
        inputData () {
            return {
                ...this.$attrs,
                value: this.value,
                error: null,
            }
        },
        hasSuggestions () {
            return this.suggestions.length > 0
        },
        showSuggestions () {
            return this.isOpen && (this.isValueValid || this.hasSuggestions) && (this.immediatelyShowSuggestions || (!(this.value === null || typeof this.value === 'string' && this.value.length < this.minLength) && (this.suggestionsCharacter ? this.cursorToken : true)))
        },
        suggestionsQuery () {
            return this.suggestionsCharacter ? this.cursorToken : (this.value || '')
        },
        suggestions () {
            const suggestions = this.isAsync ? this.asyncSuggestions : this.getSuggestions(this.suggestionsQuery)
            if (this.addNewEnabled && this.suggestionsQuery && this.suggestionsQuery !== '' && suggestions.every(item => item.label !== this.suggestionsQuery)) {
                return [{ id: 'addNew', label: this.suggestionsQuery, metadata: 'Add new' }, ...suggestions]
            }
            return suggestions
        },
        inputError () {
            if (this.showSuggestions || this.suggestions.filter(item => !item.disabled).length > 0 || !this.isValid || ( this.value === null || typeof this.value === 'string' && this.value.length <= this.minLength)) {
                return null
            }
            return this.isValid(this.value)
        },
        isValueValid () {
            return this.isValid ? this.isValid(this.value) === null : true
        },
        cursorTokenBounds () {
            const lastSpaceIndex = this.value.substring(0, this.cursorIndex).lastIndexOf(' ') + 1
            const lastNewlineIndex = this.value.substring(0, this.cursorIndex).lastIndexOf('\n') + 1
            const fromIndex = Math.max(lastSpaceIndex, lastNewlineIndex)

            let toIndex = -1
            const nextSpaceIndex = this.value.indexOf(' ', fromIndex + 1)
            const nextNewlineIndex = this.value.indexOf('\n', fromIndex + 1)
            if (nextSpaceIndex === -1) {
                toIndex = nextNewlineIndex
            } else if (nextNewlineIndex === -1) {
                toIndex = nextSpaceIndex
            } else {
                toIndex = Math.min(nextSpaceIndex, nextNewlineIndex)
            }
            return {
                from: fromIndex,
                to: toIndex === -1 ? this.value.length : toIndex,
            }
        },
        cursorToken () {
            const cursorToken = this.value.substring(this.cursorTokenBounds.from, this.cursorTokenBounds.to)
            if (cursorToken.startsWith(this.suggestionsCharacter)) {
                return cursorToken.substring(1)
            }
            return null
        },
    },
    watch: {
        value (text) {
            this.$nextTick(() => {
                // Reset input state to normal
                if (text && text.length <= this.minLength) {
                    this.$refs.input.errorMessage = null
                }
            })
        },
        isOpen () {
            this.$nextTick(() => {
                // Reset input state to normal
                if (this.isOpen) {
                    this.$refs.input.errorMessage = null
                }
            })
        },
        suggestionsQuery (v, ov) {
            if (this.isAsync) {
                this.debouncedLoadAsyncOptions()
            }
        },
        suggestions (newSuggestions) {
            this.$nextTick(() => {
                const listHeight = this.$refs.hiddenList.$el.offsetHeight
                const rect = this.$el.getBoundingClientRect()
                const safeSpacing = 60
                const isAbove = rect.bottom + listHeight + safeSpacing > window.innerHeight
                this.aboveStyles = isAbove ? { 'bottom': `${this.$refs.input.$el.offsetHeight}px` } : {}
                // Activate using keyboard flag to prevent double click down/up
                // to switch to next item in the list
                if (this.$refs.list) {
                    this.$refs.list.startUsingKeyboard()
                }
            })
        },
    },
    beforeCreate () {
        this.minLength = 2
    },
    methods: {
        updateCursorIndex () {
            this.cursorIndex = this.$refs.input.$refs.input.selectionStart
        },
        onKeyDown (ev) {
            const stop = () => {
                ev.stopPropagation()
                ev.preventDefault()
            }
            if (ev.code === 'Enter') {
                if (this.$refs.list) {
                    stop()
                    this.$refs.list.selectActiveItem(ev)
                } else if (ev.ctrlKey || ev.metaKey) {
                    stop()
                    this.$emit('confirm')
                }
            } else if (this.$refs.list && ['ArrowUp', 'ArrowDown'].includes(ev.code)) {
                stop()
                this.$refs.list.onKeyDown(ev)
            }
        },
        focus () {
            this.$refs.input.focus()
        },
        onInputFocus () {
            if (this.isAsync) {
                this.asyncSuggestions = []
                this.loadAsyncOptions()
            }
            this.isOpen = true
            this.$emit('focus')
        },
        close () {
            if (this.isOpen) {
                this.isOpen = false
                this.cursorIndex = 0
                this.$emit('blur')
            }
        },
        onBlur (ev) {
            if (!this.$el.contains(ev.relatedTarget)) {
                this.close()
            }
        },
        onInput (v) {
            this.isOpen = true
            this.$emit('input', v)
            if (!this.onInputTrackingDebounced) {
                this.onInputTrackingDebounced = debounce(() => {
                    this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'search' })
                }, 1000)
            }
            this.onInputTrackingDebounced()
            this.cursorIndex = this.$refs.input.$refs.input.selectionStart
        },
        onSelect (suggestion) {
            let targetCursorIndex = suggestion.label.length
            if (this.suggestionsCharacter) {
                const newValue = `${this.value.substring(0, this.cursorTokenBounds.from)}${this.suggestionsCharacter}${suggestion.label} ${this.value.substring(this.cursorTokenBounds.to)}`
                this.$emit('input', newValue)
                targetCursorIndex = this.cursorTokenBounds.from + suggestion.label.length + 2
            } else {
                this.$emit('input', suggestion.label)
            }
            this.$emit('select', suggestion)
            this.close()

            this.$nextTick(() => {
                this.$refs.input.$refs.input.setSelectionRange(targetCursorIndex, targetCursorIndex)
                this.cursorIndex = targetCursorIndex
            })
        },
        loadAsyncOptions () {
            this.isLoading = true
            this.getSuggestions(this.suggestionsQuery)
                .then(options => {
                    this.asyncSuggestions = options
                })
                .catch(console.error)
                .finally(() => {
                    this.isLoading = false
                })
        },
        debouncedLoadAsyncOptions: debounce(function () {
            return this.loadAsyncOptions()
        }, 400),
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.typeahead {
    .regular-font();
    position: relative;

    &__suggestions {
        position: absolute;
        margin-top: -7px;
        background-color: white;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
        padding: 15px 0;
        z-index: @z-heaven;
    }

    &__hidden-suggestions {
        position: absolute;
        padding: 15px 0;
        visibility: hidden;
    }

    &__no-items-text {
        color: @charcoal;
        position: absolute;
        margin-top: -7px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
        padding: 20px;
        width: 100%;
        box-sizing: border-box;
        background: white;
        font-size: 16px;
        z-index: 1;
    }

    &__scrollable-list-icon {
        margin-left: 15px;
        color: @charcoal;
    }
}
</style>
