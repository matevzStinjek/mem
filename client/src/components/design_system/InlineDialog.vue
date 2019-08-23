<template>
    <div class="inline-dialog" v-on="$listeners">
        <div v-if="$slots.header" class="inline-dialog__header">
            <slot name="header"></slot>
        </div>
        <slot></slot>
    </div>
</template>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.inline-dialog {
    position: absolute;
    width: 340px;
    background-color: @white;
    box-shadow: 1px 2px 5px 0 @shadow;
    font-size: 14px;
    z-index: @z-default;
    .regular-font();

    &__header {
        padding: 10px 15px;
        border-bottom: 1px solid @fog;
    }
}
</style>
