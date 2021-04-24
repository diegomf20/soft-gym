<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Nuevo Ingreso</h1>
        </nav>
        <div class="row">
            <div class="col-sm-4 col-lg-2 form-group">
                <label for="">Fecha:</label>
                <input class="form-control" type="date" v-model="venta.fecha">
            </div>
            <div class="col-sm-8 col-lg-5 form-group">
                <label for="">Nombres y apellidos ( <input 
                                        type="checkbox" 
                                        id="checkbox" 
                                        v-model="anonimo" 
                                        @change=change_anonimo> Anonimo ): </label>
                <v-select 
                        :disabled="anonimo"
                        :reduce="cliente => cliente.id"
                        label="nombres"
                        :filterable="false" 
                        :options="clientes"
                        @search="getCliente"
                        v-model="venta.cliente_id">
                    <template slot="no-options">
                        Buscando cliente ...
                    </template>
                    <template slot="option" slot-scope="option">
                        {{ `${option.nombres} ${option.ape_paterno} ${option.ape_materno}` }}
                    </template>
                    <template slot="selected-option" slot-scope="option">
                        {{ `${option.nombres} ${option.ape_paterno} ${option.ape_materno}` }}
                    </template>
                </v-select>
                <span class="text-danger">{{ error_ingreso.cliente_id }}</span>
            </div>
            <div class="col-sm-6 col-lg-2 form-group">
                <label for="">Cuenta</label>
                <select class="form-control" v-model="venta.cuenta_id">
                    <option v-for="cuenta in cuentas" :value="cuenta.id">{{ cuenta.descripcion }}</option>
                </select>
            </div>
            <div class="col-sm-6 col-lg-3 form-group">
                <label for="">Referencia (**)</label>
                <input type="text" class="form-control">
            </div>
        </div>
        <div class="table table-responsive">
            <div class="table-header">
                <div class="row">
                    <div class="col-1 text-center">Quitar</div>
                    <div class="col-1 text-center">Buscar</div>
                    <div class="col-10">
                        <div class="row">
                            <div class="col-6">Descripción</div>
                            <div class="col-2">Cantidad</div>
                            <div class="col-2">Monto</div>
                            <div class="col-2">Sub Total</div>
                        </div>
                    </div>
                </div>
            </div>
            <div 
                v-for="(item,index) in venta.items" 
                :key="item.id"
                class="table-row">
                <div class="row">
                    <div class="col-3 col-lg-2 text-center">
                        <div class="row">
                            <div class="col-lg-6 mb-sm">
                                <a @click="removeItem(index)" type="button" class="btn btn-danger">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <button class="btn btn-info" @click="buscarProducto(index)">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-9 col-lg-10">
                        <div class="row">
                            <div class="col-lg-6 mb-sm">
                                {{ (item.tipo=='P') ? 'Producto' : (item.tipo=='S'?'Servicio':'') }} - {{ item.descripcion_producto }}
                                <div v-if="item.tipo=='S'">
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <p class="mb-0">Fecha Inicio:</p>
                                        </div>
                                        <div class="col-lg-8">
                                            <input  class="form-control form-control-sm" type="date" v-model="item.fecha_inicio">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 col-lg-2">
                                <label for="">Cantidad:</label>
                                <input type="number" class="form-control form-control-sm" v-model="item.cantidad">
                            </div>
                            <div class="col-4 col-lg-2">
                                <label for="">Monto</label>
                                {{ Number(item.monto).toFixed(2) }}</div>
                            <div class="col-4 col-lg-2">
                                <label for="">Subtotal</label>
                                {{(item.cantidad*item.monto).toFixed(2)}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-row" v-if="venta.items.length==0">
                <div class="col-12 text-center">
                    Sin Items de ingreso
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 form-group">
                <button class="btn btn-primary" @click="addItem">+ Agregar Item</button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8"></div>
            <div class="col-sm-4">
                <div class="row text-right">
                    <div class="col-6 mb-3">
                        <label for="">Descuento:</label>
                    </div>
                    <div class="col-6 mb-3">
                        <input type="text" class="form-control" v-model="venta.descuento" placeholder="Descuento">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="">Total:</label>
                    </div>
                    <div class="col-6 mb-3">
                        {{(totalVenta-venta.descuento).toFixed(2)}}
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" @click="save()">GUARDAR</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-buscar" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-nuevoLabel">Buscar Producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control mb-3" placeholder="search" v-model="search" @keyup="listarProductos()">
                        <table class="table">
                            <tr v-for="producto in productos.data">
                                <td>{{ producto.descripcion }}</td>
                                <td>{{ producto.tipo }}</td>
                                <td>{{ Number(producto.precio).toFixed(2) }}</td>
                                <td>
                                    <button class="btn btn-primary" @click="seleccionar(producto)">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState,mapMutations } from 'vuex'

export default {
    data() {
        return {
            cuentas: [],
            clientes: [],
            venta: this.initVenta(), 
            index: 0,
            productos: [],
            error_ingreso:{},
            search: '',
            anonimo: false
        }
    },
    mounted() {
        this.listarCuentas();
    },
    computed: {
        ...mapState(['user_sistema']),
        totalVenta(){
            var total=0;
            for (let i = 0; i < this.venta.items.length; i++) {
                const item = this.venta.items[i];
                total+=item.cantidad*item.monto;
            }
            return total;
        }
    },
    methods: {
        change_anonimo(){
            if(this.anonimo){
                this.venta.cliente_id=null;
            }
        },
        getCliente(search){
            if (search!='') {
                axios.get(`${url_base}/cliente?type=all&search=${search}`)
                .then((params)=>{
                    this.clientes= params.data;
                });
            }
        },
        listarCuentas(){
            axios.get(`${url_base}/cuenta?all`).then((params)=> {
                this.cuentas=params.data
            }); 
        },
        addItem(){
            this.venta.items.push({
                producto_id: '',
                codigo: '',
                descripcion_producto: '',
                cantidad: 1,
                monto: 0,
                fecha_inicio: moment().format('Y-MM-DD')
            });
        },
        removeItem(index){
            this.venta.items.splice(index, 1);
        },
        buscarProducto(index){
            $('#modal-buscar').modal();
            this.index=index;
            // this.listarProductos();
        },
        listarProductos(){
            axios.get(`${url_base}/producto?search=${this.search}`).then((params)=> {
                this.productos=params.data
            }); 
        },
        seleccionar(producto){
            $('#modal-buscar').modal('hide');
            this.venta.items[this.index].producto_id=producto.id;
            this.venta.items[this.index].descripcion_producto=producto.descripcion;
            this.venta.items[this.index].tipo=producto.tipo;
            this.venta.items[this.index].monto=producto.precio;
            this.search='';
        },
        save(){
            if(!this.anonimo&&(this.venta.cliente_id===null||this.venta.cliente_id==='')){
                swal({
                    text: 'Seleccione Cliente',
                    icon: "warning"
                });
            }else{
                this.error_ingreso={};
                axios.post(`${url_base}/ingreso`,this.venta)
                .then((params)=> {
                    var respuesta=params.data;
                    switch (respuesta.status) {
                        case "OK":
                            swal({
                                text: respuesta.message,
                                icon: "success"
                            });
                            this.venta=this.initVenta();
                            break;
                        case "WARNING":
                            swal({
                                text: respuesta.message,
                                icon: "warning"
                            });
                            break;

                        default:
                            break;
                    }
                }).catch((error)=>{
                    var response=error.response;
                    if (response.status==422) {
                        var errors=response.data.errors;
                        for(var i in errors){
                            errors[i]=errors[i][0];
                        }
                        this.error_ingreso=errors
                    }
                });
            }
        },
        initVenta(){
            return {
                cliente_id: '',
                dni: '',
                descripcion_cliente: '',
                items:[],
                descuento: 0,
                cuenta_id: 1,
                fecha: moment().format('Y-MM-DD')
            }
        }
    },
}
</script>