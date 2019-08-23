export default {
    install (Vue) {

        Vue.filter('capitalize', function (value) {
            return value[0].toUpperCase() + value.slice(1)
        })

        Vue.filter('prefix', function (value, prefix) {
            const prefixValue = (x) => {
                if (Array.isArray(x)) {
                    return x.map(prefixValue)
                } else if (typeof (x) === 'object') {
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
        })

        Vue.filter('middleEllipsis', function (value, totalCharacters) {
            if (totalCharacters && value && value.length > totalCharacters) {
                return value.substring(0, totalCharacters - 11) + '...' + value.substring(value.length - 10, value.length)
            }
            return value
        })

        Vue.filter('slugify', function (value) {
            return value ? value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '') : ''
        })

        Vue.filter('humanize', function (value, precision, method) {
            if (precision == null) {
                precision = 2
            }
            if (method == null) {
                method = 'toFixed'
            }
            if (value < 1e3) {
                value = Number((value)[method](precision))

            } else if (value < 1e6) {
                value = Number((value / 1e3)[method](precision)) + 'K'

            } else if (value < 1e9) {
                value = Number((value / 1e6)[method](precision)) + 'M'

            } else if (value < 1e12) {
                value = Number((value / 1e9)[method](precision)) + 'B'

            } else {
                value = Number((value / 1e12)[method](precision)) + 'T'
            }

            return value
        })

        Vue.filter('truncate', function (value, len, numOfVisibleEndingCharacters, min) {
            if (value.length > min) {
                return value.substr(0, len - numOfVisibleEndingCharacters) + " &hellip; " + string.substr(-numOfVisibleEndingCharacters)
            } else {
                return value
            }
        })

        Vue.filter('pluralize', function (count, word) {
            return count === 1 ? word : word + 's'
        })

        Vue.directive('focus', {
            inserted (el) {
                el.focus()
                if (el === document.activeElement) {
                    el.focusDone = true
                }
            },
            update (el) {
                if (!el.focusDone) {
                    el.focus()
                    if (el === document.activeElement) {
                        el.focusDone = true
                    }
                }
            },
        })

        Vue.directive('click-outside', {
            bind (el, binding, vnode) {
                el.event = (event) => {
                    if (!(el === event.target || el.contains(event.target))) {
                        vnode.context[binding.expression](event)
                    }
                }
                document.addEventListener('mousedown', el.event, true)
            },
            unbind (el) {
                document.removeEventListener('mousedown', el.event, true)
            },
        })
    },
}
