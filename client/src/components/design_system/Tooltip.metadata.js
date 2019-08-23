function setup () {
    // Wait for tooltip animation to finish
    return new Promise(resolve => setTimeout(resolve, 1200))
}

function defaultSlot (h) {
    return 'Something'
}

export default {
    metaName: 'Tooltip',
    hasAbsolutePosition: true,
    variations: {
        theme: ['dark', 'light'],
    },
    usecases: [
        {
            name: 'Basic',
            setup: setup,
            scopedSlots: { default: defaultSlot },
            title: 'Some',
            show: true,
        },
    ],
}
