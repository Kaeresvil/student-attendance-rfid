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
                                <h5 class="card-title">Create New Sections</h5>
                                <div>
                                    <button class="block mr-2 uppercase shadow bg-emerald-800 hover:bg-emerald-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" type="button" @click="this.$router.push('/sections')">Back</button>
                                </div>
                            </div>

                    </div>
                    
                    <div class="card-body">
                        <form @submit.prevent="updatePost" enctype="multipart/form-data">
                                <div class="form-group mb-2">
                                    <label>Section Name</label><span class="text-danger"> *</span>
                                    <input type="text" class="form-control" v-model="form.name" placeholder="Enter section name">
                                </div>

                                <div class="form-group mb-3">
                                    <label>Description</label><span class="text-danger"> *</span>
                                    <textarea class="form-control"  v-model="form.description" placeholder="Enter description"  style="height: 100px"></textarea>
                                </div>
                                <div class="form-group mb-2">
                                    <a-form-item label="Assigned Grade Level" name="location">
                                        <a-select show-search v-model:value="form.grade_level_id" id="role_id">
                                        <a-select-option v-for="grade in option_gradelevel" :key="grade.id" size="large">{{
                                            grade.grade_level
                                        }}
                                        </a-select-option>
                                        </a-select>
                                    </a-form-item>
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
import Swal from "sweetalert2";
import { useRoute, useRouter} from 'vue-router'
import router from '../../router';

export default defineComponent({
components:{},
setup(){
    const option_gradelevel = ref()
    const route = useRoute()
    const router = useRouter()
    const form = reactive({
        id: "",
        name: "",
        grade_level_id: "",
        description:"",
    })

    const handling = ()=>{
        if(route.path.includes("edit")){
            axios.post(`/api/page/section_update/${form.id}`,form)
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
            axios.post('/api/page/section/add',form)
            .then(response => { 
                Swal.fire({
                icon: "success",
                title: response.data.success,
                showConfirmButton: true,
                });
                form.name = '';           
                form.description = '';           
                form.grade_level_id = '';           
            })
            .catch(function (error) {
            console.log(error)
            });
        }

      
    }

    const deleteRecord = (id) =>{
                axios.delete(`/api/page/section_delete/${id}`)
                .then(response => {
                    Swal.fire({
                    icon: "success",
                    title: response.data.success,
                    showConfirmButton: true,
                    });
                    router.push('/sections')
                })
                .catch(function(error) {
                        console.log(error)
                });
    }
 

    onMounted(() =>{ 
        if(route.path.includes("edit")){
            let id = route.params.id
                   axios.get(`/api/page/section_show/${id}`)
                    .then(response => {
                        form.id = response.data.id
                        form.name = response.data.name
                        form.description = response.data.description
                        form.grade_level_id = response.data.grade_level_id
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
        }

        //get all grade
        axios.get('/api/page/all_grade_level/')
                    .then(response => {
                        option_gradelevel.value = response.data
                    })
                    .catch(function(error) {
                        console.log(error);
                    });






    })
    return {
        form,
        route,
        option_gradelevel,

        handling,
        deleteRecord
    }
}
})

   
</script>