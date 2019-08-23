import Icon from './Icon.vue'

export default {
    metaName: 'Input',
    variations: {
        theme: ['dark', 'light'],
        size: ['condensed', 'normal', 'phat'],
        disabled: [false, true],
    },
    usecases: [
        {
            name: 'Inactive',
            label: 'Something',
        },
        {
            testOnly: true,
            name: 'Focused',
            label: 'Something',
            setup (vm) {
                vm.focused = true
                vm.overlay.open = true
                return new Promise(resolve => setTimeout(resolve, 500))
            },
        },
        {
            testOnly: true,
            name: 'Focused with placeholder',
            label: 'Something',
            placeholder: 'Placeholder',
            setup (vm) {
                vm.focused = true
                vm.overlay.open = true
                return new Promise(resolve => setTimeout(resolve, 500))
            },
        },
        {
            testOnly: true,
            name: 'Input',
            label: 'Something',
            setup (vm) {
                vm.text = 'Input text'
                return new Promise(resolve => setTimeout(resolve, 500))
            },
        },
        {
            name: 'Password hidden',
            type: 'password',
            label: 'Something',
            setup (vm) {
                vm.text = 'Hidden text'
            },
        },
        {
            name: 'Right slot',
            label: 'Something',
            scopedSlots: {
                right (h) {
                    return h(Icon, { props: { name: 'clear' } })
                },
            },
        },
        {
            name: 'Left slot',
            label: 'Something',
            scopedSlots: {
                left (h) {
                    return h(Icon, { props: { name: 'pencil-edit' } })
                },
            },
        },
        {
            name: 'Before slot',
            label: 'Something',
            scopedSlots: {
                before (h) {
                    return h(Icon, { props: { name: 'bars' } })
                },
            },
        },
    ],
}
