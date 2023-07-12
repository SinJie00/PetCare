import { createRouter, createWebHistory } from 'vue-router';
import store from '../store'; // Import the store
import {
  Home,
  Login,
  Register,
  Profile,
  ForgotPassword,
  ResetPassword,
  AdoptionAnimal,
  AdoptionApplication,
  ProductDonationAdmin,
  StrayPostAdmin,
  CreateArticleAdmin,
  EditArticleAdmin,
  ArticleAdmin,
  VolunteerApplicationAdmin,
  Adoption,
  MoneyDonation,
  ProductDonation,
  VolunteerApplication,
  StrayPost,
  Article,
  ArticlePage,
} from '../pages/index.js';

/* import Home from '../pages/Home';
import Login from '../pages/Login';
import Register from '../pages/Register';
import Profile from '../pages/Profile'; */

/* import { ROLE } from '../constants'; */

/* const isAuthenticated = () => !!localStorage.getItem('token');
const isAdmin = () => localStorage.getItem('userRole') === 'admin'; */

/* const requireAuth = (to, from, next) => {
  if (!isAuthenticated()) {
    // If not authenticated, redirect to login page
    next('/login');
  } else if (to.matched.some(record => record.meta.requiresAdmin) && !isAdmin()) {
    // If the route requires admin role but user is not an admin, redirect to home page
    next('/');
  } else {
    // Otherwise, proceed to the requested route
    next();
  }
} */

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    /* beforeEnter: (to, from, next) => {
      //const isAuthenticated = !!localStorage.getItem('token');
      if (isAuthenticated) {
        next({ name: 'home' }); // redirect to home page if authenticated
      } else {
        next(); // proceed to login page
      }
    } */
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/profile',
    name: 'Profile',
    component: Profile,
    /* meta: {
      requiresAuth: true,
      roles: [ROLE.USER, ROLE.ADMIN],
    }, */
  },
  {
    path: '/forgotpassword',
    name: 'ForgotPassword',
    component: ForgotPassword,
  },
  {
    path: '/api/reset-password',
    name: 'ResetPassword',
    component: ResetPassword,
    /* props: route => ({
      token: route.query.token,
      email: route.query.email,
    }), */
  },
  {
    path: '/admin/adoptionanimal',
    name: 'AdoptionAnimal',
    component: AdoptionAnimal,
    meta: {
      requiresAdmin: true
    }
  },
  {
    path: '/admin/adoptionapplication',
    name: 'AdoptionApplication',
    component: AdoptionApplication,
    meta: {
      requiresAdmin: true
    }
  },
  {
    path: '/admin/volunteerapplication',
    name: 'VolunteerApplicationAdmin',
    component: VolunteerApplicationAdmin,
    meta: {
      requiresAdmin: true
    }
  },
  {
    path: '/admin/productdonation',
    name: 'ProductDonationAdmin',
    component: ProductDonationAdmin,
    meta: {
      requiresAdmin: true
    }
  },
  {
    path: '/admin/straypost',
    name: 'StrayPostAdmin',
    component: StrayPostAdmin,
    meta: {
      requiresAdmin: true
    }
  },
  {
    path: '/admin/article',
    name: 'ArticleAdmin',
    component: ArticleAdmin,
    meta: {
      requiresAdmin: true
    }
  },
  {
    path: '/admin/article/create',
    name: 'CreateArticleAdmin',
    component: CreateArticleAdmin,
    meta: {
      requiresAdmin: true
    }
  },
  {
    path: '/admin/article/:articleId/edit',
    name: 'EditArticleAdmin',
    component: EditArticleAdmin,
    meta: {
      requiresAdmin: true
    }
  },
  {
    path: '/adoption',
    name: 'Adoption',
    component: Adoption
  },
  {
    path: '/productdonation',
    name: 'ProductDonation',
    component: ProductDonation
  },
  {
    path: '/moneydonation',
    name: 'MoneyDonation',
    component: MoneyDonation
  },
  {
    path: '/volunteerapplication',
    name: 'VolunteerApplication',
    component: VolunteerApplication
  },
  {
    path: '/straypost',
    name: 'StrayPost',
    component: StrayPost
  },
  {
    path: '/article',
    name: 'Article',
    component: Article
  },
  {
    path: '/articles/:articleId',
    name: 'ArticlePage',
    component: ArticlePage,
  },
  /* {
    path: '/donation',
    component: Donation,
    meta: { requiresAuth: true },
    children: [
      {
        path: 'admin',
        component: DonationAdmin,
        meta: { roles: [ROLE.ADMIN] }
      },
      {
        path: 'user',
        component: DonationUser,
        meta: { roles: [ROLE.ADMIN] }
      }
    ]
  } */

];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const isAdmin = store.getters['auth/isAdmin'];
  const isAuthenticated = store.getters['auth/isLoggedIn'];

  if ((to.name === 'Login' && isAuthenticated) || (to.matched.some(record => record.meta.requiresAdmin) && !isAdmin)) {
    next({ name: 'Home' }); // redirect user to home page
  } else if (to.name === 'Profile' && !isAuthenticated) {
    next({ name: 'Login' }); // redirect unauthenticated user to login page
  } else {
    next(); // proceed to the requested page
  }
});


/* router.beforeEach((to, from, next) => {
  const isAuthenticated = store.getters['auth/isAuthenticated'];
  const userRole = store.getters['auth/userRole'];

  if (to.meta.roles.includes(ROLE.ADMIN) && (!isAuthenticated || userRole !== ROLE.ADMIN)) {
    next({ name: 'login' }); // redirect to login page if not authenticated or not an admin
  } else if (to.name === 'login' && isAuthenticated) {
    next({ name: 'home' }); // redirect to home page if authenticated user tries to access login page
  } else {
    next(); // proceed to the requested page
  }
}); */


/* router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
      if (!localStorage.getItem('token')) {
          next('/login');
      } else {
          if (to.matched.some(record => record.meta.requiresAdmin)) {
              if (localStorage.getItem('user_role') !== 'admin') {
                  next('/');
              } else {
                  next();
              }
          } else {
              next();
          }
      }
  } else {
      next();
  }
}); */

export default router;

