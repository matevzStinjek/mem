<template>
    <div class="folder-thumbnail" @click="$router.push(`events/${id}`)">
        <div class="folder-thumbnail__icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="256" height="256"><linearGradient id="a" x1="32.366" x2="31.7" y1="5.786" y2="58.953" gradientUnits="userSpaceOnUse" spreadMethod="reflect"><stop offset="0" stop-color="#1a6dff"/><stop offset="1" stop-color="#c822ff"/></linearGradient><path fill="url(#a)" d="M50 12h-4v-1c0-2.757-2.243-5-5-5s-5 2.243-5 5v1h-8v-1c0-2.757-2.243-5-5-5s-5 2.243-5 5v1h-4c-2.757 0-5 2.243-5 5v36c0 2.757 2.243 5 5 5h36c2.757 0 5-2.243 5-5V17c0-2.757-2.243-5-5-5zm-12-1c0-1.654 1.346-3 3-3s3 1.346 3 3v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-4zm-18 0c0-1.654 1.346-3 3-3s3 1.346 3 3v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-4zm-6 3h4v1c0 1.654 1.346 3 3 3h4c1.654 0 3-1.346 3-3v-1h8v1c0 1.654 1.346 3 3 3h4c1.654 0 3-1.346 3-3v-1h4c1.654 0 3 1.346 3 3v5H11v-5c0-1.654 1.346-3 3-3zm1 14.414l1.376 1.376a3.006 3.006 0 0 0 4.248 0l2.377-2.375 2.375 2.375c1.135 1.135 3.113 1.135 4.248 0l2.377-2.375 2.375 2.375c.567.567 1.322.881 2.124.881s1.557-.313 2.124-.881l2.377-2.375 2.375 2.375a3.006 3.006 0 0 0 4.248 0L49 28.414V48H15V28.414zm34-2.828l-2.79 2.79a1.029 1.029 0 0 1-1.42 0l-2.375-2.375a2.002 2.002 0 0 0-2.83 0l-2.375 2.375a1.006 1.006 0 0 1-1.42 0l-2.375-2.375a2.002 2.002 0 0 0-2.83 0l-2.375 2.375a1.006 1.006 0 0 1-1.42 0l-2.375-2.375a2.002 2.002 0 0 0-2.83 0l-2.375 2.375a1.029 1.029 0 0 1-1.42 0L15 25.586V24h34v1.586zM50 56H14c-1.654 0-3-1.346-3-3v-.026A4.948 4.948 0 0 0 14 54h36c1.13 0 2.162-.391 3-1.026V53c0 1.654-1.346 3-3 3zm0-4H14c-1.654 0-3-1.346-3-3V24h2v24c0 1.103.897 2 2 2h34c1.103 0 2-.897 2-2V24h2v25c0 1.654-1.346 3-3 3z"/><linearGradient id="b" x1="43.35" x2="43.35" y1="37.833" y2="47.346" gradientUnits="userSpaceOnUse" spreadMethod="reflect"><stop offset="0" stop-color="#6dc7ff"/><stop offset="1" stop-color="#e6abff"/></linearGradient><path fill="url(#b)" d="M47 39.197V45a1 1 0 0 1-1 1h-5.803a.496.496 0 0 1-.351-.846l6.307-6.307a.496.496 0 0 1 .847.35z"/></svg>
        </div>
        <div class="folder-thumbnail__name">{{ name }}</div>
    </div>
</template>

<script>
export default {
    props: {
        id: { type: String, default: 'asd' },
        name: { type: String, default: 'epik event' },
    },
}
</script>

<style lang="less" scoped>
@import (reference) '~@/common/less/common';

.folder-thumbnail {
    width: 256px;
    border-radius: 3px;
    padding: 24px;
    transition: background-color 0.08s;
    cursor: pointer;

    &__name {
        font-size: 24px;
        margin: -8px 35px 8px 35px;
    }

    &:hover {
        background-color: #333;
    }
}
</style>
