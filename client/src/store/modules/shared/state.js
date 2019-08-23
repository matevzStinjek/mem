const state = {
}

export const getState = () => {
    return JSON.parse(JSON.stringify(state))
}
