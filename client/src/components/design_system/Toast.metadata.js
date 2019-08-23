function setup (vm) {
    vm.show()
    return new Promise(resolve => setTimeout(resolve, 1000))
}

function slot (h) {
    return h('div', 'Label')
}

function actionSlot (h) {
    return h('div', 'Action')
}

export default {
    metaName: 'Toast',
    hasAbsolutePosition: true,
    variations: {
        theme: ['dark', 'light'],
    },
    usecases: [
        {
            name: 'Basic',
            setup: setup,
            scopedSlots: {
                default: slot,
            },
        },
        {
            name: 'With action',
            setup: setup,
            scopedSlots: {
                default: slot,
                action: actionSlot,
            },
        },
    ],
}
