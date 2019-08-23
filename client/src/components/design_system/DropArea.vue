<template>
    <div v-if="dragActive" :class="['drop-area__overlay--' + theme, 'drop-area__overlay--' + size]" class="drop-area__overlay" @dragleave.stop="dragEnd" @drop.prevent="drop">
        <svg v-if="size != 'small'" :viewBox="`0 0 ${width} ${height}`" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path :d="outlinePath" vector-effect="non-scaling-stroke"></path>
        </svg>

        <div v-if="withText" class="drop-area__info">
            <icon name="upload" class="drop-area__icon" />
            <p class="drop-area__info-text">Drop the files here to upload.</p>
        </div>
    </div>
</template>

<script>
import Icon from './Icon.vue'

export default {
    components: {
        Icon,
    },
    model: {
        prop: 'dragActive',
        event: 'drag-change',
    },
    props: {
        dragActive: { type: Boolean, default: false },
        theme: { type: String, default: 'dark' },
        size: { type: String, default: 'full' },
        withText: { type: Boolean, default: false },
    },
    data () {
        return {
            width: null,
            height: null,
        }
    },
    computed: {
        outlinePath () {
            return `
                M 14 6 
                L ${this.width - 14} 6
                a 8,8 0 0 1 8,8
                L ${this.width - 6} ${this.height - 14}
                a 8,8 0 0 1 -8,8
                L 14 ${this.height - 6}
                a 8,8 0 0 1 -8,-8
                L 6 14
                a 8,8 0 0 1 8,-8 Z
            `
        },
    },
    mounted () {
        this.draggable = this.$el.parentNode

        this.draggable.addEventListener('dragover', this.onDragOver)
    },
    beforeDestroy () {
        this.draggable.removeEventListener('dragover', this.onDragOver)
    },
    methods: {
        onDragOver (e) {
            e.preventDefault()
            e.stopPropagation()

            this.dragStart()
        },
        dragStart () {
            if (!this.dragActive) {
                this.width = this.draggable.clientWidth
                this.height = this.draggable.clientHeight

                this.$emit('drag-change', true)
            }
        },
        dragEnd () {
            if (this.dragActive) {
                this.$emit('drag-change', false)
            }
        },
        drop (ev) {
            this.dragEnd()

            if (ev.dataTransfer.files && ev.dataTransfer.files.length > 0) {
                this.$root.$emit('tracking-event', { type: 'input', label: 'dropFiles', trigger: 'drop' })
                this.$emit('drop', ev.dataTransfer.files)
            }
        },
    },
}
</script>

<style scoped lang="less">
@import (reference) './less/common';
@import './less/typography';

.drop-area__overlay {
    position: absolute;
    background-color: fade(@blue, 60);
    z-index: @z-highest;
    width: 100%;
    height: ~"calc(100% - 30px)";

    svg {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
    }

    path {
        fill: none;
        stroke: fade(white, 20);
        stroke-width: 2;
        stroke-dasharray: 10, 10;
    }
}

.drop-area__overlay--small {
    top: -2px;
    left: -2px;
    height: ~"calc(100% + 4px)";
    width: ~"calc(100% + 4px)";
}

.drop-area__overlay--light {
    background-color: fade(#002db8, 80);

    path {
        stroke: fade(black, 20);
    }
}

.drop-area__info {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    flex-direction: column;
}

.drop-area__info-text {
    color: white;
    margin-top: 30px;
    font-size: 18px;
}

.drop-area__icon.drop-area__icon {
    width: 90px;
    height: 90px;
    box-sizing: border-box;
    color: white;
    background-color: rgba(0, 0, 0, 0.1);
    padding: 28px;
}
</style>
