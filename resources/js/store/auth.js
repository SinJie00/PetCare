import axios from 'axios'

export default {
    namespaced: true,
    state: {
        token: localStorage.getItem('token') || '',
        status: '',
        user: {},
        userRole: null
    },
    getters: {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,
        currentUser: state => state.user,
        isAdmin: state => state.userRole == 2
    },
    mutations: {
        SET_USER(state, user) {
            state.user = user;
        },
        SET_USER_ROLE(state, role) {
            state.userRole = role;
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
            state.userRole = '';
            /* state.error = null; */
        },
        USER_SUCCESS(state, user) {
            state.status = 'success';
            state.user = user;
        },
        UPDATE_PROFILE_REQUEST(state) {
            state.status = 'loading';
        },
        UPDATE_USER(state, user) {
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
                commit('SET_USER_ROLE', user.role);
                /* console.log('get the token');
                console.log(token);
                console.log(user); */
                localStorage.setItem('token', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                commit('AUTH_SUCCESS', { token, user });
                /* this.$toast.success('Login successful!'); */
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
                console.log(user.name);
                console.log(user.email);
                console.log(user);
                const response = await axios.post('/api/register', user);
                const { currentUser, token } = response.data;
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                commit('AUTH_SUCCESS', { token, currentUser });
                return response;
            } catch (error) {
                /* commit('AUTH_ERROR'); */
                localStorage.removeItem('token');
                return Promise.reject(error);
                /* throw new Error(error.response.data.message); */
                /* reject(error); */
            }
        },
        async updateProfile({ commit, state }, user) {
            try {
                console.log('vuex update profile');
                console.log(user);
                console.log(state.token);
                commit('UPDATE_PROFILE_REQUEST');
                const response = await axios.put('http://localhost:81/api/update-profile',
                    {
                        name: user.name,
                        email: user.email,
                        gender: user.gender,
                        phone: user.phone,
                        address: user.address, 
                    },
                    {
                    headers: {
                        'Authorization': `Bearer ${state.token}`
                    }
                });
                commit('UPDATE_USER', response.data.user);
                /* commit('UPDATE_PROFILE_SUCCESS'); */
                console.log('success call api & set new user');
                console.log(response.data.user);
                return Promise.resolve(response);
            } catch (error) {
                console.log('vuex update error');
                //console.log(Promise.reject(error));
                return Promise.reject(error);
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
    }
}