import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [
        {
            path: '/',
            component: () => import("@/views/home/HomeView"),
            children: [
                {
                    path: '',
                    name: 'folders',
                    alias: 'events',
                    component: () => import('@/views/home/FoldersView'),
                },
                {
                    path: 'events/:folderId',
                    name: 'folderDetail',
                    component: () => import('@/views/home/FolderDetailView'),
                }
            ],
        },
        {
            path: '/404',
            name: '404',
            component: () => import("@/views/error/404View"),
        },
        {
            path: '*',
            redirect: { name: '404' },
        },
    ]
})
