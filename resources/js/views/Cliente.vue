<template>
    <div class="container-fluid">
        <!-- Modal -->
        <div class="modal fade" id="modal-nuevo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-nuevoLabel">Nuevo cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">DNI:</label>
                            <input type="text" class="form-control" v-model="cliente.dni">
                            <span class="text-danger">{{ error_cliente.dni }}</span>
                        </div>
                        <div class="form-group">
                            <label for="">Nombres:</label>
                            <input type="text" class="form-control" v-model="cliente.nombres">
                            <span class="text-danger">{{ error_cliente.nombres }}</span>
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="">Apellido Paterno:</label>
                                <input type="text" class="form-control" v-model="cliente.ape_paterno">
                                <span class="text-danger">{{ error_cliente.ape_paterno }}</span>
                            </div>
                            <div class="col-6 form-group">
                                <label for="">Apellido Materno:</label>
                                <input type="text" class="form-control" v-model="cliente.ape_materno">
                                <span class="text-danger">{{ error_cliente.ape_materno }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="">Teléfono:</label>
                                <input type="text" class="form-control" v-model="cliente.telefono">
                            </div>
                            <div class="col-6 form-group">
                                <label for="">Fecha Nacimiento:</label>
                                <input type="date" class="form-control" v-model="cliente.fecha_nacimiento">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">E-mail:</label>
                            <input type="text" class="form-control" v-model="cliente.email">
                        </div>
                        <div>
                            <label for="">Dirección:</label>
                            <input type="text" class="form-control" v-model="cliente.direccion">
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
                        <h5 class="modal-title" id="modal-EditarLabel">Editar cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nombres:</label>
                            <input type="text" class="form-control" v-model="cliente_editar.nombres">
                            <span class="text-danger">{{ error_cliente_editar.nombres }}</span>
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="">Apellido Paterno:</label>
                                <input type="text" class="form-control" v-model="cliente_editar.ape_paterno">
                                <span class="text-danger">{{ error_cliente_editar.ape_paterno }}</span>
                            </div>
                            <div class="col-6 form-group">
                                <label for="">Apellido Materno:</label>
                                <input type="text" class="form-control" v-model="cliente_editar.ape_materno">
                                <span class="text-danger">{{ error_cliente_editar.ape_materno }}</span>
                            </div>
                            <div class="col-6 form-group">
                                <label for="">Teléfono:</label>
                                <input type="text" class="form-control" v-model="cliente_editar.telefono">
                            </div>
                            <div class="col-6 form-group">
                                <label for="">Fecha Nacimiento:</label>
                                <input type="date" class="form-control" v-model="cliente_editar.fecha_nacimiento">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">E-mail:</label>
                            <input type="text" class="form-control" v-model="cliente_editar.email">
                        </div>
                        <div class="form-group">
                            <label for="">Dirección:</label>
                            <input type="text" class="form-control" v-model="cliente_editar.direccion">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="update()" type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-historial" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Historial Membresias</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Descripcion</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in historial">
                                    <td>{{ item.descripcion }}</td>
                                    <td>{{ item.fecha_inicio }}</td>
                                    <td>{{ item.fecha_fin }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <nav>
            <h1 class="card-title">CLIENTE</h1>
            <!-- <h1 class="card-comment mb-0">Control de Stock</h1> -->
        </nav>
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6 col-lg-8">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-nuevo">
                            Nuevo
                        </button>
                        <a class="btn btn-success" :href="excel()">
                            <i class="far fa-file-excel"></i> Exportar
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <input class="form-control" placeholder="search" v-model="search" @keyup="listar()">
                    </div>
                </div>
                <div class="table">
                    <div class="table-header">
                        <div class="row">
                            <div class="col-2">DNI</div>
                            <div class="col-3">Nombres y Apellido</div>
                            <div class="col-2">Teléfono</div>
                            <div class="col-3">Email</div>
                            <div class="col-1">Editar</div>
                            <div class="col-1">Historial</div>
                        </div>
                    </div>
                    <div v-for="item in clientes.data" class="table-row">
                        <div class="row">
                            <div class="col-2">{{ item.dni }}</div>
                            <div class="col-3">{{ item.nombres + ' ' + item.ape_paterno+ ' ' + item.ape_materno }}</div>
                            <div class="col-2">{{ item.telefono }}</div>
                            <div class="col-3">{{ item.email}}</div>
                            <div class="col-1">
                                <a @click="getCliente(item.dni)" type="button" class="text-primary"><i class="fas fa-pen"></i></a>
                            </div>
                            <div class="col-1">
                                <a @click="listarHistorial(item.id)" type="button" class="text-info"><i class="fas fa-history"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <table-paginate 
                    :current_page="clientes.current_page" 
                    :last_page="clientes.last_page"
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
            clientes: [],
            historial:[],
            cliente: this.initCliente(),
            error_cliente: this.initValidate(),
            cliente_editar: this.initCliente(),
            error_cliente_editar: this.initValidate(),
            search: ''
        }
    },
    mounted() {
        this.listar();
    },
    methods: {
        initCliente(){
            return {
                dni: '',
                nombres: '',
                ape_paterno: '',
                ape_materno: '',
                telefono: '',
                email: '',
                fecha_nacimiento: '',
            }
        },
        initValidate(){
            return {
                dni: '',
                nombres: '',
                ape_paterno: '',
                ape_materno: ''
            }
        },
        listar(n=1){
            axios.get(`${url_base}/cliente?page=${n}&search=${this.search}`).then((params)=> {
                this.clientes=params.data
            }); 
        },
        save(){
            axios.post(`${url_base}/cliente`,this.cliente)
            .then((params)=> {
                var respuesta=params.data;
                switch (respuesta.status) {
                    case "OK":
                        swal({
                            text: respuesta.message,
                            icon: "success"
                        });
                        $('#modal-nuevo').modal('hide')
                        this.cliente=this.initCliente();
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
                    this.error_cliente=errors
                }
            });
        },
        getCliente(dni){
            axios.get(`${url_base}/cliente/${dni}`)
            .then((params)=>{
                this.cliente_editar=params.data;
                $('#modal-editar').modal();
            });
        },
        update(){
            axios.post(`${url_base}/cliente/${this.cliente_editar.dni}?_method=PUT`,this.cliente_editar)
            .then((params)=> {
                var respuesta=params.data;
                switch (respuesta.status) {
                    case "OK":
                        swal({
                            text: respuesta.message,
                            icon: "success"
                        });
                        this.cliente_editar=this.initCliente();
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
                    this.error_cliente_editar=errors
                }
            });
        },
        excel(){
            return `${url_base}/cliente?type=excel`
        },
        listarHistorial(id){
            axios.get(`${url_base}/cliente/${id}/historial`)
            .then((params)=>{
                $('#modal-historial').modal();
                this.historial=params.data;
            });
        }
    },
}
</script>