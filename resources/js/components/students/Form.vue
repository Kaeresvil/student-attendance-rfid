<template>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center jutify-content-center">
            <div class="col-md-10">

                <div class="card card-default">

                    <div class="card-header">

                    <div class="d-flex justify-content-between ">
                                <h5 class="card-title">Create New Student</h5>
                                <div>
                                    <button class="block mr-2 uppercase shadow bg-emerald-800 hover:bg-emerald-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" type="button" @click="this.$router.push('/students')">Back</button>
                                </div>
                            </div>

                    </div>
                    
                    <div class="card-body">
                   

                            <div class="form-group row">
                                <label for="rfid" class="col-sm-4 col-form-label text-md-right">Learner Reference Number (LRN) <p style="color: red; font-size: 12px;">If RFID cant read LRN please click on the right side field!</p></label>
                                <div class="col-md-8">
                                    <input style="{border: active ? '1px solid red' : '1px solid black'}" id="rfid" type="text" class="form-control lrn" name="rfid" v-model="form.lrn" required
                                            placeholder="Tap RFID to provide LRN">
                                </div>

                                
                               
                              </div>

                            <div class="form-group row mt-1">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-8 mt-2">
                                    <input id="name" type="text" class="form-control" name="name" v-model="form.name" required placeholder="Enter full name">
                                </div>

                            </div>


                            <div class="form-group row mt-1">
                                <label for="address" class="col-md-4 col-form-label text-md-right">Address</label>
                                <div class="col-md-8 mt-2">
                                    <input :disabled="disable" id="address" type="text" class="form-control" name="address" v-model="form.address"
                                           required autocomplete="off" placeholder="Enter address">
                                </div>
                            </div>
                              <div class="form-group row mt-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Grade Level and Section</label>
                                <div class="col-md-8 mt-2">
                                    <a-select show-search v-model:value="form.grade_section_id" id="role_id" class="form-select">
                                        <a-select-option v-for="grade in option_grade_section" :key="grade.id" size="large">{{
                                          grade.grade_level.grade_level + ' - ' + grade.name 
                                        }}
                                        </a-select-option>
                                        </a-select>
                            </div>
                            </div>

                            <div class="w-full flex justify-end mt-2">
                                <div>
                                    <button class="mr-2 mt-2 uppercase shadow bg-blue-800 hover:bg-blue-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" @click="handling()">{{$route.path.includes('edit') ? 'Update':'Register'}}</button>

                                    <button v-if="$route.path.includes('edit')" class="mr-2 mt-2 uppercase shadow bg-red-800 hover:bg-red-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" @click="deleteRecord($route.params.id)">Delete</button>
                                </div>
                            </div>

                           


                
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
    const disable = ref(false)
    const active = ref(true)
    const option_grade_section = ref()
    const route = useRoute()
    const router = useRouter()
    const form = reactive({
      id: '',
      lrn: '',
      name: '',
      address: '',
      grade_section_id:''
    })

    const autofocusFields = ()=>{
        var el = document.getElementsByClassName("lrn")[0];
                hideKeyboard(el);

                function hideKeyboard(el) {
                setTimeout(function() {
                    el.blur(); //close the keyboard
                    el.focus(); //focus on the input
                }, 1000);
                }
    }
    const handling = ()=>{
        const isAssigned = ref(false)
        if(form.lrn == ''){
            const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                    })

                    Toast.fire({
                    icon: 'error',
                    title: 'Failed to Process Data!',
                    text: 'LRN is required!'
                    })

                    autofocusFields()

        }else{
            isAssigned.value = true;
        }
if(isAssigned.value){
        if(route.path.includes("edit")){
            axios.post(`/api/page/student/update/${form.id}`,form)
            .then(response => { 
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                    })

                    Toast.fire({
                    icon: 'success',
                    title: 'Student Updated Successfully'
                    })        
            })
            .catch(function (error) {
            console.log(error)
            });

        }else{
            axios.post('/api/page/student/add',form)
            .then(response => { 
                if(response.data.success == false){
                    Swal.fire({
                icon: "error",
                title: response.data.success,
                showConfirmButton: true,
                });   
                }else{
                    const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                    })

                    Toast.fire({
                    icon: 'success',
                    title: 'Student Created Successfully'
                    })
                form.id = '',
                form.name = '',
                form.lrn = '',
                form.address = '',
                form.grade_section_id = ''
                autofocusFields()
                
                }
                     
            })
            .catch(function (error) {
            console.log(error)
            });
        }
    }
    

    }

    const deleteRecord = (id) =>{
                axios.delete(`/api/page/studentdelete/${id}`)
                .then(response => {
                    Swal.fire({
                    icon: "success",
                    title: response.data.success,
                    showConfirmButton: true,
                    });
                    router.push('/students')
                })
                .catch(function(error) {
                        console.log(error)
                });
    }
 

    onMounted(() =>{ 
        if(route.path.includes("edit")){
            let id = route.params.id
                   axios.get(`/api/page/studentedit/${id}`)
                    .then(response => {
                        form.id = response.data.id
                        form.name = response.data.name
                        form.address = response.data.address
                        form.grade_section_id = response.data.grade_section_id
                        form.lrn = response.data.lrn

                        console.log(response.data);
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
        }
        if(route.path.includes("create")){
        autofocusFields()
        }
        //get grade and section
            axios.get('/api/page/all_section')
            .then(response => {
                option_grade_section.value = response.data
            })
            .catch(function(error) {
                console.log(error);
            });

    })
    return {
        form,
        route,
        option_grade_section,
        disable,
        active,

        handling,
        deleteRecord
    }
}
})

   
</script>