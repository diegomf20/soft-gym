<template>
    <div class="container-fluid">
        <nav>
            <h1 class="card-title">Bienvenido</h1>
            <h1 class="card-comment mb-0">Resumen de General</h1>
        </nav>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3 card card-resumen">
                            <div class="card-body">
                                <h2 class="text-center mb-0">{{ indicadores.membresias }}</h2>
                            </div>
                            <div class="card-footer text-center">
                                <p class="mb-0">Membresias Activas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3 card card-resumen">
                            <div class="card-body">
                                <h2 class="text-center mb-0">S/. {{ ingresos }}</h2>
                            </div>
                            <div class="card-footer text-center">
                                <p class="mb-0">Ingresos Hoy</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div id="cumpleanios" class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-birthday-cake"></i> Cumpleaños Proximos</h6>
                    </div>
                    <div class="card-body scrollable">
                        <ul class="list-group" >
                            <li class="list-group-item" v-for="cliente in cumpleanios">
                                <h6 class="mb-0">{{ `${cliente.nombres} ${cliente.ape_paterno}` }}</h6>
                                <p class="mb-0 comentario">{{ cliente.fecha }}</p>
                            </li>
                        </ul>
                        <h6 class="text-secondary text-center" v-if="cumpleanios.length==0"><i class="fas fa-compress-alt"></i> Sin Datos</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div id="cumpleanios" class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-users"></i> Membresias por Vencer y vencidas</h6>
                    </div>
                    <div class="card-body scrollable">
                        <ul class="list-group">
                            <li v-for="item in membresias"
                            :key="item.dni" 
                            class="list-group-item" 
                            :class="item.vencimiento<0 ? 'text-danger':''">
                                <div class="row">
                                    <div class="col-12"><b>{{ item.descripcion_cliente }}</b></div>
                                    <div class="col-12">{{ item.descripcion_producto }}</div>
                                    <div class="col-12">
                                        {{ `${formatearFecha(item.fecha_inicio)} - ${formatearFecha(item.fecha_fin)}` }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <h6 class="text-secondary text-center" v-if="membresias.length==0"><i class="fas fa-compress-alt"></i> Sin Datos</h6>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Resumen de Egresos</h6>
                            </div>
                            <div class="col-4 text-right">
                                <h6> {{ anio_mes }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="350"></canvas>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</template>
<style>
#cumpleanios .card-body{
    max-height: 200px;
}
p.comentario{
    font-size: 12px;
}
.scrollable{
    overflow-y: auto;
}
@media (max-width: 992px){
    .sidebar{
        width: 0px;
    }
    .sidebar .sidebar-header{
        height: 0px;
        padding: 0;
    }
    .content{
        width: calc(100% - 0px);
    }
    .content-fluid{
        padding: 16px 8px;
    }
}
.card-resumen i{
    font-size: 60px;
}
</style>
<script>
export default {
    data() {
        return {
            indicadores: {
                membresias: 0
            },
            cumpleanios: [],
            egresos: [],
            ingresos: 0,
            anio_mes: moment().format('Y-MM'),
            membresias: []
        }
    },
    mounted() {
        axios.get(`${url_base}/indicadores`).then((params)=> {
            this.indicadores=params.data
        }); 
        axios.get(`${url_base}/cumpleanios`).then((params)=> {
            this.cumpleanios=params.data
        }); 
        axios.get(`${url_base}/indicador/ingresos_hoy`).then((params)=> {
            this.ingresos=params.data.ingresos
        }); 
        axios.get(`${url_base}/indicador/membresias`).then((params)=> {
            this.membresias=params.data
        }); 
        
        this.crear();
    },
    methods: {
        formatearFecha(value){
            return moment(value).format('DD/MM/Y')
        },
        crear(){
            axios.get(`${url_base}/indicador/egresos?fecha=2021-04`).then((params)=> {
                var egresos=params.data
                var labels=[];
                var data=[];
                for (let i = 0; i < egresos.length; i++) {
                    const egreso = egresos[i];
                    labels.push(egreso.descripcion)
                    data.push(egreso.total)
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
                        // title: {
                        //     display: true,
                        //     text: 'Egresos por Mes'
                        // },
                        plugins: {
                            datalabels: {
                                formatter: (value, ctx) => {
                                    
                                    let sum = 0;
                                    let dataArr = ctx.chart.data.datasets[0].data;
                                    dataArr.map(data => {
                                        console.log(data);
                                        sum += Number(data);
                                    });
                                    console.log(sum);
                                    let percentage = (value*100 / sum).toFixed(0)+"%";
                                    return percentage;
                                },
                                color: '#fff',
                            }
                        },
                    },
                });
            }); 
        }
    },
}
</script>