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
                                <h5 class="card-title">Create New User</h5>
                                <div>
                                    <button class="btn btn-success mr-2" type="button" @click="this.$router.push('/users')">Back</button>
                                </div>
                            </div>

                    </div>
                    
                    <div class="card-body">
                        <form >

                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control" name="name" v-model="name" required
                                           autofocus autocomplete="off"  placeholder="Enter your name">

                                 

                                </div>

                                
                               
                              </div>

                            <div class="form-group row mt-1">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-8 mt-2">
                                    <input id="email" type="email" class="form-control" name="email" v-model="email" required
                                           autofocus autocomplete="off" placeholder="Enter your email">
                                </div>
                            </div>


                            <div class="form-group row mt-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-8 mt-2">
                                    <input id="password" type="password" class="form-control" name="password" v-model="password"
                                           required autocomplete="off" placeholder="Enter your password">
                                </div>
                            </div>

                              <div class="form-group row mt-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Cofirm Password</label>
                                <div class="col-md-8 mt-2">
                                    <input id="confirmpassword" type="password" class="form-control" name="confirmpassword" v-model="confirmpassword"
                                           required autocomplete="off" placeholder="Enter your password">
                                </div>
                            </div>

                              <div class="form-group row mt-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Role</label>
                                <div class="col-md-8 mt-2">
                                <select v-model="role_type" class="form-select" required aria-label="select example" placeholder="Please Select" >
                                    <option v-for="role in roles" :key="role.id" :value=role.id >{{role.role_name}}</option>
                                    </select>
                            </div>
                            </div>

                        

                            <div class="form-group row mt-1 mb-0">
                                <div class="col-md-10 offset-md-10 mt-2">
                                    <button type="submit" class="btn btn-primary" @click="handleSubmit">
                                        Register
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