const capitalize = value =>  value[0].toUpperCase() + value.slice(1)

const prefix = (value, prefix) => {
    const prefixValue = (x) => {
        if (Array.isArray(x)) {
            return x.map(prefixValue)
        } else if (typeof x === 'object') {
            const prefixed = {}
            for (const key in x) {
                prefixed[prefix + key] = x[key]
            }
            return prefixed
        } else {
            return prefix + x
        }
    }

    if (value == null) {
        return null
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
