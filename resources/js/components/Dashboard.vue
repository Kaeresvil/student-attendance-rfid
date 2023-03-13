<template>
         <nav class="navbar navbar-expand-sm navbar-dark mb-4 " style="background-color: #FFA800;">
            <a v-if="isLoggedIn && !$route.path.includes('attendance')" class="navbar-brand mx-5 text-dark"   href="#">LOGO</a>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId"
                aria-expanded="false" aria-label="Toggle navigation"></button>
          <div class="navbar-nav"  v-if="isLoggedIn && !$route.path.includes('attendance')">
                <router-link to="/home" class="nav-item nav-link text-dark">Home</router-link>
                <router-link to="/students" class="nav-item nav-link text-dark">Students</router-link>
                 <router-link to="/events" class="nav-item nav-link text-dark">Events</router-link>
                 <router-link to="/reports" class="nav-item nav-link text-dark">Reports</router-link>
                 <div class="dropdown">
                <a  class="nav-item nav-link text-dark dropdown-toggle " href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                    User
                </a>

                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuLink">
                    <li> <router-link to="/users" class="dropdown-item">Users</router-link></li>
                    <li> <router-link to="/roles" class="dropdown-item">Roles</router-link></li>
                </ul>
                </div>
                <a class="nav-item nav-link " style="cursor: pointer; font-size: 17px; font-weight: 900; " @click="logout">LOGOUT</a>
            </div>

            <div v-else class="mx-auto">
                <h1>RFID Attendance Management System</h1>
            </div>


        </nav>
        <router-view></router-view>
</template>

<script >
import { defineComponent, ref, onMounted } from 'vue';
import axios from "../axios"
import { useRoute } from 'vue-router';

export default defineComponent({
components:{},
props: {
    isLog: {
        type: Boolean
    }
},

setup(props){

    const isLoggedIn = ref(false)
    const route = useRoute()
    var self = this;

    onMounted(() =>{
        isLoggedIn.value = window.window.Laravel.isLoggedin
    })
    

    
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
        logout
    }
}
})


</script>

<style scoped>
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
font-size: 17px;
}
.navbar-expand-sm .navbar-nav .nav-link {
    padding-right: 20px;
}
</style>
