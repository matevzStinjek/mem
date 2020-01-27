// import folderRoutes from 'folder/routes'
// import folderStore from 'folder/store'

export class FolderModule {

    get name () {
        return 'folder'
    }

    constructor (router, store) {
        this.router = router
        this.store = store
    }

    install () {
        // this.router.addRoutes(folderRoutes)
        // this.store.registerModule(this.name, folderStore)
    }
}
