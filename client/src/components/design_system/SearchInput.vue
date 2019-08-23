<template>
    <input-element ref="input" :value="value" :label="label" :theme="theme" :size="size" :class="[theme] | prefix('search-input--')" class="search-input" @keyup="$emit('keyup', $event)" @input="$emit('input', $event)">
        <icon slot="before" name="search" />
        <icon v-if="isLoading" slot="right" name="loading" class="spin" />
        <icon v-if="value && value.length > 0" slot="right" name="clear" class="search-input__clear-icon" @click="$emit('input', null)" />
    </input-element>
</template>

<script>
import InputElement from './Input.vue'
import Icon from './Icon.vue'

export default {
    components: {
        InputElement,
        Icon,
    },
    props: {
        value: { type: String, default: '' },
        label: { type: String, default: 'Search' },
        isLoading: { type: Boolean, default: false },
        size: { type: String, default: 'normal' },
        theme: { type: String, default: 'dark' },
    },
    methods: {
        focus () {
            this.$refs.input.focus()
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.search-input {
    .regular-font();

    &__clear-icon {
        cursor: pointer;
        margin-left: 6px;
    }
}

.search-input__loading-icon.search-input__loading-icon {
    width: 24px;
    height: 24px;
}

.search-input--dark {
    .search-input__clear-icon {
        color: @cement;

        &:hover {
            color: white;
        }
    }
}

.search-input--light {
    .search-input__clear-icon {
        color: @charcoal;

        &:hover {
            color: black;
        }
    }
}
</style>

<style lang="less">
.search-input.search-input {
    .input-field__message-wrap {
        display: none;
    }
}
</style>
