<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Bienvenido</h1>
            <h1 class="card-comment mb-0">Resumen de General</h1>
        </nav>
        <div class="row">
            <div class="col-md-3">
                <div class="card card-resumen">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <h1 style="font-size=24px" class="text-right">
                                    <i class="far fa-calendar-check"></i>
                                </h1>
                            </div>
                            <div class="col-8">
                                <h4 class="text-left">{{ indicadores.menbresias }}</h4>
                                <p class="mb-0">Membresias Activas</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        Ver Lista
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
@media (max-width: 768px){
    .sidebar{
        width: 50px;
    }
    .sidebar .sidebar-header{
        height: 50px;
        padding: 0;
    }
    .content{
        width: calc(100% - 50px);
    }
}
.card-resumen i{
    font-size: 60px;
}
    /* .bg-primary{
        background-color: var(--primary)!important;
    } */
</style>
<script>
export default {
    data() {
        return {
            indicadores: {
                menbresias: 0
            }
        }
    },
}
</script>