import { createWebHistory, createRouter } from 'vue-router'
import store from '@/store'

/* Guest Component */
const Login = () => import('@/components/Login.vue')
const Register = () => import('@/components/Register.vue')
/* Guest Component */

/* Layouts */
const DahboardLayout = () => import('@/components/layouts/Default.vue')
/* Layouts */

/* Authenticated Component */
const Dashboard = () => import('@/components/Dashboard.vue')
const Incomes = () => import('@/components/Incomes.vue')
const Tags = () => import('@/components/Tags.vue')
/* Authenticated Component */


const routes = [
    {
        name: 'login',
        path: '/login',
        component: Login,
        meta: {
            middleware: 'guest',
            title: `Login`
        }
    },
    {
        name: 'register',
        path: '/register',
        component: Register,
        meta: {
            middleware: 'guest',
            title: `Register`
        }
    },
    {
        path: '/',
        component: DahboardLayout,
        meta: {
            middleware: 'auth'
        },
        children: [
            {
                name: 'dashboard',
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            },
            {
                name: 'incomes',
                path: '/incomes',
                component: Incomes,
                meta: {
                    middleware: 'auth',
                    title: `Incomes`
                }
            },
            {
                name: 'tags',
                path: '/tags',
                component: Tags,
                meta: {
                    middleware: 'auth',
                    title: `Tags`
                }
            },
        ]
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == 'guest') {
        if (store.state.auth.authenticated) {
            next({ name: 'dashboard' })
        }
        next()
    } else {
        if (store.state.auth.authenticated) {
            next()
        } else {
            next({ name: 'login' })
        }
    }
})

export default router
