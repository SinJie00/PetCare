/* import Vue from 'vue'
import Vuex from 'vuex' */
import createPersistedState from 'vuex-persistedstate'
import { createStore } from 'vuex'
import auth from './auth'

/* Vue.use(Vuex) */

/* export default new Vuex.Store({
    plugins:[
        createPersistedState()
    ],
    modules:{
        auth
    }
}) */

const store = createStore({
    plugins: [
      createPersistedState()
    ],
    modules: {
      auth
    }
})

export default store