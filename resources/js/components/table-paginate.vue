<template>
    <div>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" v-if="current_page!=1" @click="getPagina(current_page-1)">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <li v-for="n in last_page" class="page-item" :class="(n==current_page) ? 'active':''">
                    <a class="page-link" @click="getPagina(n)">{{n}}</a>
                </li>
                <li class="page-item">
                <a class="page-link" v-if="current_page!=last_page" @click="getPagina(current_page+1)">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
                </li>
            </ul>
        </nav>
    </div>
</template>
<script>
export default {
    props: {
        paginateGet: {type: Function},
        current_page: {type: Number},
        last_page: {type: Number},
    },
    mounted() {
        // console.log(this.current_page);
        // console.log(this.last_page);
    },
    methods: {
        getPagina(id){
            this.paginateGet(id)
        }     
    },
}
</script>