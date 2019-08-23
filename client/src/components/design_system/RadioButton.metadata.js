function defaultSlot (h) {
    return 'Something'
}

export default {
    metaName: 'RadioButton',
    variations: {
        theme: ['dark', 'light'],
        size: ['condensed', 'normal', 'phat'],
        disabled: [false, true],
        selectedValue: ['none', 'something'],
    },
    forceValueSync: true,
    usecases: [
        {
            name: 'Value A',
            scopedSlots: { default: () => 'Something' },
            value: 'something',
        },
        {
            name: 'Value B',
            scopedSlots: { default: () => 'Something else' },
            value: 'something else',
        },
    ],
}
