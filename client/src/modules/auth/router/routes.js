import AuthLoginView from '../views/AuthLoginView.vue'
import AuthRegisterView from '../views/AuthRegisterView.vue'

export const homeRoutes = [
    {
        path: '/login',
        name: 'authLoginView',
        component: AuthLoginView,
    },
    {
        path: '/register',
        name: 'authRegisterView',
        component: AuthRegisterView,
    },
]