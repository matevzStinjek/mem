export default {
    metaName: 'Slider',
    variations: {
        theme: ['dark', 'light'],
        size: ['condensed', 'normal', 'phat'],
        disabled: [false, true],
        alignment: ['left', 'right'],
    },
    usecases: [
        {
            name: 'Basic',
            min: 1,
            max: 100,
            limit: 20,
            step: 1,
            label: 'Basic slider',
            value: 5,
            unit: '%',
        },
    ],
}
