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
                                <h5 class="card-title">Create New Grade Level</h5>
                                <div>
                                    <button class="block mr-2 uppercase shadow bg-emerald-800 hover:bg-emerald-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" type="button" @click="this.$router.push('/grades')">Back</button>
                                </div>
                            </div>

                    </div>
                    
                    <div class="card-body">
                        <form @submit.prevent="updatePost" enctype="multipart/form-data">
                                <div class="form-group mb-2">
                                    <label>Grade Level</label><span class="text-danger"> *</span>
                                    <input type="text" class="form-control" v-model="form.grade_level" placeholder="Enter grade level">
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
    const form = reactive({
        id: "",
        grade_level: "",
        description:"",
    })

    const handling = ()=>{
        if(route.path.includes("edit")){
            axios.post(`/api/page/grade_level_update/${form.id}`,form)
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
            axios.post('/api/page/grade_level/add',form)
            .then(response => { 
                Swal.fire({
            icon: "success",
            title: 'Successfully Created',
            showConfirmButton: true,
          });   
                 form.grade_level = '';           
                form.description = '';                            
            })
            .catch(function (error) {
            console.log(error)
            });
        }

      
    }

    const deleteRecord = (id) =>{
                axios.delete(`/api/page/grade_level_delete/${id}`)
                .then(response => {
                    Swal.fire({
                    icon: "success",
                    title: response.data.success,
                    showConfirmButton: true,
                    });
                    router.push('/grades')
                })
                .catch(function(error) {
                        console.log(error)
                });
    }
 

    onMounted(() =>{ 
        if(route.path.includes("edit")){
            let id = route.params.id
                   axios.get(`/api/page/grade_level_show/${id}`)
                    .then(response => {
                        form.id = response.data.id
                        form.grade_level = response.data.grade_level
                        form.description = response.data.description
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
        }

    })
    return {
        form,

        handling,
        deleteRecord
    }
}
})

   
</script>