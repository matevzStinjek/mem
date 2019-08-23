<template>
    <div :class="[theme, size, cssModifiers] | prefix('default-list-item--')" class="default-list-item" :data-disabled="disabled ? 'true' : 'false'" >
        <p :style="labelWidth ? { width: `${labelWidth}px` } : {}" :class="cssModifiers | prefix('default-list-item__label--')" class="default-list-item__label">
            <avatar class="default-list-item__avatar"
                    v-if="avatarInitials || avatarIcon || avatarImage"
                    :initials="avatarInitials"
                    :icon="avatarIcon"
                    :image="avatarImage">
            </avatar>

            <template v-if="highlightQuery">
                <span v-for="(part, index) in getParts(label)" :key="index" :style="part.bold ? { fontWeight: 'bold' } : {}" class="default-list-item__part">{{ part.text }}</span>
            </template>
            <template v-else>
                {{ label }}
            </template>
        </p>
        <p v-if="metadata" :style="metadataWidth ? { width: `${metadataWidth}px` } : {}" :class="cssModifiers | prefix('default-list-item__metadata--')" class="default-list-item__metadata">
            <template v-if="highlightQuery">
                <span v-for="(part, index) in getParts(metadata)" :key="index" :style="part.bold ? { fontWeight: 'bold' } : {}" class="default-list-item__part">{{ part.text }}</span>
            </template>
            <template v-else>
                {{ metadata | middleEllipsis(metadataLength) }}
            </template>
            <icon v-if="icon" :name="icon" class="default-list-item__icon" />
        </p>

        <template v-if="metadata">
            <p ref="labelContainer" class="default-list-item__hidden-width default-list-item__label">{{ label }}</p>
            <p ref="metadataContainer" class="default-list-item__hidden-width default-list-item__metadata">
                {{ metadata }}
                <icon v-if="icon" :name="icon" class="default-list-item__icon" />
            </p>
        </template>
    </div>
</template>

<script>
import { getTextHighlightParts } from './helpers/utils'
import Icon from './Icon.vue'
import Avatar from './Avatar.vue'

export default {
    components: {
        Icon,
        Avatar,
    },
    props: {
        size: { type: String, required: false, default: 'normal' },
        theme: { type: String, required: false, default: 'dark' },
        label: { type: String, required: true },
        avatarInitials: { type: String, default: null },
        avatarIcon: { type: String, default: null },
        avatarImage: { type: String, default: null },
        metadata: { type: String },
        icon: { type: String },
        selected: { type: Boolean },
        disabled: { type: Boolean },
        highlightQuery: { type: String },
    },
    data () {
        return {
            labelWidth: null,
            metadataWidth: null,
        }
    },
    computed: {
        metadataLength () {
            if (!this.metadataWidth) {
                return 100
            }
            return Math.floor(this.metadataWidth / 7)
        },
        cssModifiers () {
            return {
                selected: this.selected,
                disabled: this.disabled,
                'with-metadata': !!this.metadata,
                'with-avatar': !!this.avatarInitials,
            }
        },
    },
    mounted () {
        window.addEventListener('resize', this.calculateWidths)
        this.$nextTick(this.calculateWidths)
    },
    beforeDestroy () {
        window.removeEventListener('resize', this.calculateWidths)
    },
    methods: {
        calculateWidths () {
            if (this.metadata) {
                const THRESHOLD = 0.1
                const totalWidth = this.$el.clientWidth - 5
                const labelWidth = this.$refs.labelContainer.clientWidth
                const metadataWidth = this.$refs.metadataContainer.clientWidth

                if (labelWidth + metadataWidth > totalWidth) {
                    let finalLabelWidth
                    if (labelWidth > metadataWidth) {
                        if (labelWidth <= (0.5 + THRESHOLD) * totalWidth) {
                            finalLabelWidth = labelWidth
                        } else {
                            finalLabelWidth = Math.floor(0.5 * totalWidth)
                        }
                    } else {
                        if (metadataWidth <= (0.5 + THRESHOLD) * totalWidth) {
                            finalLabelWidth = totalWidth - metadataWidth
                        } else {
                            finalLabelWidth = Math.floor(0.5 * totalWidth)
                        }
                    }

                    this.labelWidth = finalLabelWidth
                    this.metadataWidth = totalWidth - finalLabelWidth
                }
            }
        },
        getParts (label) {
            return getTextHighlightParts(label, this.highlightQuery)
        },
    },
}
</script>

<style lang="less">
@import (reference) './less/common';
@import './less/typography';

.default-list-item {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    .regular-font();

    &--disabled {
        cursor: auto;

        .default-list-item__icon {
            color: @ink;
        }
    }

    &__label {
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        .regular-font();
        color: @cement;
        display: inline-block;
        transition: color @default-transition-time ease-out;

        &--error { color: @red; }
        &--with-metadata { padding-right: 5px; }
    }

    &__avatar {
        color: @purple;
        margin-right: 10px;
    }

    &__metadata {
        display: flex;
        align-items: center;
        margin: 0;
        white-space: nowrap;
        justify-content: flex-end;
        overflow: hidden;
        .regular-font();
        color: @smog;
    }

    &__icon {
        margin-left: 10px;
        color: @charcoal;
    }

    &__hidden-width {
        visibility: hidden;
        position: absolute;
    }

    &__part {
        white-space: pre-wrap;
    }
}

.default-list-item--dark {
    .default-list-item {
        &__label {
            color: @cement;
            &--selected { color: @void; }
            &--disabled { color: @charcoal; }

            &:hover {
                &:not(.default-list-item--disabled)&:not(.default-list-item--selected) {
                    .default-list-item__label { color: @white; }
                }
            }
        }
    }
}

.default-list-item--light {
    .default-list-item {
        &__label {
            color: @charcoal;
            &--selected { color: @blue; }
            &--disabled { color: @dust; }

            &:hover {
                &:not(.default-list-item--disabled)&:not(.default-list-item--selected) {
                    .default-list-item__label { color: @void; }
                }
            }
        }
    }
}

.default-list-item--condensed {
    height: 20px;

    .default-list-item {
        &__label {
            font-size: 14px;
        }

        &__metadata {
            font-size: 11px;
            letter-spacing: 0.5px;
        }
    }
}

.default-list-item--normal {
    height: 30px;

    .default-list-item {
        &__label {
            font-size: 18px;
        }

        &__metadata {
            font-size: 12px;
        }
    }
}

.default-list-item--phat {
    height: 45px;

    &.default-list-item--with-avatar {
        height: 60px;
    }

    .default-list-item {
        &__label {
            font-size: 22px;
        }

        &__metadata {
            font-size: 14px;
        }
    }
}
</style>
