<template>
    <div id="app">
        <router-view/>
    </div>
</template>

<style lang="less">
@import '~@/common/less/common';

body {
    margin: 0;
}
#app {
    font-family: system-ui, 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    height: 100vh;
    background-color: @main;
    color: @white;
}
</style>
