<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Lista de Ingresos</h1>
        </nav>
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6 col-lg-8">
                        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-nuevo">
                            Nuevo
                        </button> -->
                    </div>
                    <!-- <div class="col-sm-6 col-lg-4">
                        <input class="form-control" placeholder="search">
                    </div> -->
                </div>
                <div class="table">
                    <div class="table-header">
                        <div class="col-2">Fecha</div>
                        <div class="col-2">DNI</div>
                        <div class="col-3">Nombres y Apellido</div>
                        <div class="col-2">Descuento</div>
                        <div class="col-2">Total</div>
                        <div class="col-1">Anular</div>
                    </div>
                    <div v-for="item in ingresos.data" 
                        :key="item.id"
                        class="table-row" 
                        :class=" item.estado=='I' ? 'text-danger' : ''">
                        <div class="col-2">{{ item.fecha }}</div>
                        <div class="col-2">{{ item.dni }}</div>
                        <div class="col-3">{{ item.descripcion_cliente }}</div>
                        <div class="col-2">{{ item.descuento}}</div>
                        <div class="col-2">{{ item.total }}</div>
                        <div class="col-1 text-center">
                            <a  v-if="item.estado=='A'"
                                @click="eliminar(item.id)" 
                                type="button" 
                                class="text-danger"
                                >
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <table-paginate 
                    :current_page="ingresos.current_page" 
                    :last_page="ingresos.last_page"
                    :paginateGet="listar"
                ></table-paginate>
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
            ingresos: []
        }
    },
    mounted() {
        this.listar();
    },
    methods: {
        listar(n=1){
            axios.get(`${url_base}/ingreso?page=${n}`).then((params)=> {
                this.ingresos=params.data
            }); 
        },
        eliminar(id){
            axios.post(`${url_base}/ingreso/${id}`,{
                _method: 'delete'
            }).then((params)=> {
                this.listar();
                // this.ingresos=params.data
            }); 
        }
    },
}
</script>