<template>
    <div class="container-fluid">
        <!-- Modal -->
        <div class="modal fade" id="modal-nuevo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-nuevoLabel">Nuevo Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nombres:</label>
                            <input type="text" class="form-control" v-model="user.nombres">
                            <span class="text-danger">{{ error_user.nombres }}</span>
                        </div>
                        <div class="form-group">
                            <label for="">Apellido Paterno:</label>
                            <input type="text" class="form-control" v-model="user.ape_paterno">
                            <span class="text-danger">{{ error_user.ape_paterno }}</span>
                        </div>
                        <div class="form-group">
                            <label for="">Apellido Materno:</label>
                            <input type="text" class="form-control" v-model="user.ape_materno">
                            <span class="text-danger">{{ error_user.ape_materno }}</span>
                        </div>
                        <div class="form-group">
                            <label for="">Usuario:</label>
                            <input type="text" class="form-control" v-model="user.usuario">
                            <span class="text-danger">{{ error_user.usuario }}</span>
                        </div>
                        <div class="form-group">
                            <label for="">Contraseña:</label>
                            <input type="text" class="form-control" v-model="user.contrasenia">
                            <span class="text-danger">{{ error_user.contrasenia }}</span>
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
                        <h5 class="modal-title" id="modal-EditarLabel">Editar user</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nombres:</label>
                            <input type="text" class="form-control" v-model="user_editar.nombres">
                            <span class="text-danger">{{ error_user_editar.nombres }}</span>
                        </div>
                        <div class="form-group">
                            <label for="">Apellido Paterno:</label>
                            <input type="text" class="form-control" v-model="user_editar.ape_paterno">
                            <span class="text-danger">{{ error_user_editar.ape_paterno }}</span>
                        </div>
                        <div class="form-group">
                            <label for="">Apellido Materno:</label>
                            <input type="text" class="form-control" v-model="user_editar.ape_materno">
                            <span class="text-danger">{{ error_user_editar.ape_materno }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="update()" type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-privilegios" tabindex="-1" role="dialog" aria-labelledby="modal-moduloLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-privilegiosLabel">Privilegios</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-for="modulo in modulos">
                            <input  type="checkbox" :value="modulo.id" v-model="privilegios.modulos">
                            <label>{{modulo.titulo}}</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="updatePrivilegios()" type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-reset" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Resetear Contraseña</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Usuario: {{ contrasenia.usuario }}</label>
                        </div>
                        <div class="form-group">
                            <label for="">Contraseña Nueva:</label>
                            <input type="text" class="form-control" v-model="contrasenia.nueva">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="reset()" type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <nav>
            <h1 class="card-title">USUARIOS</h1>
            <!-- <h1 class="card-comment mb-0">Control de Stock</h1> -->
        </nav>
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="form-group col-sm-6 col-lg-8">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-nuevo">
                            Nuevo
                        </button>
                    </div>
                    <div class="form-group col-sm-6 col-lg-4">
                        <input class="form-control" placeholder="search" v-model="search" @keyup="listar()">
                    </div>
                </div>
                <div class="table table-responsive">
                    <div class="table-header">
                        <div class="row">
                            <div class="col-2">Usuario</div>
                            <div class="col-4">Nombres y Apellido</div>
                            <div class="col-2">Editar</div>
                            <div class="col-2">Privilegios</div>
                            <div class="col-2">Reset</div>
                        </div>
                    </div>
                    <div v-for="item in users.data" class="table-row">
                        <div class="row">
                            <div class="col-lg-2"><label>Usuario:</label>{{ item.usuario }}</div>
                            <div class="col-lg-4"><label>Descripción:</label>{{ item.nombres + ' ' + item.ape_paterno+ ' ' + item.ape_materno }}</div>
                            <div class="col-lg-2"><label>Editar:</label>
                                <a @click="getuser(item.id)" type="button" class="text-primary"><i class="fas fa-pen"></i></a>
                            </div>
                            <div class="col-lg-2"><label>Privilegios:</label>
                                <a @click="getPrivilegios(item.id)" type="button" class="text-info"><i class="fas fa-user-lock"></i></a>
                            </div>
                            <div class="col-lg-2"><label>Resetear:</label>
                                <a @click="abrirReset(item.usuario)" type="button" class="text-info"><i class="fas fa-key"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <table-paginate 
                    :current_page="users.current_page" 
                    :last_page="users.last_page"
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
            users: [],
            user: this.inituser(),
            user_editar: this.inituser(),
            error_user: this.initValidate(),
            error_user_editar: this.initValidate(),
            privilegios:{
                user_id: -1,
                modulos: [] 
            },
            modulos:[],
            search: '',
            contrasenia: {
                usuario: '',
                nueva:''
            }
        }
    },
    mounted() {
        this.listar();
        this.listarModulos();
    },
    methods: {
        inituser(){
            this.error_user=this.initValidate();
            this.error_user_editar=this.initValidate();
            return {
                nombres: '',
                ape_paterno: '',
                ape_materno: '',
                usuario: '',
                contrasenia: ''
            }
        },
        initValidate(){
            return {
                nombres: '',
                ape_paterno: '',
                ape_materno: '',
                usuario: '',
                contrasenia: ''
            }
        },
        listarModulos(){
            axios.get(`${url_base}/modulo`).then((params)=> {
                this.modulos=params.data
            }); 
        },
        listar(n=1){
            axios.get(`${url_base}/user?page=${n}&search=${this.search}`).then((params)=> {
                this.users=params.data
            }); 
        },
        save(){
            axios.post(`${url_base}/user`,this.user)
            .then((params)=> {
                var respuesta=params.data;
                switch (respuesta.status) {
                    case "OK":
                        swal({
                            text: respuesta.message,
                            icon: "success"
                        });
                        $('#modal-nuevo').modal('hide')
                        this.user=this.inituser();
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
                    this.error_user=errors
                }
            });
        },
        getuser(id){
            axios.get(`${url_base}/user/${id}`)
            .then((params)=>{
                this.user_editar=params.data;
                $('#modal-editar').modal();
            });
        },
        getPrivilegios(id){
            axios.get(`${url_base}/user/${id}/privilegios`)
            .then((params)=>{
                this.privilegios.modulos=[];
                for (let i = 0; i < params.data.length; i++) {
                    const modulo = params.data[i];
                    this.privilegios.modulos.push(modulo.modulo_id);                    
                }
                this.privilegios.user_id=id;
                $('#modal-privilegios').modal();
            });
        },
        updatePrivilegios(){
            axios.post(`${url_base}/user/${this.privilegios.user_id}/privilegios`,this.privilegios)
            .then((params)=>{
                $('#modal-privilegios').modal('hide');
            });
        },
        abrirReset(usuario){
            this.contrasenia.usuario=usuario;
            $('#modal-reset').modal();
        },
        reset(usuario){
            axios.post(`${url_base}/user/reset`,this.contrasenia)
            .then((params)=> {
                var respuesta=params.data;
                switch (respuesta.status) {
                    case "OK":
                        swal({
                            text: respuesta.message,
                            icon: "success"
                        });
                        $('#modal-reset').modal('hide');
                        this.contrasenia={
                            usuario: '',
                            nueva: ''
                        };
                        break;
                }
            })
        },
        update(){
            axios.post(`${url_base}/user/${this.user_editar.id}?_method=PUT`,this.user_editar)
            .then((params)=> {
                var respuesta=params.data;
                switch (respuesta.status) {
                    case "OK":
                        swal({
                            text: respuesta.message,
                            icon: "success"
                        });
                        this.user_editar=this.inituser();
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
                    this.error_user_editar=errors
                }
            });
        }
    },
}
</script>