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
        user_sistema: JSON.parse(localStorage.getItem('user_sistema'))||null,
        modulos: JSON.parse(localStorage.getItem('modulos')) || [],
    },
    modules:{
        // 'sidebar': moduleSidebar
    },
    mutations: {        
        auth_success(state,user_sistema){
            state.user_sistema=user_sistema;
            localStorage.setItem('user_sistema',JSON.stringify(state.user_sistema));
            axios.defaults.headers.common['Authorization'] = state.user_sistema.usuario;
            store.commit('getModulos');
        },
        auth_close(state){
            console.log("deslogin");
            state.user_sistema=null;
            localStorage.removeItem('user_sistema');
            localStorage.removeItem('modulos');
        },
        getModulos(state){
            if (state.user_sistema!=null) {
                var id=state.user_sistema.id;
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
if (store.state.user_sistema) {
    axios.defaults.headers.common['Authorization'] = store.state.user_sistema.usuario;
}
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
        path: '/reportes/balance-periodo',
        component: require('./views/BalancePeriodo.vue').default,
    },
    {
        path: '/reportes/recurrente',
        component: require('./views/Recurrente.vue').default,
    },
    {
        path: '/reportes/otros',
        component: require('./views/OtrosReportes.vue').default,
    },
    {
        path: '/reportes/cuenta',
        component: require('./views/RCuenta.vue').default,
    },
];

// function login(){
//     return axios.get(url_base+'/comprobar').then(res=>res.data).catch(res=>res);
// }
var router=new VueRouter({
    mode: 'history',
    routes,
    linkExactActiveClass: "active"
});
function validado(ruta) {
    for (let i = 0; i < store.state.modulos.length; i++) {
        const modulo = store.state.modulos[i];
        if (modulo.ruta==ruta) {
            return true;
        }
    }
    return false;
}
router.beforeEach(async (to, from, next) => {
    $('.modal-backdrop').remove();
    if (store.state.user_sistema!=null) {
        switch (to.path) {
            case "/login":
                next("/");
                break;
            case "/":
                next();
                break;
        
            default:
                if (validado(to.path)) {
                    next();
                }else{
                    next("/");
                }
                break;
        }
    } else {
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