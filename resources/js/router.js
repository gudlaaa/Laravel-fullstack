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


const routes = [

    //project routes

    {
        path: '/',
        component: home
    },
    {
        path: '/testvuex',
        component: usecom
    },
    {
        path: '/tags',
        component: tags
    },
    {
        path: '/category',
        component: category
    },
    {
        path: '/adminusers',
        component: adminusers
    },
    {
        path: '/login',
        component: login
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
