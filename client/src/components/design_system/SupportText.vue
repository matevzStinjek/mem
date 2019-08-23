<template>
    <div class="support-text">
        <div :class="{right: isRight, left: !isRight, url: isUrl} | prefix('support-text__main--')" class="support-text__main">
            <a v-if="url" :href="url" target="_blank" tabindex="-1" class="support-text__link" @click="click">
                <support-text :theme="theme" :text="text" :is-right="isRight" :is-url="true"></support-text>
            </a>
            <template v-else>
                <div v-if="isRight && showText" class="support-text__hidden support-text__hidden--right">
                    <p ref="content"
                       :style="width ? {'width': `${width}px`} : {}"
                       :class="{'support-text__content--multiline': isMultiline,
                                'support-text__content--fixed-width': width,
                                'support-text__content--light': theme==='light'}"
                       class="support-text__content support-text__content--right" v-html="text"></p>
                </div>

                <div :class="{'support-text__icon--open': showText}" class="support-text__icon" data-testilda-id="support-icon">
                    <icon ref="icon" name="support"/>
                </div>

                <div v-if="!isRight && showText" class="support-text__hidden support-text__hidden--left">
                    <p ref="content" :style="width ? {'width': `${width}px`} : {}"
                       :class="{'support-text__content--multiline': isMultiline,
                                'support-text__content--fixed-width': width,
                                'support-text__content--light': theme==='light'}"
                       class="support-text__content support-text__content--left"
                       v-html="text"></p>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import Icon from './Icon.vue'

export default {
    name: 'support-text',
    components: {
        SupportText: this,
        Icon: Icon,
    },
    props: {
        text: { type: String, required: true },
        url: { type: String, required: false },
        isRight: { type: Boolean, default: false },
        theme: { type: String, default: 'dark' },
        width: { type: Number, required: false },
        trackName: { type: String, required: false },
        isUrl: { type: Boolean, default: false },
    },
    data () {
        return {
            isMultiline: false,
            showText: false, // Don't have "hidden" element in DOM all the time, absolute positoning prevents clicking under element
        }
    },
    created () {
        this.timeoutId = null
    },
    mounted () {
        if (!this.url) {
            this.$refs.icon.$el.addEventListener('mouseenter', this.open)
        }
    },
    methods: {
        /*
        Using setTimeout is simpler than handling all sorts of transitionend events,
        also vertical animations don't have to start exactly when horizontal one is over
        */
        open (ev) {
            this.showText = true
            this.$nextTick(() => {
                const expandedHeight = this.$refs.content.scrollHeight

                this.isMultiline = expandedHeight > this.$refs.content.clientHeight + 1

                this.$refs.content.addEventListener('mouseleave', this.close)
                this.$refs.content.style.transform = 'translateX(0px)'

                if (this.isMultiline) {
                    this.timeoutId = setTimeout(() => {
                        this.$refs.content.style.height = `${expandedHeight - 2}px` // 2px of padding
                    }, 100)
                }
            })
        },
        close (ev) {
            clearTimeout(this.timeoutId)
            this.$refs.content.style.height = `${this.isMultiline ? 8 : 12}px`
            setTimeout(() => {
                this.$refs.content.style.transform = `translateX(${this.isRight ? 225 : -225}px)`
            }, this.isMultiline ? 80: 150)
            setTimeout(() => this.showText = false, 300)
        },
        click () {
            this.$root.$emit('tracking-event', { type: 'link', label: this.trackName || 'supportText', trigger: 'click', data: { url: this.url } })
        },
    },
}
</script>

<style lang="less" scoped>
@import (reference) './less/common';
@import './less/typography';

.support-text {
    .regular-font();
    position: relative;
    height: 14px;

    &__main {
        font-size: 0;
        position: relative;
        cursor: default;

        &--right {
            position: absolute;
            right: 0;
            top: 0;
        }

        &--url {
            cursor: pointer;
        }
    }

    &__link {
        outline: none;
    }

    &__hidden {
        overflow: hidden;
        display: inline-block;
        vertical-align: middle;
        position: absolute;
        border-radius: 7px;
        padding: 0 0 4px 2px; // For shadow
        z-index: @z-middle;

        &--left {
            left: -2px;
        }

        &--right {
            right: 0;
        }
    }

    &__content {
        margin: 0;
        height: 12px;
        color: white;
        background-color: @charcoal;
        font-size: 11px;
        padding: 1px 0;
        border-radius: 7px;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.5);
        line-height: 14px;
        letter-spacing: 0.5px;
        white-space: pre;
        transition: transform @default-transition-time ease-out;

        &--fixed-width {
            white-space: normal;
        }

        &--light {
            background-color: #f7f7f7;
            color: #7a7982;
        }

        &--multiline {
            padding: 3px 0;
            transition: transform 100ms ease-out, height 80ms ease-out;
        }

        &--left {
            padding-left: 20px;
            padding-right: 10px;
            transform: translateX(-105%);
        }

        &--right {
            padding-left: 10px;
            padding-right: 20px;
            transform: translateX(105%);
        }
    }

    &__icon {
        width: 14px;
        height: 14px;
        display: inline-block;
        vertical-align: middle;
        position: relative;
        z-index: @z-middle - 1; // if there's open support text it should cover other support text icons

        &--open {
            z-index: @z-high;
            pointer-events: none;
        }

        .icon-wrapper {
            width: 14px;
            height: 14px;
        }
    }
}
</style>

<style lang="less">
.support-text__content {
    b {
        font-weight: bold;
    }
}
</style>
