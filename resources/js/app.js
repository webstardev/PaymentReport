/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from '@/services/store';

import '@/plugins/vue-bootstrap.js';
import '@/plugins/sweetalert.js';
import '@/plugins/axios.js';
import '@/plugins/loading-overlay.js';
import '@/plugins/vee-validate.js';
import '@/plugins/vue-multiselect.js';

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app');
