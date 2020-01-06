import Vue from 'vue'
import Vuex from 'vuex'
import shared from './modules/shared'

Vue.use(Vuex)

export default new Vuex.Store({
    namespaced: true,
    ...shared,
})
