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

import Vuex from 'vuex'
Vue.use(Vuex)
window.store=new Vuex.Store({
    state: {
        cuenta: JSON.parse(localStorage.getItem('cuenta_sistema'))||null,
        modulos: JSON.parse(localStorage.getItem('modulos')) || [],
        conexion: false
    },
    modules:{
        // 'sidebar': moduleSidebar
    },
    mutations: {        
        auth_success(state,cuenta){
            state.cuenta=cuenta;
            localStorage.setItem('cuenta_sistema',JSON.stringify(state.cuenta));
            // axios.defaults.headers.common['Authorization'] = state.cuenta.api_token;
            store.commit('getModulos');
        },
        auth_close(state){
            state.cuenta=null;
            localStorage.removeItem('cuenta_sistema');
            localStorage.removeItem('modulos');
            localStorage.removeItem('fundo');
        },
        getModulos(state){
            if (state.cuenta!=null) {
                var id=state.cuenta.id;
                axios.get(url_base+'/user/'+id+'/privilegios')
                .then(response => {
                    state.modulos = response.data;
                    localStorage.setItem('modulos',JSON.stringify(state.modulos));
                    console.log("modulos actualizados");
                });
            }
        },
    },
    actions: {}
});

var routes =[
    {
        path: '/',
        component: require('./views/Dashboard.vue').default,
    },
    {
        path: '/login',
        component: require('./views/Login.vue').default,
        meta:{
            layout: 'vacio'
        }
    },
    {
        path: '/ingreso',
        component: require('./views/Ingreso.vue').default,
    },
    {
        path: '/ingreso/lista',
        component: require('./views/ListaIngreso.vue').default,
    },
    {
        path: '/egreso',
        component: require('./views/Egreso.vue').default,
    },
    {
        path: '/egreso/lista',
        component: require('./views/ListaEgreso.vue').default,
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
        path: '/user',
        component: require('./views/User.vue').default,
    },
    {
        path: '/concepto',
        component: require('./views/Concepto.vue').default,
    },
    {
        path: '/membresia',
        component: require('./views/Membresia.vue').default,
    },
    {
        path: '/reportes/balance',
        component: require('./views/Balance.vue').default,
    },
    {
        path: '/reportes/recurrente',
        component: require('./views/Recurrente.vue').default,
    },
];

function login(){
    return axios.get(url_base+'/comprobar').then(res=>res.data).catch(res=>res);
}
var router=new VueRouter({
    mode: 'history',
    routes,
    linkExactActiveClass: "active"
});
router.beforeEach(async (to, from, next) => {
    var auth=await login();
    var auth_status=(auth.status=="OK") ?  true : false;
    if (auth_status) {
        if (to.path=="/login") {
            next("/");
        }else{
            next();
        }
    } else {
        // console.log("aqui");
        if (to.path=="/login") {
            next();
        }else{
            next('login');
        }
    }
});


Vue.component('vacio',require("./layouts/vacio.vue").default);
Vue.component('panel',require("./layouts/panel.vue").default);
const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(Dashboard)
});