<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">MEMBRESIAS ACTIVAS</h1>
            <!-- <h1 class="card-comment mb-0">Control de Stock</h1> -->
        </nav>
        <div class="row">
            <div class="col-sm-6 col-lg-4 mb-3">
                <input class="form-control" placeholder="search" v-model="search" @keyup="listar()">
            </div>
        </div>
        <div class="table table-responsive">
            <div class="table-header">
                <div class="row">
                    <div class="col-3">Cliente</div>
                    <div class="col-4">Producto</div>
                    <div class="col-3">Rango de Fechas</div>
                    <div class="col-2">Quedan</div>
                </div>
            </div>
            <div    
                v-for="item in membresias"
                :key="item.dni" 
                class="table-row" 
                :class="item.vencimiento<0 ? 'text-danger':''">
                <div class="row">
                    <div class="col-lg-3"><label for="">Cliente</label>{{ item.descripcion_cliente }}</div>
                    <div class="col-lg-4"><label for="">Producto</label>{{ item.descripcion_producto }}</div>
                    <div class="col-lg-3">
                        <label for="">Rango de Fechas</label>{{ `${formatearFecha(item.fecha_inicio)} - ${formatearFecha(item.fecha_fin)}` }}
                    </div>
                    <div class="col-lg-2"><label for="">Quedan</label>{{ item.vencimiento }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import TablePaginate from '../components/table-paginate'
export default {
    components: { TablePaginate },
    data() {
        return {
            membresias: [],
            search: ''
        }
    },
    mounted() {
        this.listar();
    },
    methods: {
        formatearFecha(value){
            return moment(value).format('DD/MM/Y')
        },
        listar(){
            axios.get(`${url_base}/reportes/membresias?search=${this.search}`).then((params)=> {
                this.membresias=params.data
            }); 
        }
    },
}
</script>