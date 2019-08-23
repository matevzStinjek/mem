<template>
    <div :class="{ [$style.item]: true, [$style.item_disabled]: isDisabled, [$style.item_success]: isSuccess }" @click="onClick">
        <div v-if="$slots.icon || icon" :class="$style.icon">
            <slot v-if="$slots.icon" name="icon"></slot>
            <icon v-else-if="icon" :name="icon"/>
        </div>
        <span :class="[$style.text, { [$style.text_margin]: hasTextMargin }]">
            <slot></slot>
        </span>
        <tooltip v-if="tooltipText" theme="light" :class="$style.tooltip">{{ tooltipText }}</tooltip>
    </div>
</template>

<script>
import Icon from './Icon.vue'
import Tooltip from './Tooltip.vue'

export default {
    components: {
        Icon,
        Tooltip,
    },
    props: {
        icon: { type: String },
        tooltipText: { type: String },
        isDisabled: { type: Boolean, default: false },
        isSuccess: { type: Boolean, default: false },
        trackName: { type: String, default: 'actionButton' },
    },
    computed: {
        hasTextMargin () {
            return !!(this.$slots.default && (this.$slots.icon || this.icon))
        },
    },
    methods: {
        onClick (event) {
            this.$emit('click', event)
            this.$root.$emit('tracking-event', { type: 'button', label: this.trackName, trigger: 'click' })
        },
    },
}
</script>

 <style lang="less" module>
@import (reference) './less/common';

 .item {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    color: @cement;
    font-size: 11px;
    line-height: 10px;
    white-space: nowrap;
    user-select: none;
    transition: color @button-hover-transition-time ease-out;
    text-decoration: none;
    outline: none;
    height: 100%;
    cursor: pointer;

    &:hover:not(.item_disabled):not(.item_success) {
        color: @white;
    }
    &_disabled {
        cursor: auto;
        color: fade(@cement, 40%)
    }
    &_success {
        color: fade(@green, 80%);

        &:hover {
            color: @green;
        }
    }
}
.icon {
    display: inline-block;
}

.text {
    text-transform: uppercase;
    color: inherit;
    text-decoration: none;
    line-height: 16px;
    .medium-font();

    &_margin {
        margin-left: 10px;
    }
}

.tooltip.tooltip {
    top: 90%;
    width: 205px;
    text-transform: none;
    width: max-content;
    position: absolute;
}
</style>
