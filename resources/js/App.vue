<template>
    <div class="container  mb-4  pt-2">
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark mb-4">
            <a class="navbar-brand mx-5"  href="#">Laravel Vue JS URP</a>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId"
                aria-expanded="false" aria-label="Toggle navigation"></button>
          <div class="navbar-nav mx-3" v-if="isLoggedIn">
                <router-link to="/posts" class="nav-item nav-link">Posts</router-link>
                <router-link to="/roles" class="nav-item nav-link">Roles</router-link>
                 <router-link to="/users" class="nav-item nav-link">Employees</router-link>
                <a class="nav-item nav-link text-warning " style="cursor: pointer; font-size: 16px; font-weight: 700; " @click="logout">Logout</a>
            </div>

        </nav>


        <router-view></router-view>
    </div>
</template>
<script>
    export default {
        name: "App",
        data() {
            return {
                isLoggedIn: false,
            }
        },
        created() {
            if (window.Laravel.isLoggedin) {
                this.isLoggedIn = true
            }
        },
        methods: {
            logout(e) {
                e.preventDefault()
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('/api/logout')
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
        },
    }
</script>