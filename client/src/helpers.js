import { getType } from '@/common/util/util.js'

const capitalize = value =>  value[0].toUpperCase() + value.slice(1)

const prefix = (value, prefix) => {
    const prefixValue = input => {
        switch (getType(input)) {
        case 'Array':
            return input.map(prefixValue)
        case 'Object':
            const prefixed = {}
            Object.keys(input).forEach(key => prefixed[`${prefix}${input}`] = input[key])
            return prefixed
        case 'String':
            return `${prefix}${input}`
        }
    }

    return prefixValue(value)
}

const focus = {
    inserted: el => {
        el.focus()
        if (el === document.activeElement) {
            el.focusDone = true
        }
    },
    update: el => {
        if (!el.focusDone) {
            el.focus()
            if (el === document.activeElement) {
                el.focusDone = true
            }
        }
    },
}

const clickOutside = {
    bind: (el, binding, vnode) => {
        el.event = event => el !== event.target && !el.contains(event.target) && vnode.context[binding.expression](event)
        document.addEventListener('mousedown', el.event, true)
    },
    unbind: el => {
        document.removeEventListener('mousedown', el.event, true)
    },
}

export default {
    install (Vue) {
        Vue.filter('capitalize', capitalize)
        Vue.filter('prefix', prefix)
        Vue.directive('focus', focus)
        Vue.directive('click-outside', clickOutside)
    },
}
