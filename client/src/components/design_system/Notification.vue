<template>
    <div :class="[ type, { 'hidden': !isShown } ] | prefix('notification--')" class="notification">
        <div class="notification--icon notification--center">
            <slot name="icon"/>
        </div>
        <div class="notification--message notification--center">
            {{ message }}
        </div>
        <div class="notification--icon notification--center notification--icon__close" @click="$emit('close')">
            <icon name="close"/>
        </div>
    </div>
</template>

<script>
import Icon from './Icon.vue'

export default {
    name: 'notification',
    components: {
        Icon,
    },
    props: {
        message: { type: String, required: false, default: '' },
        type: { type: String, required: true, default: 'warning' },
    },
    data () {
        return {
            isShown: false,
        }
    },
    beforeDestroy () {
        if (this.timeoutId) {
            clearTimeout(this.timeoutId)
        }
    },
    methods: {
        show (timeout = 5000) {
            this.isShown = true
            if (this.timeoutId) {
                clearTimeout(this.timeoutId)
            }
            this.timeoutId = setTimeout(this.hide, timeout)
        },
        hide () {
            this.isShown = false
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';

@slide-in-out-animation-time: 0.4s;

.notification {
    position: absolute;
    left: 0;
    right: 0;
    margin: auto;
    height: 45px;
    width: 481px;
    box-shadow: 0px 1px 10px rgba(0, 0, 0, 0.15);
    border-radius: 5px;
    display: flex;

    transition: opacity @slide-in-out-animation-time ease;
    opacity: 1;

    &--hidden {
        opacity: 0;
    }

    &--warning {
        background: @yellow;
    }
}

.notification--message {
    line-height: 20px;
    font-size: 14px;
    color: @charcoal;
    opacity: 0.9;
    flex-grow: 1;
    cursor: default;
    text-align: center;
}

.notification--icon {
    flex-grow: 0;
    padding: 0 15px;
}

.notification--center {
    display: flex;
    align-items: center;
}

.notification--icon__close {
    cursor: pointer;
}
</style>
