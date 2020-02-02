import authRoutes from 'auth/routes'

export class AuthModule {

    get name () {
        return 'auth'
    }

    constructor (router, store) {
        this.router = router
        this.store = store
    }

    install () {
        this.router.addRoutes(authRoutes)
    }
}
