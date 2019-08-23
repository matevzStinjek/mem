export default {
    metaName: 'DateInput',
    variations: {
        theme: ['dark', 'light'],
        size: ['condensed', 'normal', 'phat'],
    },
    usecases: [
        {
            name: 'Basic',
            label: 'Enter date',
            value: new Date(2018, 0, 10),
        },
    ],
}
