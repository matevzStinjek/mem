<template>
    <div :class="[theme] | prefix('group--')" class="group">
        <div class="group__header">
            <div class="group__label">{{ label }}</div>
            <div v-if="description" class="group__tooltip-wrapper">
                <support-text :text="description"></support-text>
            </div>
        </div>
        <slot></slot>
    </div>
</template>

<script>
import SupportText from './SupportText.vue'

export default {
    components: {
        SupportText,
    },
    props: {
        label: { type: String },
        description: { type: String, required: false },
        theme: { type: String, default: 'dark' },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.group {
    position: relative;
    padding: 40px 30px 30px 30px;
    margin-top: 10px;
    border: 1px solid @charcoal;
    .regular-font();

    &__header {
        display: flex;
        align-items: center;
        position: absolute;
        top: -11px;
        padding: 0 10px;
        background-color: @void;
    }

    &__label {
        flex: 1;
        color: @ash;
        font-size: 18px;
    }

    &__tooltip-wrapper {
        width: 16px;
        height: 16px;
        margin: 0 5px 0 15px;
    }

    // themes
    &--light {
        border-color: @cement;

        .group {
            &__header {
                background-color: @white;
            }

            &__label {
                color: @smog;
            }
        }
    }
}
</style>
