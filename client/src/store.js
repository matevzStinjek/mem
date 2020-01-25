import Vue from 'vue'
import Vuex from 'vuex'
import shared from './modules/shared/store'

Vue.use(Vuex)

export default new Vuex.Store({
    namespaced: true,
    ...shared,
})
