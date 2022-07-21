require('./bootstrap');
import Vue from 'vue';

window.Vue = require('vue');

import App from './components/App.vue';
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import axios from 'axios';
import {routes} from './routes';
import DataTable from 'laravel-vue-datatable';
import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
import Datetime from 'vue-datetime'
// You need a specific loader for CSS files
import 'vue-datetime/dist/vue-datetime.css'

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(DataTable);
Vue.use(Datetime);
Vue.use(VueToast, {
    position: "top-right"
});

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

const app = new Vue({
    el: '#app',
    router: router,
    render: h => h(App),
});
