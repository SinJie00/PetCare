import axios from 'axios'

export default {
    namespaced: true,
    state: {
        token: localStorage.getItem('token') || '',
        status: '',
        user: {}
    },
    getters: {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,
        currentUser: state => state.user
    },
    mutations: {
        SET_USER(state, user) {
            state.user = user;
        },
        AUTH_REQUEST(state) {
            state.status = 'loading';
        },
        AUTH_SUCCESS(state, { token, user }) {
            state.status = 'success';
            state.token = token;
            state.user = user;
            /* state.error = null; */
        },
        /* AUTH_ERROR(state, error) {
            state.status = 'error';
        }, */
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
    },
    actions: {
        async login({ commit }, { email, password }) {
            try {
                console.log('hi here');
                console.log(email);
                console.log('end');
                commit('AUTH_REQUEST');
                // Get CSRF cookie
                await axios.get('/sanctum/csrf-cookie');
                const response = await axios.post('http://localhost:81/api/login', { email, password });/* .then(response =>{ */
                console.log('response here');
                console.log(response.data);
                console.log(response);
                const { user, token } = response.data;
                /* console.log('get the token');
                console.log(token);
                console.log(user); */
                localStorage.setItem('token', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                commit('AUTH_SUCCESS', { token, user });
                /* this.$toaster.success('Login successful!'); */
                /* return response; */
                return Promise.resolve();
            } catch (error) {
                console.log("Error here");
                console.log(error);
                /* commit('AUTH_ERROR'); */
                localStorage.removeItem('token');
                delete axios.defaults.headers.common['Authorization'];
                return Promise.reject(error);
                /* if (error.response && error.response.data) {
                    const { message, errors } = error.response.data;
                    return Promise.reject(errors || message);
                } */
                
                /* return Promise.reject(error); */
                /* console.log(error.response.data.message);
                throw new Error(error.response.data.message); */
            }
        },

        async logout({ commit, state }) {
            console.log('Calling logout action');
            try {
                const token = state.token;
                const response = await axios.post('http://localhost:81/api/logout', {}, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                console.log('Logout response:', response);
            } catch (error) {
                console.error('Logout error:', error);
            }
            commit('LOGOUT');
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
        },
        async register({ commit }, user) {
            try {
                commit('AUTH_REQUEST');
                const response = await axios.post('/api/register', user);
                const { user, token } = response.data;
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                commit('AUTH_SUCCESS', { token, user });
                this.$toast.success('Registration successful!');
                return response;
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
                throw new Error(error.response.data.message);
            }
        }
    }
}