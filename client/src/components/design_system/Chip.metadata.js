export default {
    metaName: 'Chip',
    width: 120,
    variations: {
        theme: ['dark', 'light'],
        isActive: [false, true],
        isRemovable: [false, true],
        isDisabled: [false, true],
        isMultiline: [false, true],
    },
    usecases: [
        {
            name: 'Basic',
            label: 'Add filter',
        },
        {
            name: 'With metadata',
            label: 'Creatives',
            metadata: '3/9',
        },
        {
            name: 'Multiline',
            label: 'First\nSecond',
        },
    ],
}
