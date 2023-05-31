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
                                    <button class="btn btn-lg buttonback" type="button" @click="this.$router.push('/grades')">Back</button>
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

                                <div style="display:flex; justify-content: flex-end; margin-top: 10px;">
                                <div>
                                    <button style="margin-right: 5px;" class="btn btn-lg buttonshow" @click="handling()">{{$route.path.includes('edit') ? 'Update':'Create'}}</button>

                                        <button v-if="$route.path.includes('edit')" type="submit" class="btn btn-lg buttondelete" @click="deleteRecord($route.params.id)">Delete</button>
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
import { defineComponent, ref, onMounted, reactive, createVNode } from 'vue';
import axios from "../../axios"
import { useRoute, useRouter} from 'vue-router'
import { Modal } from 'ant-design-vue';
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';
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


        Modal.confirm({
        title: 'Reminder!',
        icon: createVNode(ExclamationCircleOutlined),
        content: 'If you delete this grade level all attendance reports, students that is tag to this grade level will also be deleted.',
        onOk() {
          return new Promise((resolve, reject) => {
            axios.delete(`/api/page/grade_level_delete/${id}`)
                .then(response => {
                    setTimeout(Math.random() > 0.5 ? resolve : reject, 500);
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

          }).catch(() => console.log('Oops errors!'));
        },

        onCancel() {},
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