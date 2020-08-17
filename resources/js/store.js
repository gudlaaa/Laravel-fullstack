import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        counter: 1000
    },
    getters: {
        getCounter(state){
            return state.counter
        },
    },
    mutations:{
        startTheCounter(state, data){
            state.counter += data
        }
    },
    actions: {
        ChangeTheCounterAction({commit}, data){
         commit('startTheCounter', data)
        }
    }
})
