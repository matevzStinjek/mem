import { largeItems, defaultSimpleItems } from './helpers/sample_data'

export default {
    metaName: 'ScrollableList',
    variations: {
        theme: ['dark', 'light'],
        size: ['condensed', 'normal', 'phat'],
    },
    usecases: [
        {
            name: 'Overlay',
            items: defaultSimpleItems,
            numItems: 4,
            showOverlay: true,
        },
        {
            name: 'Scroll up',
            items: defaultSimpleItems,
            numItems: 4,
            enableScrollTop: true,
        },
        {
            name: 'Large items',
            items: largeItems,
            numItems: 10,
        },
    ],
}
