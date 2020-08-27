import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import firstpage from './components/pages/myFirstVuePage'
import newRoute from './components/pages/newRoute'
import methods from './components/pages/basic/methods'
import usecom from './vuex/usecom'

// admin project pages
import home from './components/pages/home'
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminusers from './admin/pages/adminusers'
import login from './admin/pages/login'
import role from './admin/pages/role'
import assignRole from './admin/pages/assignRole'



const routes = [

    //project routes

    {
        path: '/',
        component: home,
        name: 'home'
    },
    {
        path: '/testvuex',
        component: usecom
    },
    {
        path: '/tags',
        component: tags,
        name: 'tags'
    },
    {
        path: '/category',
        component: category,
        name: 'category'
    },
    {
        path: '/adminusers',
        component: adminusers,
        name: 'adminusers'
    },
    {
        path: '/role',
        component: role,
        name: 'role'
    },
    {
        path: '/assignRole',
        component: assignRole,
        name: 'assignRole'
    },
    {
        path: '/login',
        component: login,
        name: 'login'
    },
    {
        path: '/my-name-vue-route',
        component: firstpage
    },
    {
        path: '/new-route',
        component: newRoute
    },
    {
        path: '/methods',
        component: methods
    }
]

export default new Router({
    mode: 'history',
    routes
})
