export default {
    metaName: 'ChipWithMultiselect',
    variations: {
        size: ['condensed', 'normal', 'phat'],
    },
    usecases: [
        {
            name: 'Basic',
            options: [
                { id: '1', label: 'This is really long creative name that needs to work well', metadata: '100000' },
                { id: '2', label: '2', metadata: 'zan.kusterle@gmail.com' },
                { id: '3', label: 'zan.kusterle@gmail.com', metadata: '3' },
                { id: '4', label: 'zan.kusterle@gmail.com', metadata: 'zan.kusterle@gmail.com' },
                { id: '5', label: '5', metadata: '5' },
                {
                    id: '6',
                    label: 'This is really long creative name that needs to work well',
                    metadata: '700',
                },
            ],
            value: [ '1', '2' ],
            isSearchable: true,
            canSelectAndClearAll: true,
            canClearAll: true,
            chipLabel: 'Chip Label',
            searchLabel: 'Search Label',
        },
    ],
}
