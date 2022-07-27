<template>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">Add New Role</h5>
                <div>
                    <router-link :to="{name: 'role'}" class="btn btn-success">Go Back</router-link>
                </div>
            </div>

            <div v-if="strSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{strSuccess}}</strong>
            </div>

            <div v-if="strError" class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{strError}}</strong>
            </div>


            <form @submit.prevent="addPost" enctype="multipart/form-data">
                <div class="form-group mb-2">
                    <label>Name</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="role_name" placeholder="Enter post name">
                </div>

                <div class="form-group mb-2">
                    <label>Description</label><span class="text-danger"> *</span>
              <textarea class="form-control"  v-model="description" placeholder="Enter description"  style="height: 100px"></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-4 mb-4"> Add Post</button>

            </form>
            
        </div>
    </div>
</template>

<script>
export default{
    data() {
        return {
            role_name: '',
            description: '',
            strSuccess: '',
            strError: '',
        }
    },
    methods: {

        addPost(e) {
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
           let existingObj = this;
                const formData = new FormData();
                formData.append('role_name', this.role_name);
                formData.append('description', this.description);
                this.$axios.post('/api/page/role/add', formData)
                .then(response => {
                    // existingObj.strError = "";
                    // existingObj.strSuccess = response.data.success;
                      window.location.href = "/roles"
                })
                .catch(function(error) {
                    existingObj.strSuccess ="";
                    console.log(error)
                    existingObj.strError = "Duplicate Role! Please make a unique role";
                });
            });
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    },
}
</script>