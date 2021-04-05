import Vue from 'vue'
import Dashboard from './app.vue';

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import swal from 'sweetalert';


import VueNumericInput from 'vue-numeric-input';
Vue.use(VueNumericInput)

import moment from 'moment';
window.moment=moment;

window.axios = require('axios');

var routes =[
    {
        path: '/dashboard',
        component: require('./views/Dashboard.vue').default,
    },
    {
        path: '/ingreso',
        component: require('./views/Ingreso.vue').default,
    },
    {
        path: '/egreso',
        component: require('./views/Egreso.vue').default,
    },
    {
        path: '/producto',
        component: require('./views/Producto.vue').default,
    },
    {
        path: '/servicio',
        component: require('./views/Servicio.vue').default,
    },
    {
        path: '/cliente',
        component: require('./views/Cliente.vue').default,
    },
    {
        path: '/cuenta',
        component: require('./views/Cuenta.vue').default,
    },
    {
        path: '/concepto',
        component: require('./views/Concepto.vue').default,
    },
];
Vue.component('panel',require("./layouts/panel.vue").default);

const router=new VueRouter({
    mode: 'history',
    routes,
    linkActiveClass: 'active'
})
const app = new Vue({
    el: '#app',
    router,
    render: h => h(Dashboard)
});