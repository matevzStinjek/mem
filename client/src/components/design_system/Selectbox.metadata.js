import { defaultItems } from './helpers/sample_data'

export default {
    metaName: 'Selectbox',
    variations: {
        theme: ['dark', 'light'],
        size: ['condensed', 'normal', 'phat'],
        disabled: [false, true],
    },
    usecases: [
        {
            name: 'Basic',
            options: defaultItems,
            isSearchable: true,
            isUnselectable: true,
            label: 'Something',
        },
        {
            testOnly: true,
            name: 'Open',
            options: defaultItems,
            isSearchable: true,
            isUnselectable: true,
            label: 'Something',
            setup (vm) {
                vm.openSelectList()
            },
            disabled: false,
        },
    ],
}
