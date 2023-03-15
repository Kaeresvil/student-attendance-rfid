<template>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center jutify-content-center">
            <div class="col-md-10">

                <div class="card card-default">

                    <div class="card-header">

                    <div class="d-flex justify-content-between ">
                                <h5 class="card-title">Create New Event</h5>
                                <div>
                                    <button class="block mr-2 uppercase shadow bg-emerald-800 hover:bg-emerald-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" type="button" @click="this.$router.push('/events')">Back</button>
                                </div>
                            </div>

                    </div>
                    
                    <div class="card-body">
                   

                            <div class="form-group row">
                                <label for="event_name" class="col-sm-4 col-form-label text-md-right">Event Name</label>
                                <div class="col-md-8">
                                    <input id="event_name" type="text" class="form-control lrn" name="event_name" v-model="form.event_name" required placeholder="Provide Event Name">
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <label for="event_date" class="col-sm-4 col-form-label text-md-right">Date</label>
                                <div class="col-md-8 mt-2">
                                    <input id="event_date" type="date" class="form-control" name="event_date" v-model="form.event_date" required >
                                </div>
                            </div>


                            <div class="form-group row mt-1">
                                <label for="description" class="col-md-4 col-form-label text-md-right">Event Description</label>
                                <div class="col-md-8 mt-2">
                                    <textarea id="event_description" rows="4" name="event_description" class="form-control" v-model="form.event_description" required placeholder="Event Description"></textarea>
                                </div>
                            </div>

                            <div class="w-full flex justify-end mt-2">
                                <div>
                                    <button class="mr-2 mt-2 uppercase shadow bg-blue-800 hover:bg-blue-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" @click="handling()">{{$route.path.includes('edit') ? 'Update':'Create'}}</button>

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
        event_name: '',
        event_date: '',
        event_description: ''
        })

        const handling = ()=>{
            if(route.path.includes("edit")){
                axios.post(`/api/page/event/update/${form.id}`,form)
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
                axios.post('/api/page/event/add',form)
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
                        form.event_name = '',
                        form.event_date = '',
                        form.event_description = ''
                    }
                        
                })
                .catch(function (error) {
                    console.log(error)
                });
            }
        }

        const deleteRecord = (id) =>{
                    axios.delete(`/api/page/eventdelete/${id}`)
                    .then(response => {
                        Swal.fire({
                        icon: "success",
                        title: response.data.success,
                        showConfirmButton: true,
                        });
                        router.push('/events')
                    })
                    .catch(function(error) {
                            console.log(error)
                    });
        }
    

        onMounted(() =>{ 
            if(route.path.includes("edit")){
                let id = route.params.id
                       axios.get(`/api/page/eventedit/${id}`)
                        .then(response => {
                            form.event_name = response.data.event_name
                            form.event_date = response.data.event_date
                            form.event_description = response.data.event_description

                            console.log(response.data);
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
            }
        })
        return {
            form,
            route,
            disable,
            active,
            handling,
            deleteRecord
        }
    }
})

   
</script>