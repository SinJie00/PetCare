import axios from 'axios'
import router from '../router'

export default {
    namespaced: true,
    state: {
        token: localStorage.getItem('token') || '',
        status: '',
        /* authenticated:false, */
        user: {}
    },
    getters: {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,
        currentUser: state => state.user
        /* authenticated(state){
            return state.authenticated
        },
        user(state){
            return state.user
        } */
    },
    mutations: {
        SET_USER(state, user) {
            state.user = user;
        },
        AUTH_REQUEST(state) {
            state.status = 'loading';
        },
        AUTH_SUCCESS(state, {token, user}) {
            state.status = 'success';
            state.token = token;
            state.user = user;
            /* state.error = null; */
        },
        AUTH_ERROR(state, error) {
            state.status = 'error';
            /* state.error = error; */
        },
        LOGOUT(state) {
            state.status = '';
            state.token = '';
            state.user = {};
            /* state.error = null; */
        },
        USER_SUCCESS(state, user) {
            state.status = 'success';
            state.user = user;
        },
        /* SET_AUTHENTICATED (state, value) {
            state.authenticated = value
        },
        SET_USER (state, value) {
            state.user = value
        } */
    },
    actions: {
        async login({ commit }, user) {
            try {
                commit('AUTH_REQUEST');
                const response = await axios.post('/api/login', user);
                const {user, token} = response.data;
                /* const token = response.data.token */ 
                localStorage.setItem('token', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                commit('AUTH_SUCCESS', {token, user});
                return response;
            } catch (error) {
                commit('AUTH_ERROR');
                localStorage.removeItem('token');
                delete axios.defaults.headers.common['Authorization'];
                /* return Promise.reject(error); */
                throw new Error(error.response.data.message);
            }
        },

        async logout({ commit }) {
            try {
                await axios.post('/api/logout')
            } catch (error) {
            // Ignore error
            }
            commit('LOGOUT');
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
        },
        async register({ commit }, user) {
            try {
                commit('AUTH_REQUEST');
                const response = await axios.post('/api/register', user);
                const {user, token} = response.data;
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                commit('AUTH_SUCCESS', { token, user });
                resolve(response);
            } catch (error) {
                commit('AUTH_ERROR');
                localStorage.removeItem('token');
                throw new Error(error.response.data.message);
                /* reject(error); */
            }
        }, 
        async getUser({ commit }) {
            try {
                const response = await axios.get('/api/user');
                const user = response.data;
                commit('USER_SUCCESS', user);
                return response;
            } catch (error) {
                commit('AUTH_ERROR');
                localStorage.removeItem('token');
                delete axios.defaults.headers.common['Authorization'];
                return Promise.reject(error);
            }
        }
        /* async login({commit}){
            await axios.get('/api/login').then(({data})=>{
                commit('SET_USER',data)
                commit('SET_AUTHENTICATED',true)
                router.push({name:'Home'})
            }).catch(({response:{data}})=>{
                commit('SET_USER',{})
                commit('SET_AUTHENTICATED',false)
            })
        },
        async logout({commit}){
            await axios.post('/api/logout'); // add this line (from chatgpt)
            commit('SET_USER',{})
            commit('SET_AUTHENTICATED',false)
        },
        async fetchUser(commit) {
            const response = await axios.get('/api/user');
            const user = response.data;
            commit('setUser', user);
          } */

    }
}