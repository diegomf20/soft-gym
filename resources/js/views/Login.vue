<template>
    <div>
        <input type="text" v-model="user.usuario">
        <input type="text" v-model="user.contrasenia">
        <button @click="logearse()">Login</button>
    </div>
</template>
<script>
export default {
    data() {
        return {
            user: {
                usuario: '',
                contrasenia: ''
            }
        }
    },
    methods: {
        logearse(){
            axios.post(`${url_base}/login`,this.user).then((param)=>{
                this.$store.commit('auth_success', param.data.data);
                this.$router.push({path: "/"} );
            });
        }
    },
}
</script>