<template>
    <div class="conrainer" style="margin:0 2% 0 2%;">
    <div  v-if="isAdmin">
         <div v-if="strSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>{{strSuccess}}</strong>
                </div>
    
                <div v-if="strError" class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>{{strError}}</strong>
                </div>
    
    
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">Users List</h5>
                <div>
                    <button class="btn btn-success" type="button" @click="this.$router.push('/user/add')">Create New User</button>
                </div>
            </div>
            
            <table class="table table-hover table-sm">
                <thead class="bg-dark text-light">
                    <tr>
                        <th width="50" class="text-center">#</th>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Role Type</th>
                        <th>Description</th>
                        <th class="text-center" width="200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index ) in users" :key="user.id">
                    <td class="text-center">{{index+1}}.</td>
                        <td>{{user.name}}</td>
                        <td>{{user.email}}</td>
                        <td>{{user.role.role_name}}</td>
                        <td>{{user.role.description}}</td>
      
                        <td class="text-center">
                            <router-link :to="{name:'edituser', params: {id:user.id}}" class="btn btn-warning mx-1">Edit</router-link>
    
                            <button class="btn btn-danger" @click="deletePost(user.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
    
    
        </div>
    </div>
    
    
    </div>
    
    <div v-else>
             <div class="d-flex align-items-center justify-content-center vh-100">
                <div class="text-center">
                    <h1 class="display-1 fw-bold">404</h1>
                    <p class="fs-3"> <span class="text-danger">Opps!</span> Page not found.</p>
                    <p class="lead">
                        You don't have permission to access this page.
                      </p>
                </div>
            </div>
    </div>
    
</div>
    </template>
    
    <script>
        export default {
            data() {
                return {
                    isAdmin: true,
                    users: [],
                    strSuccess: '',
                    strError: ''
                }
            },
            created() {
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.get('/api/page/users')
                    .then(response => {
                        this.users = response.data;
                        console.log(response.data)
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
                });
    
    
                     if (window.Laravel.isLoggedin) {
              this.username = window.Laravel.user
              if(window.Laravel.role_type != 1){
                this.isAdmin = false
              }
              
                }
            },
            methods: {
                deletePost(id) {
                    this.$axios.get('/sanctum/csrf-cookie').then(response => {
                        let existingObj = this;
                        if(confirm("Do you really want to delete this data?")) {
                            this.$axios.delete(`/api/page/usersdelete/${id}`)
                            .then(response => {
                                let i = this.users.map(item => item.id).indexOf(id); // find index of your object
                                this.users.splice(i, 1);
                                existingObj.strError = "";
                                existingObj.strSuccess = response.data.success;
                            })
                            .catch(function(error) {
                                // existingObj.strError = "";
                                // existingObj.strSuccess = error.response.data.message;
                                console.log(error)
                            });
                        }
                    });
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

<style scoped>

</style>