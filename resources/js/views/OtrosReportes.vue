<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Otros Reportes</h1>
            <!-- <h1 class="card-comment mb-0">Control de Stock</h1> -->
        </nav>
        <div class="card mb-3">
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
                <a :href="excel_balance()" class="btn btn-success mb-sm">
                    <i class="far fa-file-excel"></i> Balance Anual
                </a>    
                <a :href="excel_producto()" class="btn btn-success mb-sm">
                    <i class="far fa-file-excel"></i> Salida Productos Anual
                </a>    
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Reporte Diario
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4 col-sm-6 col-lg-3 form-group">
                        <label>Seleccionar Día:</label>
                    </div>
                    <div class="col-8 col-sm-6 col-lg-2 form-group">
                        <input type="date" v-model="day" class="form-control">
                    </div>
                    
                </div>
                <button @click="get_producto_diario()" class="btn btn-success mb-3">
                    <i class="far fa-file-excel"></i> Producto
                </button>
                <div class="row">
                    <div class="col-lg-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Descripcion</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="producto in producto_diario">
                                    <td>{{ producto.codigo }}</td>
                                    <td>{{ producto.descripcion }}</td>
                                    <td>{{ producto.total }}</td>
                                </tr>
                            </tbody>
                        </table>
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
            year: moment().format('Y'),
            day: moment().format('Y-MM-DD'),
            producto_diario: []
        }
    },
    methods: {
        excel_balance(){
            return `${url_base}/reportes/balance_anual?year=${this.year}`;
        },
        excel_producto(){
            return `${url_base}/reportes/producto_anual?year=${this.year}`;
        },
        get_producto_diario(){
            axios.get(`${url_base}/reportes/producto_diario?day=${this.day}`).then((params)=> {
                this.producto_diario=params.data
            }); 
        }

    },
}
</script>