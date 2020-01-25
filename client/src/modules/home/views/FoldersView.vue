<template>
    <div class="folders__container">
        <div class="folders">
            <folder-thumbnail/>
        </div>
    </div>
</template>

<script>
import FolderThumbnail from '@/components/FolderThumbnail'

export default {
    name: 'FoldersView',
    components: {
        FolderThumbnail,
    },
}
</script>

<style lang="less" scoped>
@import (reference) '~@/common/less/common';

.folders {
    margin: -24px auto;
    max-width: 1024px;

    display: flex;
    flex-wrap: wrap;
    align-content: flex-start;
    justify-content: center;

    &__container {
        position: relative;
        padding: 64px 32px 32px;
    }
}
</style>
