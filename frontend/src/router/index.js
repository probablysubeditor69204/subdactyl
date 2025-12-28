import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Dashboard from '../views/Dashboard.vue'
import CreateServer from '../views/CreateServer.vue'
import Admin from '../views/Admin.vue'
import Setup from '../views/Setup.vue'

const routes = [
    { path: '/', redirect: '/dashboard' },
    { path: '/setup', component: Setup, meta: { guest: true } },
    { path: '/login', component: Login, meta: { guest: true } },
    { path: '/register', component: Register, meta: { guest: true } },
    { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/create-server', component: CreateServer, meta: { requiresAuth: true } },
    { path: '/admin', component: Admin, meta: { requiresAuth: true, requiresAdmin: true } },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token')
    const user = JSON.parse(localStorage.getItem('user') || '{}')

    if (to.meta.requiresAuth && !token) {
        next('/login')
    } else if (to.meta.guest && token) {
        next('/dashboard')
    } else if (to.meta.requiresAdmin && user.role !== 'admin') {
        next('/dashboard')
    } else {
        next()
    }
})

export default router
