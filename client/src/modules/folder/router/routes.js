import FolderDetailView from '../views/FolderDetailView.vue'

export const homeRoutes = [
    {
        path: '/folder/:id',
        name: 'folderDetail',
        component: FolderDetailView,
    },
]