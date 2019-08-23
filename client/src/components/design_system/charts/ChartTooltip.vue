<template>
    <div class="chart-tooltip">
        <slot></slot>
    </div>
</template>

<script>
export default {}
</script>

<style scoped lang="less">
@import (reference) '../less/variables';

.chart-tooltip {
    position: absolute;
    bottom: 0;
    min-height: 50px;
    min-width: 80px;
    background-color: white;
    border-radius: 4px;
    margin: 0 auto;
    transform: translateX(-50%);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    white-space: nowrap;
    padding: 0 10px;
    filter: drop-shadow(1px 1px 4px rgba(0, 0, 0, 0.16));

    &:after {
        content: "";
        position: absolute;
        width: 0;
        height: 0;
        border-width: 12px;
        border-style: solid;
        border-color: @white transparent transparent transparent;
        top: 44px;
        left: ~"calc(50% - 10px)";
    }
}
</style>
