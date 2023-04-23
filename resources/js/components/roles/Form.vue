<template>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center jutify-content-center">
            <div class="col-md-10">
                <!-- <div v-if="formError !== null" class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    <strong>{{formError}}</strong>
                </div> -->

                <div class="card card-default card_form">

                    <div class="card-header">

                    <div class="d-flex justify-content-between ">
                                <h5 class="card-title">Create New Role</h5>
                                <div>
                                    <button class="block mr-2 uppercase shadow bg-emerald-800 hover:bg-emerald-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" type="button" @click="this.$router.push('/roles')">Back</button>
                                </div>
                            </div>

                    </div>
                    
                    <div class="card-body">
                        <form @submit.prevent="updatePost" enctype="multipart/form-data">
                                <div class="form-group mb-2">
                                    <label>Name</label><span class="text-danger"> *</span>
                                    <input type="text" class="form-control" v-model="form.role_name" placeholder="Enter role name">
                                </div>

                                <div class="form-group mb-2">
                                    <label>Code</label><span class="text-danger"> *</span>
                                    <input type="text" :disabled="isdisabled" class="form-control" v-model="form.code" placeholder="Enter role code">
                                </div>
      

                                <div class="form-group mb-2">
                                    <label>Description</label><span class="text-danger"> *</span>
                                    <textarea class="form-control"  v-model="form.description" placeholder="Enter description"  style="height: 100px"></textarea>
                                </div>

                                <div class="w-full flex justify-end">
                                    <div>
                                        <button type="submit" class="mr-2 mt-2 uppercase shadow bg-blue-800 hover:bg-blue-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" @click="handling()">{{$route.path.includes('edit') ? 'Update':'Create'}}</button>

                                        <button v-if="$route.path.includes('edit')" type="submit" class="mr-2 mt-2 uppercase shadow bg-red-800 hover:bg-red-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" @click="deleteRecord($route.params.id)">Delete</button>
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
import { defineComponent, ref, onMounted, reactive } from 'vue';
import axios from "../../axios"
import { useRoute, useRouter} from 'vue-router'
import Swal from "sweetalert2";

export default defineComponent({
components:{},
setup(){
    const route = useRoute()
    const router = useRouter()
    const isdisabled = ref(false)
    const form = reactive({
        id: "",
        role_name: "",
        code: "",
        description:"",
    })

    const handling = ()=>{
        if(route.path.includes("edit")){
            axios.post(`/api/page/roleupdate/${form.id}`,form)
            .then(response => { 
                Swal.fire({
                icon: "success",
                title: response.data.success,
                showConfirmButton: true,
               });         
            })
            .catch(function (error) {
            console.log(error)
            });

        }else{
            axios.post('/api/page/role/add',form)
            .then(response => { 
                Swal.fire({
            icon: "success",
            title: 'Successfully Created',
            showConfirmButton: true,
          });   
                 form.role_name = '';           
                 form.code = '';           
                form.description = '';                            
            })
            .catch(function (error) {
            console.log(error)
            });
        }

      
    }

    const deleteRecord = (id) =>{
                axios.delete(`/api/page/roledelete/${id}`)
                .then(response => {
                    Swal.fire({
                    icon: "success",
                    title: response.data.success,
                    showConfirmButton: true,
                    });
                    router.push('/roles')
                })
                .catch(function(error) {
                        console.log(error)
                });
    }
 

    onMounted(() =>{ 
        if(route.path.includes("edit")){
            let id = route.params.id
                   axios.get(`/api/page/roleedit/${id}`)
                    .then(response => {
                        form.id = response.data.id
                        form.role_name = response.data.role_name
                        form.code = response.data.code
                        form.description = response.data.description
                        isdisabled.value = true
                        console.log(response.data);
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
        }

    })
    return {
        form,
        isdisabled,

        handling,
        deleteRecord
    }
}
})

   
</script>