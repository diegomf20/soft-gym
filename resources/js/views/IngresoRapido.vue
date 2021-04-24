<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Nuevo Ingreso Rápido</h1>
        </nav>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5>Datos Generales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 col-lg-3 form-group">
                                <label>Fecha Inicio:</label>
                                <input  class="form-control" type="date" v-model="venta.fecha">
                            </div>
                            <div class="col-sm-8 col-lg-3 form-group">
                                <label for="">Cuenta</label>
                                <select class="form-control" v-model="venta.cuenta_id">
                                    <option v-for="cuenta in cuentas" :value="cuenta.id">{{ cuenta.descripcion }}</option>
                                </select>
                            </div>
                            <div class="col-lg-3 form-group">
                                <label for="">Producto</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" v-model="venta.descripcion_producto" readonly>
                                    <button class="input-group-text btn-info" @click="buscarProducto(0)">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-3 form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="">Cantidad</label>
                                        <input type="number" class="form-control" v-model="venta.cantidad">
                                    </div>
                                    <div class="col-6">
                                        <label for="">Monto</label>
                                        <input type="number" class="form-control" :value="Number(venta.monto).toFixed(2)" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-right">
                                <button class="btn btn-primary" @click="save()">
                                    GUARDAR (S/. {{ Number(venta.cantidad*venta.monto).toFixed(2) }})
                                </button>
                            </div>
                        </div>
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
    },
    methods: {
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
            axios.get(`${url_base}/producto?tipo=P&search=${this.search}`).then((params)=> {
                this.productos=params.data
            }); 
        },
        seleccionar(producto){
            $('#modal-buscar').modal('hide');
            this.venta.producto_id=producto.id;
            this.venta.descripcion_producto=producto.descripcion;
            this.venta.tipo=producto.tipo;
            this.venta.monto=producto.precio;
            this.search='';
        },
        save(){
            this.error_ingreso={};
            axios.post(`${url_base}/ingreso/rapic`,this.venta)
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
                descripcion_cliente: '',
                producto_id: '',
                descripcion_producto: '',
                cantidad: 1,
                monto: 0,
                descuento: 0,
                cuenta_id: 1,
                fecha: moment().format('Y-MM-DD')
            }
        }
    },
}
</script>