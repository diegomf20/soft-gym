<template>
    <div class="login-content">
        <div class="card card-login">
            <div class="card-header text-center">
                <img src="/logo.png" width="100px" alt="">
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="logearse()">
                    <div class="form-group">
                        <label for="">Usuario</label> 
                        <input type="text" v-model="user.usuario" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Contraseña</label>
                        <input type="password" v-model="user.contrasenia" class="form-control">
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<style>
    .login-content{
        display: flex;
        align-items: center;
        height: 100vh;
    }
    .card-login{
        margin-right: auto;
        margin-left: auto;
        background-color: #ced4da;
    }
</style>
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
                var respuesta=param.data
                switch (respuesta.status) {
                    case "OK":
                        this.$store.commit('auth_success', param.data.data);
                        this.$router.push({path: "/"} );
                        break;
                    case "ERROR":
                        swal({
                            text: respuesta.message,
                            icon: "error"
                        });
                        break;
                    default:
                        break;
                }
            });
        }
    },
}
</script>