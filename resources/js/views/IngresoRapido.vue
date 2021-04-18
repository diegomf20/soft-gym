<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Nuevo Ingreso</h1>
        </nav>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5>Datos Generales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <p>Fecha Inicio:</p>
                            </div>
                            <div class="col-8">
                                <input  class="form-control" type="date" v-model="venta.items[0].fecha_inicio">
                            </div>
                            <div class="form-group col-4">
                                <label for="">DNI o Código: </label>
                                <input @keyup="getCliente" type="text" class="form-control" v-model="venta.dni">
                                <span class="text-danger">{{ error_ingreso.cliente_id }}</span>
                            </div>
                            <div class="form-group col-12">
                                <label for="">Nombres y apellidos: </label>
                                <input type="text" class="form-control" v-model="venta.descripcion_cliente" disabled>
                            </div>
                            <div class="form-group col-6">
                                <label for="">Cuenta</label>
                                <select class="form-control" v-model="venta.cuenta_id">
                                    <option v-for="cuenta in cuentas" :value="cuenta.id">{{ cuenta.descripcion }}</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="">Referencia (**)</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5>Datos Servicio</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" v-model="venta.items[0].descripcion_producto" placeholder="Producto o Servicio" readonly>
                                    <button class="input-group-text btn-info" @click="buscarProducto(0)">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-4 form-group">
                                <label for="">Cantidad</label>
                                <input type="number" class="form-control" v-model="venta.items[0].cantidad">
                            </div>
                            <div class="col-4 form-group">
                                <label for="">Monto</label>
                                <input type="number" class="form-control" :value="Number(venta.items[0].monto).toFixed(2)" readonly>
                            </div>
                            <div class="col-4 form-group">
                                <label for="">Sub total</label>
                                <input type="number" class="form-control" :value="(item.cantidad*item.monto).toFixed(2)" readonly>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-info mt-4" @click="buscarProducto(0)">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div v-if="venta.items[0].tipo=='S'">
                            <hr>
                            <div class="row">
                                
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
        <div class="table">
            <div class="table-header">
                <div class="row">
                    <div class="col-1">Buscar</div>
                    <div class="col-1">Tipo</div>
                    <div class="col-4">Descripción</div>
                    <div class="col-1">Cantidad</div>
                    <div class="col-2">Monto</div>
                    <div class="col-2">Sub Total</div>
                    <div class="col-1">Quitar</div>
                </div>
            </div>
            <div 
                v-for="(item,index) in venta.items" 
                :key="item.id"
                class="table-row">
                <div class="row">
                    <div class="col-1">
                        
                    </div>
                    <div class="col-1">{{ (item.tipo=='P') ? 'Producto' : (item.tipo=='S'?'Servicio':'') }}</div>
                    <div class="col-4">
                        {{ item.descripcion_producto }}
                        <div v-if="item.tipo=='S'">
                            <hr>
                            <div class="row">
                                <div class="col-4">
                                    <p>Fecha Inicio:</p>
                                </div>
                                <div class="col-8">
                                    <input  class="form-control" type="date" v-model="item.fecha_inicio">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        
                    </div>
                    <div class="col-2">{{ Number(item.monto).toFixed(2) }}</div>
                    <div class="col-2">
                        {{(item.cantidad*item.monto).toFixed(2)}}
                    </div>
                    <div class="col-1">
                        <a @click="removeItem(index)" type="button" class="btn btn-danger">X</a>
                        <!-- <a @click="getcuenta(item.id)" type="button" class="text-primary"><i class="fas fa-pen"></i></a> -->
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
            <div class="col-sm-4">
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
            venta: this.initVenta(), 
            index: 0,
            productos: [],
            error_ingreso:{},
            search: ''
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
        getCliente(){
            if ((this.venta.dni+"").length>7) {
                axios.get(`${url_base}/cliente/${this.venta.dni}`)
                .then((params)=>{
                    var respuesta= params.data;
                    this.venta.cliente_id=respuesta.id;
                    this.venta.descripcion_cliente=`${respuesta.nombres} ${respuesta.ape_paterno} ${respuesta.ape_materno}`;
                });
            }else{
                this.venta.cliente_id=null;
                this.venta.descripcion_cliente='';
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
        },
        initVenta(){
            return {
                cliente_id: '',
                concepto_id: 'IXS',
                dni: '',
                descripcion_cliente: '',
                items:[
                    {
                        producto_id: '',
                        codigo: '',
                        descripcion_producto: '',
                        cantidad: 1,
                        monto: 0,
                        fecha_inicio: moment().format('Y-MM-DD')
                    }
                ],
                descuento: 0,
                cuenta_id: 1
            }
        }
    },
}
</script>