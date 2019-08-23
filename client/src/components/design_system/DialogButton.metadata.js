function defaultSlot (h) {
    return h('div', 'Something')
}

export default {
    metaName: 'DialogButton',
    width: 300,
    variations: {
        disabled: [false, true],
        // loading: [false, true], We would have to disable animation to test this
    },
    usecases: [
        {
            name: 'Success',
            scopedSlots: { default: defaultSlot },
        },
        {
            name: 'Warning',
            scopedSlots: { default: defaultSlot },
            warning: true,
        },
        {
            name: 'Error',
            scopedSlots: { default: defaultSlot },
            error: true,
        },
    ],
}
