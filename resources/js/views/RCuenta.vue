<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">REPORTE DE CUENTAS</h1>
        </nav>
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Cuentas Hoy</h5>
                    </div>
                    <div class="card-body">
                        <div class="table">
                            <div class="table-header">
                                <div class="row">
                                    <div class="col-8">Descripcion</div>
                                    <div class="col-4">total</div>
                                </div>
                            </div>
                            <div v-for="item in cuentas" class="table-row">
                                <div class="row">
                                    <div class="col-8">{{ item.descripcion }}</div>
                                    <div class="col-4">{{ item.total }}</div>
                                </div>
                            </div>
                            <div class="table-row">
                                <div class="row">
                                    <div class="col-8"><b>TOTAL</b></div>
                                    <div class="col-4"><b>{{ total_general }}</b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Cuentas por Periodo</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2 mb-3">
                                <b for="">Fecha Inicio:</b>
                            </div>
                            <div class="col-sm-4 mb-3">
                                <input type="date" class="form-control" v-model="fecha_inicio">
                            </div>
                            <div class="col-sm-2 mb-3">
                                <b for="">Fecha Fin:</b>
                            </div>
                            <div class="col-sm-4 mb-3">
                                <input type="date" class="form-control" v-model="fecha_fin">
                            </div>
                            <div class="col-sm-3 mb-3">
                                <button class="btn btn-danger form-control" @click="listar()">
                                    Actualizar
                                </button>
                            </div>
                        </div>
                            <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="table">
                                    <div class="table-header">
                                        <div class="row">
                                            <!-- <div class="col-2">Codigo</div> -->
                                            <div class="col-5">Descripcion</div>
                                            <div class="col-7">
                                                <div class="row">
                                                    <div class="col-6">Ingreso</div>
                                                    <div class="col-6">Egreso</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-for="item in data" class="table-row">
                                        <div class="row">
                                            <!-- <div class="col-2">{{ item.id }}</div> -->
                                            <div class="col-5">{{ item.descripcion }}</div>
                                            <div class="col-7">
                                                <div class="row">
                                                    <div class="col-6">{{ item.ingreso }}</div>
                                                    <div class="col-6">{{ item.egreso }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-row">
                                        <div class="row">
                                            <div class="col-8 text-right">Ingreso:</div>
                                            <div class="col-4 text-right">{{ total_ingreso }}</div>
                                            <div class="col-8 text-right">Egreso:</div>
                                            <div class="col-4 text-danger text-right">{{ total_egreso }}</div>
                                            <div class="col-8 text-right"><b>Total:</b></div>
                                            <div class="col-4 text-right"><b>{{ (total_ingreso - total_egreso).toFixed(2) }}</b></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <canvas id="myChart" width="400" height="350"></canvas>
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
            data: [],
            cuentas: [],
            fecha_inicio: moment().startOf('month').format('Y-MM-DD'),
            fecha_fin: moment().endOf('month').format('Y-MM-DD')
        }
    },
    mounted() {
        this.listar();
    },
    computed:{
        total_egreso(){
            var total=0;
            for (let i = 0; i < this.data.length; i++) {
                const item = this.data[i];
                total+=Number(item.egreso);
            }
            return total.toFixed(2);
        },
        total_ingreso(){
            var total=0;
            for (let i = 0; i < this.data.length; i++) {
                const item = this.data[i];
                total+=Number(item.ingreso);
            }
            return total.toFixed(2);
        },
        total_general(){
            var total=0;
            for (let i = 0; i < this.cuentas.length; i++) {
                const item = this.cuentas[i];
                total+=Number(item.total);
            }
            return total.toFixed(2);
        }
    },
    methods: {
        listar(){
            axios.get(`${url_base}/reportes/cuenta?fecha_inicio=${this.fecha_inicio}&fecha_fin=${this.fecha_fin}`).then((params)=> {
                this.data=params.data
                this.graficas(this.data);
            });
            axios.get(`${url_base}/reportes/resumen`).then((params)=> {
                this.cuentas=params.data
                // this.graficas(this.data);
            });
        },
        graficas(egresos){
            console.log(egresos);
            var labels=[];
            var data=[];
            for (let i = 0; i < egresos.length; i++) {
                const egreso = egresos[i];
                if (egreso.ingreso>0) {
                    labels.push(egreso.descripcion)
                    data.push(egreso.ingreso)
                }
            }
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            'rgba(250, 24, 37,1)',//rojo
                            'rgba(0,80,164,1)',
                            'rgba(54, 162, 235,1)',
                            '#CC2936',
                            'rgba(75, 192, 192,1)',
                            'rgba(247, 168, 32, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    // tooltips:{
                    //     enable: false
                    // },
                    responsive: true,
                    legend: {
                        position: 'right',
                        // display: false
                    },
                    title: {
                        display: true,
                        text: 'GRAFICO INGRESOS POR CUENTA'
                    },
                    plugins: {
                        datalabels: {
                            formatter: (value, ctx) => {
                                
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                                dataArr.map(data => {
                                    sum += Number(data);
                                });
                                let percentage = (value*100 / sum).toFixed(0)+"%";
                                return percentage;
                            },
                            color: '#fff',
                        }
                    },
                },
            });
        }
    },  
}
</script>