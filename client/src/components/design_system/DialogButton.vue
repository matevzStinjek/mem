<template>
    <div class="dialog-button" :class="{ 'inline': inline } | prefix('dialog-button--')" :data-testilda-disabled="disabled ? 'true' : 'false'" :data-loading="loading ? 'true' : 'false'" >
        <a v-if="href" :href="href" :target="target" class="dialog-button__link" @mousedown.prevent.stop @click="click">
            <dialog-button :disabled="disabled" :loading="loading" :error="error" style="width: inherit;" :track-name="trackName" :track-type="trackType">
                <slot></slot>
            </dialog-button>
        </a>
        <div v-else :class="[size, { 'disabled': disabled, 'loading': loading, 'error': error, 'warning': warning }] | prefix('dialog-button__container--')" :tabindex="disabled ? -1 : 0" class="dialog-button__container" ref="buttonContainer" @mousedown.prevent.stop @click="click" @keyup.enter.prevent.stop="click">
            <svg v-if="loading" viewBox="0 0 30 30">
                <path d="M15,30C6.7,30,0,23.3,0,15c0-4.7,2.3-9.2,6-12l2.4,3.2C5.6,8.2,4,11.5,4,15C4,21,8.9,26,15,26c6.1,0,11-4.9,11-11c0-5.7-4.4-10.5-10-11l0.3-4C24,0.7,30,7.2,30,15C30,23.3,23.3,30,15,30z"/>
            </svg>
            <div :style="loading ? { visibility: 'hidden' } : {}" class="dialog-button__text">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
import DialogButton from './DialogButton.vue'

export default {
    name: 'dialog-button',
    components: { DialogButton: DialogButton },
    props: {
        disabled: { type: Boolean, default: false },
        loading: { type: Boolean, default: false },
        error: { type: Boolean, default: false },
        warning: { type: Boolean, default: false },
        inline: { type: Boolean, default: false },
        size: { type: String, default: 'normal' },
        href: { type: String },
        target: { type: String, default: '_self' },
        trackName: { type: String, required: false },
        trackType: { type: String, default: 'button' },
    },
    methods: {
        click (ev) {
            if (!this.disabled) {
                this.$root.$emit('tracking-event', { type: this.trackType, label: this.trackName || ((this.$slots && this.$slots.default && this.$slots.default[0].text) ? this.$slots.default[0].text : 'dialog-button'), trigger: 'click' })
                this.$emit('click', ev)
            }
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.dialog-button {
    width: fit-content;
    margin: 0 auto;
    color: @void;

    &--inline {
        display: inline-block;
        margin: 0 20px 0 0;
    }
}

.dialog-button__link,
.dialog-button__container {
    width: 100%;
}

.dialog-button__container {
    position: relative;
    padding: 22px 75px;
    background-color: @green;
    font-size: 14px;
    .medium-font();
    line-height: 16px;
    text-transform: uppercase;
    cursor: pointer;
    user-select: none;
    text-align: center;
    box-sizing: border-box;

    &:hover {
        background-color: @green-hover-dark;
    }

    &:focus {
        outline: none;
    }

    &.dialog-button__container--loading {
        background-color: @green-disabled;
    }

    &.dialog-button__container--disabled {
        background-color: @green-disabled;
        cursor: no-drop;
    }

    &.dialog-button__container--error {
        color: white;
        background-color: @red;

        &:hover {
            background-color: @red-hover-dark;
        }
    }

    &.dialog-button__container--warning {
        color: white;
        background-color: @charcoal;

        &:hover {
            background-color: @charcoal-hover-light;
        }

        &.dialog-button__container--loading {
            background-color: darken(@charcoal, 10%);
        }
    }

    &.dialog-button__container--condensed {
        padding: 14px 45px;
    }
}

.dialog-button__link {
    color: black;
    text-decoration: none;
}

.dialog-button__container--loading {
    cursor: default;
    min-height: 16px;
    min-width: 36px;
    pointer-events: none;

    &:focus { outline: none; }

    svg {
        position: absolute;
        top: 20px;
        width: 20px;
        height: 20px;
        left: 0;
        right: 0;
        margin: auto;
        fill: @white;
        animation: BUTTON_LOADER 1s ease-out infinite;
    }
}

@keyframes BUTTON_LOADER {
    0% { transform: rotate(0); }
    100% { transform: rotate(720deg); }
}
</style>
