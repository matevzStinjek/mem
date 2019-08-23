import Vue from 'vue'
import Tooltip from '../Tooltip.vue'

const tooltipVm = new Vue({
    data () {
        return {
            theme: 'dark',
            target: null,
            text: null,
            title: null,
        }
    },
    mounted () {
        const tooltipElement = this.$children[0].$el
        tooltipElement.style.top = tooltipElement.style.left = '0'
        this.intervalId = setInterval(this.updatePosition, 100)
    },
    beforeDestroy () {
        clearInterval(this.intervalId)
    },
    methods: {
        updatePosition () {
            if (this.target && !document.body.contains(this.target)) {
                this.target = null
            }

            if (this.target) {
                const tooltipElement = this.$children[0].$el
                const targetRect = this.target.getBoundingClientRect()

                tooltipElement.style.transform = `translate(${Math.round(targetRect.left + window.scrollX)}px, ${Math.round(targetRect.bottom + window.scrollY)}px)`
            }
        },
    },
    render (h) {
        const props = {
            theme: this.theme,
            isRelative: false,
            title: this.title,
            show: !!this.target,
        }
        return h(Tooltip, { props }, [this._v(this.text || '')])
    },
})

export default {
    beforeCreate () {
        if (!tooltipVm.$el) {
            const body = document.getElementsByTagName('body')[0]
            const container = document.createElement('div')
            body.appendChild(container)
            tooltipVm.$mount(container)
        }
    },
    methods: {
        showTooltip (element, text, title = null) {
            if (this.theme) {
                tooltipVm.theme = this.theme
            }
            tooltipVm.canShowTooltip = true
            this.$nextTick(() => {
                if (tooltipVm.canShowTooltip) {
                    tooltipVm.text = text
                    tooltipVm.title = title
                    tooltipVm.target = element
                    tooltipVm.updatePosition()
                }
            })
        },
        hideTooltip () {
            tooltipVm.target = null
            tooltipVm.canShowTooltip = false
        },
    },
}
