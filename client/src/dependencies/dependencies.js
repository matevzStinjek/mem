import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import VueCompositionApi from '@vue/composition-api'
import helpers from './helpers'

export class Dependencies {

    get name () {
        return 'dependencies'
    }

    install (Vue) {
        Vue.use(BootstrapVue)
        Vue.use(BootstrapVueIcons)
        Vue.use(VueCompositionApi)

        helpers.install(Vue)
    }
}
