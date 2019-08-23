<template>
    <div class="file-upload-requirements" :class="{'file-upload-requirements--drag-active': dragActive }">
        <p v-for="requirement in requirements" :key="requirement.name" class="file-upload-requirements__requirement">
            {{ requirement.name }}:
            <span class="file-upload-requirements__requirement-value">{{ requirement.value }}</span>
        </p>
    </div>
</template>

<script>
export default {
    props: {
        requirements: { type: Array, required: true },
        dragActive: { type: Boolean, default: false },
    },
}
</script>

<style scoped lang="less">
@import (reference) './less/common';
@import './less/typography';

.file-upload-requirements {
    color: @ash;
    font-size: 11px;
    margin-top: 15px;
    line-height: 15px;
    letter-spacing: 0.5px;
    position: absolute;
    .regular-font();

    .file-upload-requirements__requirement-value {
        .medium-font();
        font-weight: bold;
    }

    &--drag-active {
        pointer-events: none;
        z-index: @z-heaven;

        .file-upload-requirements__requirement {
            color: @cement;
        }
    }
}
</style>
