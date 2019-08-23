import { defaultItems, defaultSimpleItems } from './helpers/sample_data'

export default {
    metaName: 'Multiselect',
    variations: {
        theme: ['dark', 'light'],
        size: ['condensed', 'normal', 'phat'],
    },
    usecases: [
        {
            name: 'Basic',
            options: defaultSimpleItems,
            value: ['1', '2'],
        },
        {
            name: 'Can clear all',
            canClearAll: true,
            options: defaultSimpleItems,
            value: [],
        },
        {
            name: 'Can clear and select all',
            canSelectAndClearAll: true,
            options: defaultSimpleItems,
            value: [],
        },
        {
            name: 'Searchable',
            isSearchable: true,
            options: defaultItems,
            value: [],
        },
    ],
}
