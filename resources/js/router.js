import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import firstpage from './components/pages/myFirstVuePage'
import newRoute from './components/pages/newRoute'
import methods from './components/pages/basic/methods'

//projec
import home from './components/pages/home'
import tags from './components/pages/tags'

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
