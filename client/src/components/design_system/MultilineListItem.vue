<template>
    <div :class="[theme, size, cssModifiers] | prefix('multiline-list-item--')" class="multiline-list-item">
        <div class="multiline-list-item__content">
            <p :class="cssModifiers | prefix('multiline-list-item__label--')" class="multiline-list-item__label">
                <template v-if="highlightQuery">
                    <span v-for="(part, index) in getParts(label)" :key="index" :style="part.bold ? { fontWeight: 'bold' } : {}">{{ part.text }}</span>
                </template>
                <template v-else>
                    {{ label }}
                </template>
            </p>
            <p v-if="metadata" :class="cssModifiers | prefix('multiline-list-item__metadata--')" class="multiline-list-item__metadata">
                <template v-if="highlightQuery">
                    <span v-for="(part, index) in getParts(metadata)" :key="index" :style="part.bold ? { fontWeight: 'bold' } : {}">{{ part.text }}</span>
                </template>
                <template v-else>
                    {{ metadata }}
                </template>
            </p>
        </div>

        <div v-if="$slots.right" class="multiline-list-item__slot">
            <slot name="right"></slot>
        </div>
    </div>
</template>

<script>
import { getTextHighlightParts } from './helpers/utils'

export default {
    props: {
        size: { type: String, required: false, default: 'normal' },
        theme: { type: String, required: false, default: 'dark' },
        label: { type: String, required: true },
        metadata: { type: String },
        selected: { type: Boolean },
        disabled: { type: Boolean },
        highlightQuery: { type: String },
    },
    computed: {
        cssModifiers () {
            return {
                selected: this.selected,
                disabled: this.disabled,
            }
        },
    },
    methods: {
        getParts (label) {
            return getTextHighlightParts(label, this.highlightQuery)
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.multiline-list-item {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;

    &--disabled {
        cursor: auto;
    }

    &__content {
        display: flex;
        flex-direction: column;
        justify-items: center;
        overflow: hidden;
    }

    &__label {
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        .regular-font();
        color: @cement;

        &--error { color: @red; }
        &--disabled { color: @ink; }
    }

    &__metadata {
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        .regular-font();
        color: @ink;
    }

    &__slot {
        margin-left: 10px;
    }
}

.multiline-list-item--dark {
    .multiline-list-item {
        &__label {
            color: @cement;
            &--selected { color: @void; }

            &:hover {
                &:not(.multiline-list-item--disabled)&:not(.multiline-list-item--selected) {
                    .multiline-list-item__label { color: @white; }
                }
            }
        }
    }
}

.multiline-list-item--light {
    .multiline-list-item {
        &__label {
            color: @charcoal;
            &--selected { color: @blue; }

            &:hover {
                &:not(.multiline-list-item--disabled)&:not(.multiline-list-item--selected) {
                    .multiline-list-item__label { color: @void; }
                }
            }
        }
    }
}

.multiline-list-item--condensed {
    padding: 3px 0;

    .multiline-list-item {
        &__label {
            font-size: 14px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        &__metadata {
            font-size: 11px;
            letter-spacing: 0.5px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    }
}

.multiline-list-item--normal {
    padding: 5px 0;

    .multiline-list-item {
        &__label {
            font-size: 18px;
        }

        &__metadata {
            font-size: 12px;
        }
    }
}

.multiline-list-item--phat {
    padding: 10px 0;

    .multiline-list-item {
        &__label {
            font-size: 22px;
        }

        &__metadata {
            font-size: 14px;
        }
    }
}
</style>
