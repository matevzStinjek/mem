function setup () {
    return new Promise((resolve, reject) => {
        setTimeout(resolve, 1000)
    })
}

function defaultSlot (h) {
    const style = { textAlign: 'center', color: this.theme === 'dark' ? 'white' : 'black' }
    return h('div', { style }, 'Dialog content goes here')
}

export default {
    metaName: 'Dialog',
    hasAbsolutePosition: true,
    variations: {
        theme: ['dark', 'light'],
        hasBackButton: [true, false],
        hasCloseButton: [true, false],
        stepId: ['a', null],
    },
    usecases: [
        {
            name: 'Basic',
            setup: setup,
            scopedSlots: { default: defaultSlot },
            steps: [
                { id: 'a', passiveLabel: 'A', activeLabel: 'A' },
                { id: 'b', passiveLabel: 'B', activeLabel: 'B' },
            ],
        },
    ],
}
