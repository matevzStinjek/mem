<template>
    <div class="dropdown" v-click-outside="close">
        <div @click="open">
            <slot></slot>
        </div>
        <inline-dialog v-if="isOpen" class="dropdown__dialog">
            <scrollable-list :items="options" :value="value" :num-items="numItems" theme="light" @select="onSelect"/>
        </inline-dialog>
    </div>
</template>

<script>
import ScrollableList from './ScrollableList.vue'
import InlineDialog from './InlineDialog.vue'

export default {
    components: {
        ScrollableList,
        InlineDialog,
    },
    props: {
        options: { type: Array, required: true },
        value: { type: String },
        numItems: { type: Number, default: 10 },
        trackName: { type: String, default: 'list' },
    },
    data () {
        return {
            isOpen: false,
        }
    },
    methods: {
        open () {
            this.isOpen = true
        },
        close () {
            this.isOpen = false
        },
        onSelect (item) {
            this.$emit('input', item.id)
            this.$root.$emit('tracking-event', { type: 'dropdown', label: this.trackName, trigger: 'select', data: { id: item.id } })
            this.close()
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';

.dropdown__dialog {
    padding: 10px 0;
}
</style>
