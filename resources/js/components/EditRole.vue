<template>
  <div v-if="error !== null" class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    <strong>{{error}}</strong>
                </div>
   <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">Edit Role</h5>
                <div>
                    <router-link :to="{name: 'role'}" class="btn btn-success">Go Back</router-link>
                </div>
            </div>

            <form @submit.prevent="updatePost" enctype="multipart/form-data">
                <div class="form-group mb-2">
                    <label>Name</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="name" placeholder="Enter post name">
                </div>

                <div class="form-group mb-2">
                    <label>Description</label><span class="text-danger"> *</span>
              <textarea class="form-control"  v-model="description" placeholder="Enter description"  style="height: 100px"></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-4 mb-4"> Edit Post</button>

            </form>
            
        </div>
    </div>
</template>

<script>
export default{
    data() {
        return {
            name: '',
            description: '',
            error: null,
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/page/roleedit/${this.$route.params.id}`)
            .then(response => {
                this.name = response.data['role_name'];
                this.description = response.data['description'];

            })
            .catch(function(error) {
                console.log(error);
            });
        })
    },
    methods: {

        updatePost(e) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let currentObj = this;
                     this.$axios.post(`/api/page/roleupdate/${this.$route.params.id}`, {
                            name: this.name,
                            description: this.description,
                        })
                .then(response => {
                    console.log(response)
                      if (response.data.status == 200) {
                                 window.location.href = "/roles"
                            } else {
                                this.error = response.data.message
                            }
                })
                .catch(function(error) {
              console.log(error)
              currentObj.error = "Name role duplicated! make it unique please."
                });
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