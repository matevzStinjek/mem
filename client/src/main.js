import Vue from 'vue'
import { Dependencies } from '@/dependencies'
import { RouterModule } from '@/modules/router'
import { StoreModule } from '@/modules/store'
import { CoreModule } from '@/modules/core'
import { SubredditModule } from '@/modules/subreddit'

Vue.config.productionTip = false

function bootstrap () {
    const dependencies = new Dependencies()
    dependencies.install(Vue)

    const routerModule = new RouterModule()
    routerModule.install(Vue)

    const storeModule = new StoreModule()
    storeModule.install(Vue)

    const coreModule = new CoreModule(routerModule.router, storeModule.store)
    coreModule.install(Vue)

    const subredditModule = new SubredditModule(routerModule.router, storeModule.store)
    subredditModule.install(Vue)

    coreModule.mount()
}

bootstrap()
