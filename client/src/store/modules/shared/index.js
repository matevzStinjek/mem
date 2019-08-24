import { getState } from './state'
import actions from './actions'
import getters from './getters'
import validationGetters from './validationGetters'
import mutations from './mutations'

const store = {
    state: getState(),
    getters: {...getters, ...validationGetters},
    mutations,
    actions,
}

export default store
