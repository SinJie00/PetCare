import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home';
import Login from '../components/Login';
import Register from '../components/Register';

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    beforeEnter: (to, from, next) => {
      const isAuthenticated = !!localStorage.getItem('token');
      if (isAuthenticated) {
        next({ name: 'Home' }); // redirect to home page if authenticated
      } else {
        next(); // proceed to login page
      }
    }
  },
  {
    path: '/register',
    name: 'register',
    component: Register
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;

