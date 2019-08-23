<template>
    <div :class="[theme] | prefix('search-status--')" class="search-status">
        <div v-if="isLoading" class="search-status__content">
            <icon name="loading" class="search-status__loading-icon spin" />
            <span class="search-status__loading-text">Loading</span>
        </div>
        <div v-else-if="isEmpty" class="search-status__content">
            <span class="search-status__empty-text">No items</span>
        </div>
    </div>
</template>

<script>
import Icon from './Icon.vue'

export default {
    components: {
        Icon,
    },
    props: {
        theme: { type: String, default: 'dark' },
        isLoading: { type: Boolean, default: false },
        isEmpty: { type: Boolean, default: false },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.search-status {
    .regular-font();

    &__content {
        font-size: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 50px;
    }

    &__loading-text {
        font-size: 12px;
        margin-left: 10px;
    }

    &__loading-icon {
        color: @blue;
    }

    &__empty-text {
        font-size: 16px;
    }
}

.search-status__loading-icon.search-status__loading-icon {
    width: 24px;
    height: 24px;
}

.search-status--light {
    color: @smog;
}

.search-status--dark {
    color: @charcoal;
}
</style>
