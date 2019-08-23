<template>
    <div :class="[theme, { active: isActive, disabled: isDisabled, pressed: isPressed, multiline: isMultiline }] | prefix('chip--')" class="chip" tabindex="0" @click="$emit('click')">
        <div class="chip__label">{{ label }}</div>
        <div v-if="metadata" class="chip__metadata">{{ metadata }}</div>
        <span v-if="isRemovable" class="chip__remove-btn" @click.stop="$emit('remove')">
            <icon style="width: 8px; height: 8px;" name="x-bold"></icon>
        </span>
    </div>
</template>

<script>
import Icon from './Icon.vue'

export default {
    components: {
        Icon,
    },
    props: {
        theme: { type: String, default: 'dark' }, // dark | light
        label: { type: String, required: true },
        metadata: { type: String, default: '' },
        isActive: { type: Boolean, default: false },
        isRemovable: { type: Boolean, default: false },
        isDisabled: { type: Boolean, default: false },
        isPressed: { type: Boolean, default: false },
        isMultiline: { type: Boolean, defalt: false },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.chip {
    height: 20px;
    font-size: 12px;
    .medium-font();
    border-radius: 3px;
    display: inline-flex;
    align-items: center;
    user-select: none;
    cursor: pointer;
    transition: all @default-transition-time;
    outline: none;
    padding: 0 5px;

    &--disabled {
        cursor: default;
    }
    &--multiline {
        height: auto;
        padding: 3px 5px;
        white-space: pre;
    }
}

.chip__label {
    padding: 0 5px;
}

.chip__metadata {
    .regular-font();
    padding-right: 5px;
}

.chip__remove-btn {
    transition: all @default-transition-time;
    padding: 0 5px;
    margin-right: -5px;
}

/* DARK THEME */
.chip--dark {
    color: @cement;

    .chip__remove-btn {
        color: @charcoal;
    }

    &.chip--active {
        color: @cement;
        background-color: fade(@charcoal, 60%);

        .chip__remove-btn {
            color: fade(@cement, 60%);
        }

        &:hover .chip__remove-btn:hover {
            color: @cement;
        }
    }

    &.chip--disabled {
        color: @smog;
    }

    &:not(.chip--disabled) {
        &:hover {
            color: @white;
            background-color: @charcoal;

            .chip__remove-btn {
                color: fade(@cement, 60%);
            }
        }

        &:active,
        &.chip--pressed {
            color: @white;
            background-color: fade(@smog, 60%);

            .chip__remove-btn {
                color: @cement;
            }
        }
    }
}

/* LIGHT THEME */
.chip--light {
    color: @charcoal;

    .chip__remove-btn {
        color: fade(@charcoal, 20%);
    }

    &.chip--active {
        color: @void;
        background-color: fade(@cement, 60%);

        .chip__remove-btn {
            color: fade(@charcoal, 60%);
        }

        &:hover .chip__remove-btn:hover {
            color: @charcoal;
        }
    }

    &.chip--disabled {
        color: @ink;
    }

    &:not(.chip--disabled) {
        &:hover {
            color: @void;
            background-color: @cement;

            .chip__remove-btn {
                color: fade(@charcoal, 60%);
            }
        }

        &:active,
        &.chip--pressed {
            color: @void;
            background-color: fade(@ash, 40%);

            .chip__remove-btn {
                color: fade(@charcoal, 60%);
            }
        }
    }
}
</style>
