import AuthView from 'auth/views/AuthView.vue'

const Login = import(/* webpackChunkName: "auth" */ 'auth/components/Login.vue')
const Register = import(/* webpackChunkName: "auth" */ 'auth/components/Register.vue')

export default [
    {
        path: '/auth',
        component: AuthView,
        redirect: '/auth/login',
        children: [
            {
                path: 'login',
                name: 'login',
                component: () => Login,
            },
            {
                path: 'register',
                name: 'register',
                component: () => Register,
            },
        ],
    },
]
