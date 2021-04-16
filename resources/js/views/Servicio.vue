<template>
    <div class="container-fluid">
        <!-- Modal -->
        <div class="modal fade" id="modal-nuevo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-nuevoLabel">Nuevo Servicio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Descripción:</label>
                            <input type="text" class="form-control" v-model="producto.descripcion">
                            <span class="text-danger">{{ error_producto.descripcion }}</span>
                        </div>
                        <div class="form-group">
                            <label for="">Pago:</label>
                            <select class="form-control" v-model="producto.pago">
                                <option value="U">Único</option>
                                <option value="M">Mensual</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Precio de Venta:</label>
                            <vue-numeric-input  v-model.number="producto.precio" :min="0" :step="2" :controls="false" class="form-control"></vue-numeric-input>
                            <span class="text-danger">{{ error_producto.precio }}</span>
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
                        <h5 class="modal-title" id="modal-EditarLabel">Editar Servicio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Descripción:</label>
                            <input type="text" class="form-control" v-model="producto_editar.descripcion">
                            <span class="text-danger">{{ error_producto_editar.descripcion }}</span>
                        </div>
                        <div class="form-group">
                            <label for="">Pago:</label>
                            <select class="form-control" v-model="producto_editar.pago">
                                <option value="U">Único</option>
                                <option value="M">Mensual</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Precio de Venta:</label>
                            <!-- <input type="text" class="form-control"  v-model.number="producto_editar.precio"> -->
                            <vue-numeric-input  v-model="producto_editar.precio" :min="0" :step="2" :controls="false" class="form-control"></vue-numeric-input>
                            <span class="text-danger">{{ error_producto_editar.precio }}</span>
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
            <h1 class="card-title">SERVICIO</h1>
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
                    <div class="col-sm-6 col-lg-4">
                        <input class="form-control" placeholder="search">
                    </div>
                </div>
                <div class="table">
                    <div class="table-header">
                        <div class="row">
                            <div class="col-2">Codigo</div>
                            <div class="col-5">Descripción</div>
                            <div class="col-2">Pago</div>
                            <div class="col-2">Precio Venta</div>
                            <div class="col-1">Editar</div>
                        </div>
                    </div>
                    <div v-for="item in productos.data" class="table-row">
                        <div class="row">
                            <div class="col-2">{{ item.codigo }}</div>
                            <div class="col-5">{{ item.descripcion }}</div>
                            <div class="col-2">{{ (item.pago=='U')?'Único':'Mensual' }}</div>
                            <div class="col-2">{{ item.precio}}</div>
                            <div class="col-1">
                                <a @click="getProducto(item.id)" type="button" class="text-primary"><i class="fas fa-pen"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <table-paginate 
                    :current_page="productos.current_page" 
                    :last_page="productos.last_page"
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
            productos: [],
            producto: this.initProducto(),
            error_producto: this.initValidate(),
            producto_editar: this.initProducto(),
            error_producto_editar: this.initValidate(),
        }
    },
    mounted() {
        this.listar();
    },
    methods: {
        initProducto(){
            return {
                tipo: 'S',
                descripcion: '',
                precio: 0,
                pago: 'U'
            }
        },
        initValidate(){
            return {
                descripcion: '',
                precio: ''
            }
        },
        listar(n=1){
            axios.get(`${url_base}/producto?tipo=S&page=${n}`).then((params)=> {
                this.productos=params.data
            }); 
        },
        save(){
            axios.post(`${url_base}/producto`,this.producto)
            .then((params)=> {
                var respuesta=params.data;
                switch (respuesta.status) {
                    case "OK":
                        swal({
                            text: respuesta.message,
                            icon: "success"
                        });
                        $('#modal-nuevo').modal('hide')
                        this.producto=this.initProducto();
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
                    this.error_producto=errors
                }
            });
        },
        getProducto(id){
            axios.get(`${url_base}/producto/${id}`)
            .then((params)=>{
                this.producto_editar=params.data;
                $('#modal-editar').modal();
            });
        },
        update(){
            axios.post(`${url_base}/producto/${this.producto_editar.id}?_method=PUT`,this.producto_editar)
            .then((params)=> {
                var respuesta=params.data;
                switch (respuesta.status) {
                    case "OK":
                        swal({
                            text: respuesta.message,
                            icon: "success"
                        });
                        this.producto_editar=this.initProducto();
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
                    this.error_producto_editar=errors
                }
            });
        }
    },
}
</script>   