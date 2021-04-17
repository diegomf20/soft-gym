<template>
    <div>
        <nav>
            <h1 class="card-title">Otros Reportes</h1>
            <!-- <h1 class="card-comment mb-0">Control de Stock</h1> -->
        </nav>
        <div class="card">
            <div class="card-header">
                <h5>Reporte Anuales</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-2 form-group">
                        <label>Seleccionar Año:</label>
                    </div>
                    <div class="col-sm-2 form-group">
                        <input type="number" v-model="year" class="form-control">
                    </div>
                </div>
                <a :href="excel_balance()" class="btn btn-success">
                    <i class="far fa-file-excel"></i> Balance Anual
                </a>    
                <a :href="excel_producto()" class="btn btn-success">
                    <i class="far fa-file-excel"></i> Salida Productos Anual
                </a>    
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            year: moment().format('Y')
        }
    },
    methods: {
        excel_balance(){
            return `${url_base}/reportes/balance_anual?year=${this.year}`;
        },
        excel_producto(){
            return `${url_base}/reportes/producto_anual?year=${this.year}`;
        }
    },
}
</script>