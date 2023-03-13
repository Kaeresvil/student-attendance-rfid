<template>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center jutify-content-center">
            <div class="col-md-10">
                <div v-if="formError !== null" class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    <strong>{{formError}}</strong>
                </div>

                <div class="card card-default">

                    <div class="card-header">

                    <div class="d-flex justify-content-between ">
                                <h5 class="card-title">Register New Student</h5>
                                <div>
                                    <button class="block mr-2 uppercase shadow bg-emerald-800 hover:bg-emerald-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" type="button" @click="this.$router.push('/users')">Back</button>
                                </div>
                            </div>

                    </div>
                    
                    <div class="card-body">
                        <form >

                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control" name="name" v-model="name" requiredautofocus autocomplete="off"  placeholder="Enter your name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">LRN #</label>
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control" name="name" v-model="name" requiredautofocus autocomplete="off"  placeholder="Enter your name">
                                </div>
                            </div>

                            <div class="w-full flex justify-end">
                                <div>
                                    <button type="submit" @click="handleSubmit" class="mr-2 mt-2 uppercase shadow bg-blue-800 hover:bg-blue-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded">Register</button>
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
    export default {
        data() {
            return {
                name: "",
                email: "",
                password: "",
                confirmpassword: "",
                formError: null,
                roles: [],
                role_type: ''
            }
        },
         created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/page/roles')
            .then(response => {
                  console.log(response.data);
                this.roles = response.data;
            })
            .catch(function(error) {
                console.log(error);
            });
        })
    },
        methods: {
            handleSubmit(e) {
                e.preventDefault()
                if(this.password.length > 0) {
                    let currentObj = this;  

                    this.$axios.get('/sanctum/csrf-cookie').then(response => {
                        this.$axios.post('/api/page/register', {
                            name: this.name,
                            email: this.email,
                            password: this.password,
                            confirmpassword: this.confirmpassword,
                            role_type: this.role_type
                        })
                        .then(response => {
                            if (response.data.success) {
                                 window.location.href = "/users"
                            } else{
                                this.formError = response.data.message
                            }
                              
                            
                        })
                        .catch(function (error) {
                            currentObj.formError =  error.response.data.errors;
                        });
                        
                    })
                }else{
                    this.formError = "Please complete all input requirements";
                }
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