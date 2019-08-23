<template>
    <div class="avatar" :class="['avatar--' + type, 'avatar--' + theme, { 'avatar--no-border': image }]">
        <span class="avatar__initials">{{ initials }}</span>

        <template v-if="icon">
            <icon :name="icon" class="avatar__icon"></icon>
        </template>

        <template v-if="image">
            <img :src="image" class="avatar__image" />
        </template>
    </div>
</template>

<script>
import Icon from './Icon.vue'

export default {
    components: { Icon },
    props: {
        initials: { type: String, default: '' },
        icon: { type: String, default: null },
        image: { type: String, default: null },
        type: { type: String, default: 'normal' }, // circle type: normal, full
        theme: { type: String, default: 'dark' },
    },
}
</script>

<style lang="less" scoped>
@import (reference) "~design-system/less/colors";

.avatar {
    position: relative;
    flex-shrink: 0;
    display: inline-block;
    width: 32px;
    height: 32px;
    border: 2px solid currentColor;
    border-radius: 100%;
    text-align: center;
    vertical-align: middle;
    line-height: 32px;
    padding: 0 3px;
    box-sizing: border-box;
    overflow: hidden;
    font-size: 12px;
    background-color: white;

    &--full {
        background-color: currentColor;

        .avatar__initials {
            color: white;
        }

        &.avatar--light .avatar__initials {
            color: @ink;
        }
    }

    &--no-border {
        border: none;
    }

    &__initials {
        position: relative;
        top: -2px;
        color: currentColor;
    }

    &__icon {
        margin-top: 6px;
        color: currentColor;
    }

    &__image {
        position: absolute;
        width: 100%;
        height: 100%;
    }
}
</style>
