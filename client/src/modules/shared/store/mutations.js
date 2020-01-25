import { getState } from './state'

const getResetState = (currentState, excludedStateKeys) => {
    const resetState = getState()

    for (const key of excludedStateKeys) {
        resetState[key] = currentState[key]
    }

    return resetState
}

export default {
}
