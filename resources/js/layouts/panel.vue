<template>
    <div class="wrapper lite">
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="/logo.png">
            </div>
            <div class="sidebar-content">
                <ul class="nav-list">
                    <li>
                        <router-link class="nav-link" to="./">
                            <div class="nav-icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <span>Dashboard</span>
                        </router-link>
                    </li>
                    <li>
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
                    <li>
                        <ul class="collapse nav-list" id="collapseIngresos">
                            <li>
                                <router-link class="nav-link" :to="{ path: '/ingreso' }" replace>
                                    Nuevo Ingreso
                                </router-link>
                            </li>
                            <li>
                                <router-link class="nav-link" to="/ingreso/lista">
                                    Lista de Ingresos
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <router-link class="nav-link" to="egreso">
                            <div class="nav-icon">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <span>Egresos</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link class="nav-link" to="membresia">
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
                                <router-link class="nav-link" to="producto">
                                    <span>Productos</span>
                                </router-link>
                            </li>
                            <li>
                                <router-link class="nav-link" to="servicio">
                                    <span>Servicios</span>
                                </router-link>
                            </li>
                            <li>
                                <router-link class="nav-link" to="cliente">
                                    <span>Clientes</span>
                                </router-link>
                            </li>
                            <li>
                                <router-link class="nav-link" to="concepto">
                                    <span>Conceptos</span>
                                </router-link>
                            </li>
                            <li>
                                <router-link class="nav-link" to="cuenta">
                                    <span>Cuentas</span>
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
export default {
    mounted() {
    },
}
</script>