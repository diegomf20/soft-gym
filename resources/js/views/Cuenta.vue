<template>
    <div class="container-fluid">
        <!-- Modal -->
        <div class="modal fade" id="modal-nuevo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-nuevoLabel">Nuevo Cuenta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Descripción:</label>
                            <input type="text" class="form-control" v-model="cuenta.descripcion">
                            <span class="text-danger">{{ error_cuenta.descripcion }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="save()" type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-editar" tabindex="-1" role="dialog" aria-labelledby="modal-EditarLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-EditarLabel">Editar Cuenta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Descripción:</label>
                            <input type="text" class="form-control" v-model="cuenta_editar.descripcion">
                            <span class="text-danger">{{ error_cuenta_editar.descripcion }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="update()" type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <nav>
            <h1 class="card-title">CUENTA</h1>
            <!-- <h1 class="card-comment mb-0">Control de Stock</h1> -->
        </nav>
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6 col-lg-8">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-nuevo">
                            Nuevo
                        </button>
                    </div>
                    <!-- <div class="col-sm-6 col-lg-4">
                        <input class="form-control" placeholder="search">
                    </div> -->
                </div>
                <div class="table">
                    <div class="table-header">
                        <div class="row">
                            <div class="col-2">N°</div>
                            <div class="col-6">Descripción</div>
                            <div class="col-3 text-center">Editar</div>
                        </div>
                    </div>
                    <div v-for="(item,index) in cuentas.data" class="table-row">
                        <div class="row">
                            <div class="col-2">{{ index+1 }}</div>
                            <div class="col-6">{{ item.descripcion }}</div>
                            <div class="col-3 text-center">
                                <a @click="getcuenta(item.id)" type="button" class="text-primary"><i class="fas fa-pen"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <table-paginate 
                    :current_page="cuentas.current_page" 
                    :last_page="cuentas.last_page"
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
            cuentas: [],
            cuenta: this.initCuenta(),
            error_cuenta: this.initValidate(),
            cuenta_editar: this.initCuenta(),
            error_cuenta_editar: this.initValidate(),
        }
    },
    mounted() {
        this.listar();
    },
    methods: {
        initCuenta(){
            return {
                descripcion: ''
            }
        },
        initValidate(){
            return {
                descripcion: ''
            }
        },
        listar(n=1){
            axios.get(`${url_base}/cuenta?tipo=S&page=${n}`).then((params)=> {
                this.cuentas=params.data
            }); 
        },
        save(){
            axios.post(`${url_base}/cuenta`,this.cuenta)
            .then((params)=> {
                var respuesta=params.data;
                switch (respuesta.status) {
                    case "OK":
                        swal({
                            text: respuesta.message,
                            icon: "success"
                        });
                        $('#modal-nuevo').modal('hide')
                        this.cuenta=this.initCuenta();
                        break;
                
                    default:
                        break;
                }
                this.listar();
            }).catch((error)=>{
                var response=error.response;
                if (response.status==422) {
                    var errors=response.data.errors;
                    for(var i in errors){
                        errors[i]=errors[i][0];
                    }
                    this.error_cuenta=errors
                }
            });
        },
        getcuenta(id){
            axios.get(`${url_base}/cuenta/${id}`)
            .then((params)=>{
                this.cuenta_editar=params.data;
                $('#modal-editar').modal();
            });
        },
        update(){
            axios.post(`${url_base}/cuenta/${this.cuenta_editar.id}?_method=PUT`,this.cuenta_editar)
            .then((params)=> {
                var respuesta=params.data;
                switch (respuesta.status) {
                    case "OK":
                        swal({
                            text: respuesta.message,
                            icon: "success"
                        });
                        this.cuenta_editar=this.initCuenta();
                        $('#modal-editar').modal('hide')
                        break;
                
                    default:
                        break;
                }
                this.listar();
            }).catch((error)=>{
                var response=error.response;
                if (response.status==422) {
                    var errors=response.data.errors;
                    for(var i in errors){
                        errors[i]=errors[i][0];
                    }
                    this.error_cuenta_editar=errors
                }
            });
        }
    },
}
</script>