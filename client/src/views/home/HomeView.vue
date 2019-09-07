<template>
    <div class="home">
        <navbar/>
        <div class="column__container">
            <div class="column column--sidebar">
                <sidebar/>
            </div>
            <div class="column column--content">
                <router-view/>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from '@/components/Navbar'
import Sidebar from '@/components/Sidebar'
import apiClient from 'api-client'

export default {
    components: {
        Navbar,
        Sidebar,
    },
    created () {
        apiClient.get('users?roles=admin')
    }
}
</script>

<style lang="less" scoped>
@import (reference) '~@/common/less/common';

.home {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.column {
    flex: 1 1 0;

    &__container {
        flex: 1 1 0;
        display: flex;
        height: 100%;
    }
    &--sidebar {
        max-width: 256px;
    }
    &--content {
        overflow-y: scroll;
        height: 100%;
    }
}
</style>
