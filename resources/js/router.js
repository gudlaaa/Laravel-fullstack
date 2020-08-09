import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import firstpage from './components/pages/myFirstVuePage'
import newRoute from './components/pages/newRoute'
import methods from './components/pages/basic/methods'

// admin project pages
import home from './components/pages/home'
import tags from './admin/pages/tags'
import category from './admin/pages/category'

const routes = [

    //project routes

    {
        path: '/',
        component: home
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
