export const authRoutes = [
    {
        path: '/login',
        name: 'login',
        component: () => import('@/components/LoginPanel.vue'),
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('@/components/RegisterPanel.vue'),
    },
    {
        path: '*',
        redirect: { name: 'login' },
    },
]
