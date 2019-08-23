import { defaultNestedItems, defaultSimpleItems } from './helpers/sample_data'

function multilineSlot (h) {
    return ({ item }) => {
        return h('div', [
            h('div', item.label),
            h('div', item.metadata),
        ])
    }
}

export default {
    metaName: 'DefaultList',
    variations: {
        theme: ['dark', 'light'],
        size: ['condensed', 'normal', 'phat'],
    },
    usecases: [
        {
            name: 'Items with groups',
            items: defaultNestedItems,
        },
        {
            name: 'Custom slot, initial offset',
            scopedSlots: { default: multilineSlot },
            items: defaultSimpleItems,
            transitionSorting: true,
            initialOffset: 50,
        },
        {
            name: 'Selected item',
            items: defaultNestedItems,
            value: '12',
        },
    ],
}
