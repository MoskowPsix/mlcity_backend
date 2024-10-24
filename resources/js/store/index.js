import Vue from 'vue'
import Vuex from 'vuex'
import typeState from './modules/typeState'
Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        typeState,
    },
})
