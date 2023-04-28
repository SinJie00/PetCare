import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import Login from './components/Login.vue';
require('./bootstrap');
/* import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css'; */

// Import Vue from vue/dist/vue.esm-bundler.js
/* import Vue from 'vue/dist/vue.esm-bundler.js'; */

const app = createApp(App);
app.component('Login', Login);
app.use(router);

/* app.use(BootstrapVue);
app.use(IconsPlugin); */

// Use Vue imported from vue/dist/vue.esm-bundler.js
/* app.config.globalProperties.$Vue = Vue; */

app.mount('#app');


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

