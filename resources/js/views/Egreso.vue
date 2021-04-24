<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Nuevo Egreso General</h1>
        </nav>
        <div class="row">
            <div class="form-group col-sm-6 col-lg-3">
                <label for="">Concepto</label>
                <select class="form-control" v-model="venta.concepto_id">
                    <option v-for="concepto in conceptos" :value="concepto.id">{{ concepto.descripcion }}</option>
                </select>
                <span class="text-danger">{{ error_egreso.concepto_id }}</span>
            </div>
            <div class="form-group col-sm-6 col-lg-3">
                <label for="">Cuenta</label>
                <select class="form-control" v-model="venta.cuenta_id">
                    <option v-for="cuenta in cuentas" :value="cuenta.id">{{ cuenta.descripcion }}</option>
                </select>
            </div>
            <div class="form-group col-sm-6 col-lg-3">
                <label for="">Fecha</label>
                <input type="date" v-model="venta.fecha" class="form-control">
            </div>
            <div class="form-group col-sm-6 col-lg-3">
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
                            <div class="col-1">Tipo</div>
                            <div class="col-6">Descripción</div>
                            <div class="col-1">Cantidad</div>
                            <div class="col-2">Monto</div>
                            <div class="col-2">Sub Total</div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-for="(item,index) in venta.items" class="table-row">
                <div class="row">
                    <div class="col-3 col-lg-2 text-center">
                        <div class="row">
                            <div class="col-lg-6 mb-sm">
                                <a @click="removeItem(index)" type="button" class="btn btn-danger">X</a>
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
                            <div class="col-sm-3 col-lg-1">{{ (item.tipo=='P') ? 'Producto' : (item.tipo=='S'?'Servicio':'') }}</div>
                            <div class="mb-sm col-sm-9 col-lg-6">
                                <input type="text" class="form-control form-control-sm" v-model="item.descripcion">
                            </div>
                            <div class="col-4 col-lg-1">
                                <label for="">Cantidad</label>
                                <input type="number" class="form-control form-control-sm" v-model="item.cantidad">
                            </div>
                            <div class="col-4 col-lg-2">
                                <label for="">Monto</label>
                                <input type="text" v-model="item.monto" class="form-control form-control-sm">
                                <!-- {{ Number(item.monto).toFixed(2) }} -->
                            </div>
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
            conceptos: [],
            venta: this.initVenta(), 
            index: 0,
            productos: [],
            error_egreso: {},
            search: ''
        }
    },
    mounted() {
        this.listarCuentas();
        this.listarConceptos();
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
        listarConceptos(){
            axios.get(`${url_base}/concepto?tipo=E&all`).then((params)=> {
                this.conceptos=params.data
            }); 
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
                descripcion: '',
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
        },
        listarProductos(){
            axios.get(`${url_base}/producto?tipo=P&search=${this.search}`).then((params)=> {
                this.productos=params.data
            }); 
        },
        seleccionar(producto){
            $('#modal-buscar').modal('hide');
            this.venta.items[this.index].producto_id=producto.id;
            this.venta.items[this.index].descripcion=producto.descripcion;
            this.venta.items[this.index].tipo=producto.tipo;
            this.venta.items[this.index].monto=0;
        },
        save(){
            this.error_egreso={};
            axios.post(`${url_base}/egreso`,this.venta)
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
                    this.error_egreso=errors
                }
            });
        },
        initVenta(){
            return {
                concepto_id: null,
                cuenta_id: 1,
                referencia: '',
                items:[],
                fecha: moment().format('Y-MM-DD'),
                descuento: 0,
            }
        }
    },
}
</script>