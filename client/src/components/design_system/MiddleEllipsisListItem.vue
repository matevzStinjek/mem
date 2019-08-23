<template>
    <div :class="[theme, size] | prefix('middle-ellipsis-list-item--')" class="middle-ellipsis-list-item">
        <div class="middle-ellipsis-list-item__label">
            <middle-ellipsis :text="label"></middle-ellipsis>
        </div>
        <div v-if="metadata" class="middle-ellipsis-list-item__metadata">
            {{ metadata }}
        </div>
    </div>
</template>

<script>
import MiddleEllipsis from './MiddleEllipsis.vue'

export default {
    components: {
        MiddleEllipsis,
    },
    props: {
        size: { type: String, required: false, default: 'normal' },
        theme: { type: String, required: false, default: 'dark' },
        label: { type: String, required: true },
        metadata: { type: String },
    },
}
</script>

<style lang="less">
@import (reference) './less/common';
@import './less/typography';

.middle-ellipsis-list-item {
    .regular-font();
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;

    &__label {
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        .regular-font();
        display: inline-block;
        transition: color @default-transition-time ease-out;
    }

    &__metadata {
        .regular-font();
        color: @ink;
    }

    &__icon {
        margin-left: 10px;
        color: @charcoal;
    }
}

.middle-ellipsis-list-item--condensed {
    height: 20px;

    .middle-ellipsis-list-item {
        &__label {
            font-size: 14px;
        }

        &__metadata {
            font-size: 11px;
            letter-spacing: 0.5px;
        }
    }
}

.middle-ellipsis-list-item--normal {
    height: 30px;

    .middle-ellipsis-list-item {
        &__label {
            font-size: 18px;
        }

        &__metadata {
            font-size: 12px;
        }
    }
}

.middle-ellipsis-list-item--phat {
    height: 45px;

    .middle-ellipsis-list-item {
        &__label {
            font-size: 22px;
        }

        &__metadata {
            font-size: 14px;
        }
    }
}
</style>
