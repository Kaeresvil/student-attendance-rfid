<template >
<div >
  <div  class="row">
        <h2 class="text-dark">Welcome {{role_type}} {{username}}</h2>
    </div>


      <div v-if="strSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{strSuccess}}</strong>
            </div>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between pb-2 mb-2">
            <h5 class="card-title">All Posts Data</h5>
            <div v-if="isAdmin">
                <button class="btn btn-success" type="button" @click="this.$router.push('/posts/add')">New Post</button>
            </div>
        </div>
        
        <table class="table table-hover table-sm">
            <thead class="bg-dark text-light">
                <tr>
                    <th width="50" class="text-center">#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th class="text-center" width="120">Image</th>
                    <th v-if="isAdmin" class="text-center" width="200">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(post, index) in posts" :key="post.id">
                    <td class="text-center">{{index+1}}.</td>
                    <td>{{post.name}}</td>
                    <td>{{post.description}}</td>
                    <td class="text-center">
                        <div v-if="post.image">
                            <img alt="post-img" width="100" v-bind:src="'/img/' +post.image">
                        </div>
                    </td>
                    <td v-if="isAdmin" class="text-center">
                        <router-link :to="{name:'editpost', params: {id:post.id}}" class="btn btn-warning mx-1">Edit</router-link>
                        <button class="btn btn-danger ml-1" @click="deletePost(post.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>


    </div>
</div>


</div>



</template>

<script>
    export default {
        data() {
            return {
                posts: [],
                strSuccess: '',
                strError: '',
                username: null,
                isAdmin: true,
            }
        },
        created() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get('/api/page/posts')
                .then(response => {
                    this.posts = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
            });

             if (window.Laravel.isLoggedin) {
          this.username = window.Laravel.user
          if(window.Laravel.role_type == 2){
            this.isAdmin = false
          }
          
            }
        },
        methods: {
            deletePost(id) {
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    let existingObj = this;
                    if(confirm("Do you really want to delete this data?")) {
                        this.$axios.delete(`/api/page/delete/${id}`)
                        .then(response => {
                            let i = this.posts.map(item => item.id).indexOf(id); // find index of your object
                            this.posts.splice(i, 1);
                            existingObj.strError = "";
                            existingObj.strSuccess = response.data.success;


                        })
                        .catch(function(error) {
                            existingObj.strError = "";
                            existingObj.strSuccess = error.response.data.message;
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