/* import Vue from 'vue'; */
import { createRouter, createWebHistory } from 'vue-router';
/* import VueRouter from 'vue-router' */

/* import Home from '@/components/Home'
import Register from '@/components/Register'
import Login from '@/components/Login' */
import Home from '../components/Home';
import Login from '../components/Login';
import Register from '../components/Register';
/* Vue.use(VueRouter) */

/* const router = new VueRouter({
  mode: 'history',
  routes :[*/
  const routes = [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/register',
      name: 'register',
      component: Register
    }
  ];
/* }); */

/* const router = new VueRouter({
  mode: 'history',
  routes,
}); */
const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;

/* export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/register',
      name: 'Register',
      component: Register
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
  ]
}) */
