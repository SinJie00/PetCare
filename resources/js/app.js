import axios from 'axios';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store'
/* import Login from './components/Login.vue'; */
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUserCircle,faEnvelope } from '@fortawesome/free-solid-svg-icons'
import { faFacebook, faInstagram } from '@fortawesome/free-brands-svg-icons';/* from '@fortawesome/free-solid-svg-icons' */
/* import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css'; */
/* import Vue3Toastify, { ToastContainerOptions } from 'vue3-toastify'; */
/* import Toaster from '@meforma/vue-toaster'; */

/* import 'jquery';
import 'popper.js'; */
/* import toastr from 'toastr'; */
// Add toastr as a global property
/* window.toastr = toastr; */
/* import toastr from 'toastr'; */
require('./bootstrap');
library.add(faUserCircle,faFacebook, faEnvelope, faInstagram);

/* axios.defaults.withCredentials = true;

axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response.status === 401) {
      store.dispatch('auth/logout')
    }
    return Promise.reject(error)
  }
) */

const app = createApp(App);
/* app.config.globalProperties.$toastr = toastr; */
/* app.component('Login', Login); */
app.component('font-awesome-icon', FontAwesomeIcon); // Register the FontAwesomeIcon component globally
app.use(router);
app.use(store);
app.use(toastr);
/* app.use(VueToasted, {
  duration: 5000,
  position: 'top-right',
  theme: 'bubble'
}) */
/* app.use(toast, {
  position: 'bottom-right',
  timeout: 3000,
  closeOnClick: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.7,
  showCloseButtonOnHover: false,
  hideProgressBar: true,
  closeButton: 'button',
  icon: true,
  rtl: false
}) */
/* app.config.globalProperties.$toast = app.config.globalProperties.$toast || Toast
 */
app.mount('#app');
/* app.use(BootstrapVue);
app.use(IconsPlugin); */

// Use Vue imported from vue/dist/vue.esm-bundler.js
/* app.config.globalProperties.$Vue = Vue; */



/* import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css'; */

// Import Vue from vue/dist/vue.esm-bundler.js
/* import Vue from 'vue/dist/vue.esm-bundler.js'; 
app.mount('#app');*/

/* import Vue from 'vue';
import App from './App.vue'
import router from './router'

import './sass/app.scss' // import your SASS file here

Vue.config.productionTip = false 

new Vue({
  router,
  render: h => h(App)
}).$mount('#app') */


/* import Login from './components/Login.vue';

Vue.component('login', Login);

const app = new Vue({
  el: '#app',
  Login,
});
 */

