<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Nuevo Ingreso</h1>
        </nav>
        <div class="row">
            <div class="col-sm-2">
                <label for="">DNI: </label>
                <input @keyup="getCliente" type="text" class="form-control" v-model="venta.dni">
            </div>
            <div class="col-sm-4">
                <label for="">Nombres y apellidos: </label>
                <input type="text" class="form-control" v-model="venta.descripcion_cliente" disabled>
            </div>
            <div class="col-sm-3">
                <label for="">Cuenta</label>
                <select class="form-control" v-model="venta.cuenta_id">
                    <option v-for="cuenta in cuentas" :value="cuenta.id">{{ cuenta.descripcion }}</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label for="">Referencia (**)</label>
                <input type="text" class="form-control">
            </div>
        </div>
        <div class="table">
            <div class="table-header">
                <div class="col-1">Buscar</div>
                <div class="col-1">Tipo</div>
                <div class="col-4">Descripción</div>
                <div class="col-1">Cantidad</div>
                <div class="col-2">Monto</div>
                <div class="col-2">Sub Total</div>
                <div class="col-1">Quitar</div>
            </div>
            <div v-for="(item,index) in venta.items" class="table-row">
                <div class="col-1">
                    <button class="btn btn-info" @click="buscarProducto(index)">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="col-1">{{ (item.tipo=='P') ? 'Producto' : (item.tipo=='S'?'Servicio':'') }}</div>
                <div class="col-4">
                    {{ item.descripcion_producto }}
                    <div v-if="item.tipo=='S'">
                        <hr>
                        <div class="row">
                            <div class="col-4">
                                <label for="">Fecha Inicio:</label>
                            </div>
                            <div class="col-8">
                                <input  class="form-control" type="date" v-model="item.fecha_inicio">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-1">
                    <input type="number" class="form-control" v-model="item.cantidad">
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
                        <table class="table">
                            <tr v-for="producto in productos">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="save()" type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            cuentas: [],
            venta: this.initVenta(), 
            index: 0,
            productos: []
        }
    },
    mounted() {
        this.listarCuentas();
    },
    computed:{
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
            axios.get(`${url_base}/producto?all`).then((params)=> {
                this.productos=params.data
            }); 
        },
        seleccionar(producto){
            $('#modal-buscar').modal('hide');
            this.venta.items[this.index].producto_id=producto.id;
            this.venta.items[this.index].descripcion_producto=producto.descripcion;
            this.venta.items[this.index].tipo=producto.tipo;
            this.venta.items[this.index].monto=producto.precio;
        },
        save(){
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
                
                    default:
                        break;
                }
            });
        },
        initVenta(){
            return {
                cliente_id: '',
                dni: '',
                descripcion_cliente: '',
                items:[],
                descuento: 0,
                cuenta_id: 1
            }
        }
    },
}
</script>