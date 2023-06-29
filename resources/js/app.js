import axios from 'axios';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store'
/* import Login from './components/Login.vue'; */
import VueGoogleMaps from '@fawmi/vue-google-maps';
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUserCircle,faEnvelope } from '@fortawesome/free-solid-svg-icons'
import { faFacebook, faInstagram } from '@fortawesome/free-brands-svg-icons';/* from '@fortawesome/free-solid-svg-icons' */
/* import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css'; */
/* import Vue3Toastify, { ToastContainerOptions } from 'vue3-toastify'; */
/* import Toaster from '@meforma/vue-toaster'; */
import { useVuelidate } from '@vuelidate/core';
import { required, email } from '@vuelidate/validators';
import dt from 'datatables.net';
import DataTable from 'datatables.net-vue3';
/* import DataTablesLib from 'datatables.net';
DataTable.use(DataTablesLib); */
import DataTablesCore from 'datatables.net-bs5';
DataTable.use(DataTablesCore);
/* import 'jquery';
import 'popper.js'; */
/* import toastr from 'toastr'; */
// Add toastr as a global property
/* window.toastr = toastr; */
/* import toastr from 'toastr'; */
/* const $ = require('jquery') */
window.$ = window.jQuery = require('jquery');
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
const app = createApp(App/* , {
  data() {
    return {
      editor: ClassicEditor,
      editorData: '<p>Content of the editor.</p>',
      editorConfig: {
        // The configuration of the editor.
      }
    };
  },
} */);


/* app.config.globalProperties.$toastr = toastr; */
/* app.component('Login', Login); */
app.component('data-table', DataTable);
app.component('font-awesome-icon', FontAwesomeIcon); // Register the FontAwesomeIcon component globally
app.use(VueSweetalert2);
//app.use(Vuelidate);
app.use(useVuelidate);
// Register global validators
app.config.globalProperties.$validators = {
  required,
  email,
  // Add more validators if needed
};
app.use(router);
app.use(store);

/* app.use(toastr); */
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
app.use(VueGoogleMaps, {
  load: {
      /* key: 'AIzaSyA3SIA74ztFJVV723RDu9RBoMtj7aARUB4', */
      /* key: 'AIzaSyADcTKEPZawqDctL4yNXgx5uaWoOE1Wtf4', */
      /* key: 'AIzaSyBea65xicuAhWz8oVHvEn6_Rlg8h9nr_EU', */
      key: 'AIzaSyAfrTNx21m1BpOFe12uPZsCof8An3TKutk',
      libraries: 'places', 
      autobindAllEvents: true,
  },
}).use(CKEditor).mount('#app');
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

