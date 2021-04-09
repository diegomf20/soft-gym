<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Lista de Ingresos</h1>
        </nav>
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6 col-lg-8">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-nuevo">
                            Nuevo
                        </button>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <input class="form-control" placeholder="search">
                    </div>
                </div>
                <div class="table">
                    <div class="table-header">
                        <div class="col-2">DNI</div>
                        <div class="col-4">Nombres y Apellido</div>
                        <div class="col-2">Teléfono</div>
                        <div class="col-3">Email</div>
                        <div class="col-1">Editar</div>
                    </div>
                    <div v-for="item in ingresos.data" class="table-row">
                        <div class="col-2">{{ item.dni }}</div>
                        <div class="col-4">{{ item.nombres + ' ' + item.ape_paterno+ ' ' + item.ape_materno }}</div>
                        <div class="col-2">{{ item.telefono }}</div>
                        <div class="col-3">{{ item.email}}</div>
                        <div class="col-1">
                            <a @click="getCliente(item.dni)" type="button" class="text-primary"><i class="fas fa-pen"></i></a>
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
        }
    },
}
</script>