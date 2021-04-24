<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">CLIENTES RECURRENTES</h1>
        </nav>
        <div class="row mb-3">
            <div class="form-group col-sm-2">
                <b for="">Fecha Inicio:</b>
            </div>
            <div class="form-group col-sm-3">
                <input type="date" class="form-control" v-model="fecha_inicio">
            </div>
            <div class="form-group col-sm-2">
                <b for="">Fecha Fin:</b>
            </div>
            <div class="form-group col-sm-3">
                <input type="date" class="form-control" v-model="fecha_fin">
            </div>
            <div class="form-group col-sm-2">
                <button class="btn btn-danger form-control" @click="listar()">
                    Buscar
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table">
                    <div class="table-header">
                        <div class="row">
                            <!-- <div class="col-2">Codigo</div> -->
                            <div class="col-6">Cliente</div>
                            <div class="col-3">Servicio / Venta</div>
                            <div class="col-3">Total</div>
                        </div>
                    </div>
                    <div v-for="item in data" class="table-row">
                        <div class="row">
                            <!-- <div class="col-2">{{ item.dni }}</div> -->
                            <div class="col-6">{{ item.descripcion_cliente }}</div>
                            <div class="col-3">{{ item.servicios_ventas }}</div>
                            <div class="col-3">{{ item.total }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="350"></canvas>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</template>
<script>
// import TablePaginate from '../components/table-paginate'

export default {
    // components: { TablePaginate },
    data() {
        return {
            data: [],
            fecha_inicio: moment().startOf('month').format('Y-MM-DD'),
            fecha_fin: moment().endOf('month').format('Y-MM-DD')
        }
    },
    mounted() {
        this.listar();
    },
    computed:{
        // total_egreso(){
        //     var total=0;
        //     for (let i = 0; i < this.data.length; i++) {
        //         const item = this.data[i];
        //         total+=Number(item.egreso);
        //     }
        //     return total.toFixed(2);
        // },
        // total_ingreso(){
        //     var total=0;
        //     for (let i = 0; i < this.data.length; i++) {
        //         const item = this.data[i];
        //         total+=Number(item.ingreso);
        //     }
        //     return total.toFixed(2);
        // },
    },
    methods: {
        listar(){
            axios.get(`${url_base}/reportes/recurrentes?fecha_inicio=${this.fecha_inicio}&fecha_fin=${this.fecha_fin}`).then((params)=> {
                this.data=params.data
                // this.graficas(this.data);
            });
        },
        // graficas(egresos){
        //     var labels=[];
        //     var data=[];
        //     for (let i = 0; i < egresos.length; i++) {
        //         const egreso = egresos[i];
        //         if (egreso.egreso>0) {
        //             labels.push(egreso.descripcion)
        //             data.push(egreso.egreso)
        //         }
        //     }
        //     var ctx = document.getElementById('myChart').getContext('2d');
        //     var myChart = new Chart(ctx, {
        //         type: 'pie',
        //         data: {
        //             labels: labels,
        //             datasets: [{
        //                 data: data,
        //                 backgroundColor: [
        //                     'rgba(250, 24, 37,1)',//rojo
        //                     'rgba(0,80,164,1)',
        //                     'rgba(54, 162, 235,1)',
        //                     '#CC2936',
        //                     'rgba(75, 192, 192,1)',
        //                     'rgba(247, 168, 32, 1)',
        //                 ],
        //                 borderWidth: 1
        //             }]
        //         },
        //         options: {
        //             // tooltips:{
        //             //     enable: false
        //             // },
        //             responsive: true,
        //             legend: {
        //                 position: 'right',
        //                 // display: false
        //             },
        //             // title: {
        //             //     display: true,
        //             //     text: 'Egresos por Mes'
        //             // },
        //             plugins: {
        //                 datalabels: {
        //                     formatter: (value, ctx) => {
                                
        //                         let sum = 0;
        //                         let dataArr = ctx.chart.data.datasets[0].data;
        //                         dataArr.map(data => {
        //                             console.log(data);
        //                             sum += Number(data);
        //                         });
        //                         console.log(sum);
        //                         let percentage = (value*100 / sum).toFixed(0)+"%";
        //                         return percentage;
        //                     },
        //                     color: '#fff',
        //                 }
        //             },
        //         },
        //     });
        // }
    },  
}
</script>