<template>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center jutify-content-center">
            <div class="col-md-10">

                <div class="card card-default" :loading="false">

                    <div class="card-header">

                    <div class="d-flex justify-content-between ">
                                <h5 class="card-title">User Profile</h5>
                                <div>
                                    <button class="btn btn-lg buttonback" disabledtype="button" @click="this.$router.go(-1)">Back</button >
                                </div>
                            </div>

                    </div>
                    
                    <div class="card-body">
                   

                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control" name="name" v-model="form.name" required
                                           autofocus autocomplete="off"  placeholder="Enter your name">

                                 

                                </div>

                                
                               
                              </div>

                            <div class="form-group row mt-1">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-8 mt-2">
                                    <input id="email" type="email" class="form-control" name="email" v-model="form.email" required
                                           autofocus autocomplete="off" placeholder="Enter your email">
                                </div>
                            </div>


                            <div class="form-group row mt-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Old Password</label>
                                <div class="col-md-8 mt-2">
                                    <input :disabled="disable" id="password" type="password" class="form-control" name="password" v-model="form.old_password"
                                           required autocomplete="off" placeholder="Enter your old password">
                                </div>
                            </div>
                            <div class="form-group row mt-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>
                                <div class="col-md-8 mt-2">
                                    <input :disabled="disable" id="password" type="password" class="form-control" name="password" v-model="form.password"
                                           required autocomplete="off" placeholder="Enter your new password">
                                </div>
                            </div>

                              <div class="form-group row mt-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Cofirm Password</label>
                                <div class="col-md-8 mt-2">
                                    <input :disabled="disable" id="confirmpassword" type="password" class="form-control" name="confirmpassword" v-model="form.confirmpassword"
                                           required autocomplete="off" placeholder="Enter your password">
                                </div>
                            </div>

                              <div class="form-group row mt-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Role</label>
                                <div class="col-md-8 mt-2">
                                    <a-select disabled show-search v-model:value="form.role_type_id" id="role_id" class="form-select">
                                        <a-select-option v-for="grade in option_role" :key="grade.id" size="large">{{
                                            grade.role_name
                                        }}
                                        </a-select-option>
                                        </a-select>
                            </div>
                            </div>
                              <div class="form-group row mt-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Assigned Grade and Section</label>
                                <div class="col-md-8 mt-2">
                                    <a-select mode="multiple" disabled show-search v-model:value="form.section" id="role_id" class="form-select">
                                        <a-select-option v-for="grade in option_grade_section" :key="grade.id" size="large">{{
                                            grade.grade_level.grade_level + ' - ' + grade.name 
                                        }}
                                        </a-select-option>
                                        </a-select>
                            </div>
                            </div>

                            <div style="display:flex; justify-content: flex-end; margin-top: 10px;">
                                <div>
                                    <button  class="btn btn-lg buttonshow" @click="handling()">Update</button>

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
import axios from "../axios"
import Swal from "sweetalert2";
import { useRoute, useRouter} from 'vue-router'
import router from '../router';

export default defineComponent({
components:{},
setup(){
    const option_role = ref()
    const disable = ref(false)
    const option_grade_section = ref()
    const route = useRoute()
    const router = useRouter()
    const form = reactive({
      id: '',
      role_type_id: '',
      name: '',
      email: '',
      password: '',
      old_password: '',
      confirmpassword: '',
      section: [],
    })



    const handling = ()=>{
        const isAssigned = ref(false)
        if(form.section.length === 0){
            Swal.fire({
                icon: "error",
                title: 'Failed to process data',
                text: 'Grade and Section is Required!',
                showConfirmButton: true,
                }); 
                
                isAssigned.value = false;
        }else{
            isAssigned.value = true;
        }
if(isAssigned.value){
       
            axios.post(`/api/page/users/profileupdate/${form.id}`,form)
            .then(response => { 
                if(response.data.success == false){
                    Swal.fire({
                icon: "error",
                title: response.data.message,
                showConfirmButton: true,
                });   
                }else{
                Swal.fire({
                icon: "success",
                title: response.data.message,
                showConfirmButton: true,
               });
               form.confirmpassword = ''
               form.password = ''
               form.old_password = ''
            }         
            })
            .catch(function (error) {
            console.log(error)
            });

    }

    }


    onMounted(() =>{ 
            let id = route.params.id
            var list = []
                   axios.get(`/api/page/usersedit/${id}`)
                    .then(response => {
                        form.id = response.data.id
                        form.name = response.data.name
                        form.email = response.data.email
                        form.role_type_id = response.data.role_type_id
                        response.data.sections.forEach((item) => {
                        list.push(item.id)
                        }),
                        form.section = list
                        console.log(response.data);
                    })
                    .catch(function(error) {
                        console.log(error);
                    });

        //get role
            axios.get('/api/page/allroles')
            .then(response => {
                option_role.value = response.data    
            })
            .catch(function(error) {
                console.log(error);
            });
        //get grade and section
            axios.get('/api/page/all_section_noRestrict')
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
        option_role,
        option_grade_section,
        disable,

        handling
    }
}
})

   
</script>