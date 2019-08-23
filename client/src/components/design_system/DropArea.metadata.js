export default {
    metaName: 'DropArea',
    hasAbsolutePosition: true,
    variations: {
        theme: ['dark', 'light'],
        size: ['condensed', 'normal', 'phat'],
    },
    usecases: [
        {
            name: 'Basic',
            dragActive: true,
        },
    ],
    data () {
        return {
            width: null,
            height: null,
        }
    },
}
