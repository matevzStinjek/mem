<template>
    <div class="prompt__overlay">
        <transition name="prompt-" appear @after-leave="$root.$emit('close')">
            <div class="prompt" v-if="isDisplayed" ref="prompt" tabindex="-1" @keyup.esc="$root.$emit('cancel')">
                <icon name="error-triangle" class="prompt__triangle"></icon>
                <div class="prompt__title">Are you sure?</div>
                <div class="prompt__subtitle">{{ message }}</div>
                <div class="prompt__buttons">
                    <dialog-button :warning="true" size="condensed" class="prompt__button" @click="$root.$emit('cancel')">Cancel</dialog-button>
                    <dialog-button :error="true" size="condensed" class="prompt__button" @click="$root.$emit('confirm')">Delete</dialog-button>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import Icon from './Icon.vue'
import DialogButton from './DialogButton.vue'

export default {
    name: 'prompt',
    components: {
        DialogButton,
        Icon,
    },
    props: {
        message: { type: String, required: true },
    },
    data () {
        return {
            isDisplayed: true,
        }
    },
    mounted () {
        this.$refs.prompt.focus()
    },
    methods: {
        close () {
            this.isDisplayed = false
        },
    },
}
</script>

<style scoped lang="less">
@import (reference) './less/common';
@import (reference) './less/typography';

.prompt__overlay {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

.prompt {
    position: fixed;
    z-index: 10;
    top: 0;
    left: 0;
    right: 0;
    margin: 0 auto;
    width: 600px;
    height: 220px;
    box-sizing: border-box;
    padding: 0 25px;
    text-align: center;
    background-color: white;
    box-shadow: 0 5px 20px rgba(0, 0, 0, .10);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    transition: all 200ms ease-out;

    &:focus {
        outline: none;
    }

    &--enter-active, &--leave-active {
        opacity: 0;
        transform: translateY(-30px);
    }

    &--enter-to {
        opacity: 1;
        transform: translateY(0);
    }

    &__triangle {
        color: @red;
        height: 32px;
        width: 32px;
    }

    &__title {
        color: black;
        .regular-font();
        font-size: 22px;
        margin-bottom: 15px;
    }

    &__subtitle {
        margin-bottom: 20px;
        .regular-font();
        color: @smog;
        font-size: 14px;
    }

    &__buttons {
        display: flex;
    }

    &__button.prompt__button {
        margin: 0 10px;
    }
}
</style>
