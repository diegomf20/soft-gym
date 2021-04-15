<template>
    <div class="wrapper lite">
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="/logo.png">
            </div>
            <div class="sidebar-content">
                <a class="nav-link" @click="logout()">Logout</a>
                <hr>
                <ul class="nav-list">
                    <li>
                        <router-link class="nav-link" to="/">
                            <div class="nav-icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <span>Dashboard</span>
                        </router-link>
                    </li>
                    <li v-if="existe('/ingreso')||existe('/ingreso/lista')">
                        <a class="nav-link"
                            data-toggle="collapse" 
                            href="#collapseIngresos" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="collapseIngresos">
                            <div class="nav-icon">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <span>Ingresos</span>
                        </a>
                    </li>
                    <li v-if="existe('/ingreso')||existe('/ingreso/lista')">
                        <ul class="collapse nav-list" id="collapseIngresos">
                            <li v-if="existe('/ingreso')">
                                <router-link class="nav-link" :to="{ path: '/ingreso' }" replace>
                                    Nuevo Ingreso
                                </router-link>
                            </li>
                            <li v-if="existe('/ingreso/lista')">
                                <router-link class="nav-link" to="/ingreso/lista">
                                    Lista de Ingresos
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <li  v-if="existe('/egreso')||existe('/egreso/lista')">
                        <a class="nav-link"
                            data-toggle="collapse" 
                            href="#collapseEgresos" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="collapseEgresos">
                            <div class="nav-icon">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <span>Egresos</span>
                        </a>
                        <ul class="collapse nav-list" id="collapseEgresos">
                            <li v-if="existe('/egreso')">
                                <router-link class="nav-link" to="/egreso">
                                    <span>Egresos</span>
                                </router-link>
                            </li>
                            <li v-if="existe('/egreso/lista')">
                                <router-link class="nav-link" to="/egreso/lista">
                                    Lista de Egresos
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <!-- <li v-if=""> -->
                    <!-- </li> -->
                    <li v-if="existe('/membresia')">
                        <router-link class="nav-link" to="/membresia">
                            <div class="nav-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <span>Membresias</span>
                        </router-link>
                    </li>
                    <li>
                        <a  class="nav-link"
                            data-toggle="collapse" 
                            href="#collapseTablas" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="collapseTablas">
                            <div class="nav-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <span>Tablas</span>
                        </a>
                    </li>
                    <li>
                        <ul class="collapse nav-list" id="collapseTablas">
                            <li>
                                <router-link class="nav-link" to="/producto">
                                    <span>Productos</span>
                                </router-link>
                            </li>
                            <li>
                                <router-link class="nav-link" to="/servicio">
                                    <span>Servicios</span>
                                </router-link>
                            </li>
                            <li>
                                <router-link class="nav-link" to="/cliente">
                                    <span>Clientes</span>
                                </router-link>
                            </li>
                            <li>
                                <router-link class="nav-link" to="/concepto">
                                    <span>Conceptos</span>
                                </router-link>
                            </li>
                            <li>
                                <router-link class="nav-link" to="/cuenta">
                                    <span>Cuentas</span>
                                </router-link>
                            </li>
                            <li>
                                <router-link class="nav-link" to="/user">
                                    <span>Usuarios</span>
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-link"
                            data-toggle="collapse" 
                            href="#collapseReportes" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="collapseReportes">
                            <div class="nav-icon">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <span>Reportes</span>
                        </a>
                    </li>
                    <li>
                        <ul class="collapse nav-list" id="collapseReportes">
                            <li>
                                <router-link class="nav-link" to="/reportes/balance">
                                    <span>Balance</span>
                                </router-link>
                            </li>
                            <li>
                                <router-link class="nav-link" to="/reportes/recurrente">
                                    <span>Recurrente</span>
                                </router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="content">
            <div class="content-fluid">
                <slot/>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState,mapMutations } from 'vuex'
export default {
    computed: {
        ...mapState(['modulos']),
    },
    methods: {
        existe(val){
            for (let i = 0; i < this.modulos.length; i++) {
                if (this.modulos[i].ruta==val) {
                    return true;
                }
            }
            return false;
        },
        logout(){
            axios.post(`${url_base}/logout`)
            .then((params)=> {
                var respuesta=params.data;
                switch (respuesta.status) {
                    case "OK":
                        // location.reload();
                        break;
                
                    default:
                        break;
                }
            });
        }
    },
}
</script>