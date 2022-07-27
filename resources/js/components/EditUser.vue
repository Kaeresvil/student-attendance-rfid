<template>

   <div class="container">
        <div class="row d-flex justify-content-center align-items-center jutify-content-center">
            <div class="col-md-10">
                <div v-if="error !== null" class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    <strong>{{error}}</strong>
                </div>

                <div class="card card-default">

                    <div class="card-header">

                    <div class="d-flex justify-content-between ">
                                <h5 class="card-title">Update User Information</h5>
                                <div>
                                    <button class="btn btn-success" type="button" @click="this.$router.push('/users')">Back</button>
                                </div>
                            </div>

                    </div>
                    
                    <div class="card-body">
                        <form  enctype="multipart/form-data">

                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control" v-model="name" >
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-8 mt-2">
                                    <input id="email" type="email" class="form-control" v-model="email">
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                
                                <label for="password" class="col-md-4 col-form-label text-md-right">Role</label>
                                <div class="col-md-8 mt-2">
                                <select v-model="role_type" class="form-select" required  >
                                    <option v-for="role in roles" :key="role.id" :value=role.id >{{role.role_name}}</option>
                                    </select>
                            </div>
                            </div>


                            <div class="form-group row mt-1 mb-0">
                                <div class="col-md-8 offset-md-4 mt-2">
                                    <button type="submit" class="btn btn-primary" @click="updateUser" >
                                        Update User
                                    </button>
                                </div>
                            </div>

                           


                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default{
    data() {
        return {
            id:'',
            name: "",
            email: "",
            role_type: "",
            roles: [],

            error: null
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/page/usersedit/${this.$route.params.id}`)
            .then(response => {
                this.name = response.data['name'];
                this.email = response.data['email'];
                this.role = response.data['role_name'];
                 console.log(response.data);

            })
            .catch(function(error) {
                console.log(error);
            });
        })

          this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/page/roles')
            .then(response => {
                 
                this.roles = response.data;
            })
            .catch(function(error) {
                console.log(error);
            });
        })
    },

 
    methods: {

        updateUser(e) {
             e.preventDefault()
             let currentObj = this;  
             this.$axios.get('/sanctum/csrf-cookie').then(response => {
                        this.$axios.post(`/api/page/users/update/${this.$route.params.id}`, {
                            name: this.name,
                            email: this.email,
                            role_type: this.role_type,
                            password: this.password,
                            confirmpassword: this.confirmpassword
                        })
                        .then(response => {
                            if (response.data.success) {
                                 window.location.href = "/users"
                            } else {
                                this.error = response.data.message
                            }
                        })
                        .catch(function (error) {
                            currentObj.error = "Inputs fiels duplication! make it unique please."
                        });
                    })

        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>