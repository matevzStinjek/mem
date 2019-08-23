function defaultSlot (h) {
    return h('div', 'Something')
}

export default {
    metaName: 'Group',
    variations: {
        theme: ['dark', 'light'],
    },
    usecases: [
        {
            name: 'Label only',
            scopedSlots: { default: defaultSlot },
            label: 'Modeling',
        },
        {
            name: 'Full text',
            scopedSlots: { default: defaultSlot },
            label: 'Modeling',
            description: 'Loooooooong descriptiooooooooon',
        },
    ],
}
