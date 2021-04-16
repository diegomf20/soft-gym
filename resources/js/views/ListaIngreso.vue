<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Lista de Ingresos</h1>
        </nav>
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6 col-lg-8">
                        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-nuevo">
                            Nuevo
                        </button> -->
                    </div>
                    <!-- <div class="col-sm-6 col-lg-4">
                        <input class="form-control" placeholder="search">
                    </div> -->
                </div>
                <div class="table">
                    <div class="table-header">
                        <div class="row">
                            <div class="col-2">Fecha</div>
                            <div class="col-2">DNI</div>
                            <div class="col-3">Nombres y Apellido</div>
                            <div class="col-2">Descuento</div>
                            <div class="col-2">Total</div>
                            <div class="col-1">Opciones</div>
                        </div>
                    </div>
                    <div v-for="item in ingresos.data" 
                        :key="item.id"
                        class="table-row" 
                        :class=" item.estado=='I' ? 'text-danger' : ''">
                        <div class="row">
                            <div class="col-sm-2"><label>Fecha:</label>{{ item.fecha }}</div>
                            <div class="col-sm-2"><label>DNI:</label>{{ item.dni }}</div>
                            <div class="col-sm-3">{{ item.descripcion_cliente }}</div>
                            <div class="col-sm-2">{{ item.descuento}}</div>
                            <div class="col-sm-2">{{ item.total }}</div>
                            <div class="col-sm-1 text-center">
                                <a  @click="ver(item.id)"
                                    class="text-info">
                                    <i class="far fa-eye"></i>
                                </a>
                                <a  v-if="item.estado=='A'"
                                    @click="eliminar(item.id)" 
                                    type="button" 
                                    class="text-danger"
                                    >
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
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
        <div class="modal fade" id="modal-ver" tabindex="-1" role="dialog" aria-labelledby="modal-verLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-verLabel">Datos de Ingreso</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="ingreso!=null">
                        <div class="row">
                            <div class="col-2">
                                <b>Fecha:</b>
                            </div>
                            <div class="col-10">
                                {{ ingreso.fecha }}
                            </div>
                            <div class="col-2">
                                <b>DNI:</b>
                            </div>
                            <div class="col-10">
                                {{ ingreso.dni }}
                            </div>
                            <div class="mb-3 col-2">
                                <b>Cliente:</b>
                            </div>
                            <div class="col-10">
                                {{ ingreso.descripcion_cliente }}
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Producto o Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Precio U.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in ingreso.detalles">
                                    <td>{{ item.descripcion }}</td>
                                    <td>{{ item.cantidad }}</td>
                                    <td>{{ item.precio }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-9 text-right">
                                <b>Descuento:</b>
                            </div>
                            <div class="col-3 text-right">
                                {{ ingreso.descuento }}
                            </div>
                            <div class="col-9 text-right">
                                <b>Total:</b>
                            </div>
                            <div class="col-3 text-right">
                                {{ ingreso.total }}
                            </div>
                        </div>
                    </div>
                </div>
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
            ingresos: [],
            ingreso: null
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
        },
        ver(id){
            $('#modal-ver').modal();
            axios.get(`${url_base}/ingreso/${id}`).then((params)=> {
                this.ingreso=params.data
            });
        },
        // formatear(fecha){
        //     console.log(fecha);
        //     moment(fecha).format('MMMM Do YYYY, h:mm:ss a');
        // },
        eliminar(id){
            var t=this;
            swal({
                text: "¿Desea anular este registro?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.post(`${url_base}/ingreso/${id}`,{
                        _method: 'delete'
                    }).then((params)=> {
                        t.listar();
                        swal("Ingreso Anulado", {
                            icon: "success",
                        });
                    }); 
                } 
            });
        }
    },
}
</script>