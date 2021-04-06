<template>
    <div class="wrapper lite">
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="logo.png">
            </div>
            <div class="sidebar-content">
                <ul class="nav-list">
                    <li class="">
                        <router-link class="nav-link" to="./">
                            <div class="nav-icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <span>Dashboard</span>
                        </router-link>
                    </li>
                    <li class="">
                        <router-link class="nav-link" to="ingreso">
                            <div class="nav-icon">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <span>Ingresos</span>
                        </router-link>
                    </li>
                    <li class="">
                        <router-link class="nav-link" to="egreso">
                            <div class="nav-icon">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <span>Egresos</span>
                        </router-link>
                    </li>
                    <li class="">
                        <router-link class="nav-link" to="producto">
                            <div class="nav-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <span>Productos</span>
                        </router-link>
                    </li>
                    <li class="">
                        <router-link class="nav-link" to="servicio">
                            <div class="nav-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <span>Servicios</span>
                        </router-link>
                    </li>
                    <li class="">
                        <router-link class="nav-link" to="cliente">
                            <div class="nav-icon">
                                <i class="far fa-address-book"></i>
                            </div>
                            <span>Clientes</span>
                        </router-link>
                    </li>
                    <li class="">
                        <router-link class="nav-link" to="cuenta">
                            <div class="nav-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <span>Cuentas</span>
                        </router-link>
                    </li>
                    <li class="">
                        <router-link class="nav-link" to="concepto">
                            <div class="nav-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <span>Conceptos</span>
                        </router-link>
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