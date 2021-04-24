<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Lista de egresos</h1>
        </nav>
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="mb-3 col-sm-6 col-lg-3">
                                <b for="">Fecha Inicio:</b>
                            </div>
                            <div class="mb-3 col-sm-6 col-lg-3">
                                <input type="date" class="form-control" v-model="fecha_inicio">
                            </div>
                            <div class="mb-3 col-sm-6 col-lg-3">
                                <b for="">Fecha Fin:</b>
                            </div>
                            <div class="mb-3 col-sm-6 col-lg-3">
                                <input type="date" class="form-control" v-model="fecha_fin">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-sm-6 col-lg-2">
                        <button class="btn btn-danger form-control" @click="listar()">
                            Buscar
                        </button>
                    </div>
                    <div class="mb-3 col-sm-6 col-lg-2">
                        <a class="btn btn-success form-control" :href="excel()">
                            Descargar
                        </a>
                    </div>
                </div>
                <div class="table table-responsive">
                    <div class="table-header">
                        <div class="row">
                            <div class="col-2">Fecha</div>
                            <div class="col-3">Concepto</div>
                            <div class="col-2">Cuenta</div>
                            <div class="col-2">Total</div>
                            <div class="col-1">Opciones</div>
                        </div>
                    </div>
                    <div v-for="item in egresos.data" 
                        :key="item.id"
                        class="table-row" 
                        :class=" item.estado=='I' ? 'text-danger' : ''">
                        <div class="row">
                            <div class="col-sm-2"><label>Fecha:</label>{{ item.fecha }}</div>
                            <div class="col-sm-3"><label>Concepto:</label>{{ item.descripcion_concepto }}</div>
                            <div class="col-sm-2"><label>Cuenta:</label>{{ item.descripcion_cuenta }}</div>
                            <div class="col-sm-2"><label>Total:</label>{{ item.monto }}</div>
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
                    :current_page="egresos.current_page" 
                    :last_page="egresos.last_page"
                    :paginateGet="listar"
                ></table-paginate>
            </div>
        </div>
        <div class="modal fade" id="modal-ver" tabindex="-1" role="dialog" aria-labelledby="modal-verLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-verLabel">Datos de egreso</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="egreso!=null">
                        <div class="row  mb-3">
                            <div class="col-3">
                                <b>Fecha:</b>
                            </div>
                            <div class="col-9">
                                {{ egreso.fecha }}
                            </div>
                            <div class="col-3">
                                <b>Concepto:</b>
                            </div>
                            <div class="col-9">
                                {{ egreso.descripcion_concepto }}
                            </div>
                            <div class="col-3">
                                <b>Cuenta:</b>
                            </div>
                            <div class="col-9">
                                {{ egreso.descripcion_cuenta }}
                            </div>
                            <div class="col-3">
                                <b>Creado Por:</b>
                            </div>
                            <div class="col-9">
                                {{ egreso.creado_por }}
                            </div>
                            <div v-if="egreso.eliminado_por" class="col-3">
                                <b>Eliminado Por:</b>
                            </div>
                            <div v-if="egreso.eliminado_por" class="col-9">
                                {{ egreso.eliminado_por }}
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
                                <tr v-for="item in egreso.detalles">
                                    <td>{{ item.descripcion }}</td>
                                    <td>{{ item.cantidad }}</td>
                                    <td>{{ item.monto }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-9 text-right">
                                <b>Total:</b>
                            </div>
                            <div class="col-3 text-right">
                                {{ egreso.monto }}
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
            egresos: [],
            egreso: null,
            fecha_inicio: moment().startOf('month').format('Y-MM-DD'),
            fecha_fin: moment().endOf('month').format('Y-MM-DD')
        }
    },
    mounted() {
        this.listar();
    },
    methods: {
        listar(n=1){
            axios.get(`${url_base}/egreso?page=${n}&fecha_inicio=${this.fecha_inicio}&fecha_fin=${this.fecha_fin}`).then((params)=> {
                this.egresos=params.data
            }); 
        },
        ver(id){
            $('#modal-ver').modal();
            axios.get(`${url_base}/egreso/${id}`).then((params)=> {
                this.egreso=params.data
            });
        },
        excel(){
            return `${url_base}/egreso?type=excel&fecha_inicio=${this.fecha_inicio}&fecha_fin=${this.fecha_fin}`;
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
                    axios.post(`${url_base}/egreso/${id}`,{
                        _method: 'delete'
                    }).then((params)=> {
                        t.listar();
                        swal("egreso Anulado", {
                            icon: "success",
                        });
                    }); 
                } 
            });
        }
    },
}
</script>