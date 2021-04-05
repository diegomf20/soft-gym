<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Bienvenido</h1>
            <h1 class="card-comment mb-0">Resumen de General</h1>
        </nav>
        <div class="row">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <h1 style="font-size=24px" class="text-right">
                                    <i class="far fa-calendar-check"></i>
                                </h1>
                            </div>
                            <div class="col-8">
                                <h4 class="text-left">{{ indicadores.menbresias }}</h4>
                                <p>Menbresias Activas</p>
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