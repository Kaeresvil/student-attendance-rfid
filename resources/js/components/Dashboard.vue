<template>
         <nav class="navbar navbar-expand-sm navbar-dark mb-4 p-0 " style="background-color: #FFA800;">
            <a v-if="isLoggedIn && !$route.path.includes('attendance')" class="navbar-brand mx-5 text-dark "   href="#">
                <img style="width:90px;" class="lg:w-20 lg:h-20  overflow-hidden" src="/img/RFIDLOGO.png"/>
            </a>
            <!-- <button class="navbar-toggler d-lg-none border" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId"
                aria-expanded="false" aria-label="Toggle navigation"></button> -->

            <div class="navbar-nav "  v-if="isLoggedIn && !$route.path.includes('attendance') && loading">
                <!-- <router-link to="/home" class="nav-item nav-link text-dark">Home</router-link> -->
                <router-link to="/students" class="nav-item nav-link text-dark">Students</router-link>
                <router-link v-if="isHeadTeacher || isAdmin" to="/events" class="nav-item nav-link text-dark">Events</router-link>
                <router-link to="/reports" class="nav-item nav-link text-dark">Reports/Attendance</router-link>
                <router-link to="/roles" class="nav-item nav-link text-dark">Roles</router-link>
                <router-link to="/grades" class="nav-item nav-link text-dark">Grade Level</router-link>
                <router-link to="/sections" class="nav-item nav-link text-dark">Sections</router-link>
                <router-link to="/users" class="nav-item nav-link text-dark">Users</router-link>
                <a @click="editRecord" to='' class="nav-item nav-link text-dark">{{authUser.name}} (Profile)</a>
                <!-- <div class="dropdown">
                <a  class="nav-item nav-link text-dark dropdown-toggle " href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                    Settings
                </a>

                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuLink">
                    <li> <a @click="editRecord" to='' class="dropdown-item">{{authUser.name}} (Profile)</a></li>
                    <li> <router-link v-if="isAdmin"  to="/users" class="dropdown-item">Users</router-link></li>
                    <li> <router-link v-if="isAdmin" to="/roles" class="dropdown-item">Roles</router-link></li>
                    <li> <router-link v-if="isAdmin" to="/grades" class="dropdown-item">Grade Level</router-link></li>
                    <li> <router-link v-if="isAdmin" to="/sections" class="dropdown-item">Sections</router-link></li>
                </ul>
                </div> -->
                <a class="nav-item nav-link " style="cursor: pointer; font-size: 17px; font-weight: 900; " @click="logout">LOGOUT</a>
            </div>

            <div v-else class="mx-auto">
                <h1 class="RFIDTitle">RFID Attendance Management System</h1>
            </div>


        </nav>
        <router-view></router-view>
</template>

<script >
import { defineComponent, ref, onMounted, computed} from 'vue';
import axios from "../axios"
import { useRoute,useRouter } from 'vue-router';

export default defineComponent({
components:{},
props: {
    isLog: {
        type: Boolean
    }
},

setup(props){

    const isLoggedIn = ref(false)
    const loading = ref(false)
    const isAdmin = ref(false)
    const isHeadTeacher = ref(false)
    const route = useRoute()
    const router = useRouter()
    const authUser = ref()
    var self = this;

    onMounted(() =>{
        isLoggedIn.value = window.window.Laravel.isLoggedin
        if(!route.path.includes('/attendance')){
        axios.get('/api/page/auth_user')
                    .then(response => {
                        console.log(response.data)
                        isAdmin.value = response.data.role.code === 'Administrator' ? true:false
                        isHeadTeacher.value = response.data.role.code === 'Head Teacher' ? true:false
                        authUser.value = response.data
                        loading.value = true
                    })
                    .catch(function (error) {
                       false
                    })
                }

    })

    
    const editRecord = () => {
            router.push({path: '/profile/' +  authUser.value.id,
            })
    }

    
    const logout = (e)=>{

                e.preventDefault()
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/api/logout')
                    .then(response => {
                        if(response.data.success) {
                            window.location.href = "/"
                        } else {
                            console.log(response);
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
                })

    }
    return {
        isLoggedIn,
        isAdmin,
        isHeadTeacher,
        authUser,
        loading,
        logout,
        editRecord
    }
}
})


</script>

<style scoped>
.RFIDTitle{
    font-size: 33px;
    font-weight: bold;
    margin-top: 2px;
}
.logout{
    color: black;
    font-weight: 600;
}

.navbar-nav {
    margin-left: auto;
}
.nav-item  {
color: #0e0e0e;
font-weight: bold;
font-size: 15px;
}
.navbar-expand-sm .navbar-nav .nav-link {
    padding-right: 20px;
}
</style>
