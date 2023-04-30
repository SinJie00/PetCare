import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
/* import Login from './components/Login.vue'; */
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faEnvelope } from '@fortawesome/free-solid-svg-icons'
import { faFacebook, faInstagram } from '@fortawesome/free-brands-svg-icons';/* from '@fortawesome/free-solid-svg-icons' */

require('./bootstrap');
library.add(faFacebook, faEnvelope, faInstagram);

const app = createApp(App);
/* app.component('Login', Login); */
app.component('font-awesome-icon', FontAwesomeIcon); // Register the FontAwesomeIcon component globally
app.use(router);
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

