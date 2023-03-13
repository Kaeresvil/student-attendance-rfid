<template>
    <div class="container">

        <div class="row d-flex justify-content-center align-items-center jutify-content-center">
            <div class="col-md-8">

                <div v-if="error !== null" class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                
                    <strong>{{error}}</strong>
                </div>
                
                <div class="min-h-screen flex flex-col items-center justify-center bg-gray-300">
                    <div class="flex flex-col bg-white shadow-md px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full max-w-md">
                        <div class="">
                            <form>
                                <div class="flex flex-col mb-6">
                                    <label for="email" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">E-Mail Address:</label>
                                    <div class="relative">
                                        <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                                            <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                            </svg>
                                        </div>
                                        <input id="email" type="email" class="text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400" v-model="email" required autofocus autocomplete="off" placeholder="E-Mail Address">
                                    </div>
                                </div>
                                <div class="flex flex-col mb-6">
                                    <label for="password" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">Password:</label>
                                    <div class="relative">
                                        <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                                            <span>
                                                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                            </span>
                                        </div>
                                        <input id="password" type="password" class="text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400" v-model="password" required autocomplete="off" placeholder="Password">
                                    </div>
                                </div>

                                <!-- <div class="flex items-center mb-6 -mt-4">
                                    <div class="flex ml-auto">
                                        <a href="#" class="inline-flex text-xs sm:text-sm text-blue-500 hover:text-blue-700">Forgot Your Password?</a>
                                    </div>
                                </div> -->

                                <div class="flex w-full">
                                    <button type="submit" @click="handleSubmit" class="flex items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-blue-600 hover:bg-blue-700 rounded py-2 w-full transition duration-150 ease-in">
                                        <span class="mr-2 uppercase">Login</span>
                                        <span>
                                            <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="flex justify-center items-center mt-6">
                            <a @click="system()" target="_blank" class="cursor-pointer inline-flex items-center font-bold text-blue-500 hover:text-blue-700 text-xs text-center">
                                <span class="ml-2">Attendance System</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card card-default">
                    <div class="card-header"><h5>Login</h5></div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control" v-model="email" required autofocus autocomplete="off" placeholder="Enter your email">
                                </div>
                            </div>


                            <div class="form-group row mt-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-8">
                                    <input id="password" type="password" class="form-control" v-model="password" required autocomplete="off" placeholder="Enter your password">
                                </div>
                            </div>

                            <div class="form-group row mt-1 mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-success" @click="handleSubmit">
                                        Login
                                    </button>
                                </div>
                            </div>

                            <button class="btn btn-success" @click="system()">
                                Attendance Stysem
                            </button>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
import axios from "../axios"
    export default {
        data() {
            return {
                email: "",
                password: "",
                error: null
            }
        },
        methods: {
            system (){
                this.$router.push('/attendance')
            },
         
            handleSubmit(e) {
                e.preventDefault()
                if(this.password.length > 0) {
                 axios.get('/sanctum/csrf-cookie').then(response => {
                     axios.post('api/login', {
                            email: this.email,
                            password: this.password
                        })
                        .then(response => {
                            if (response.data.success) {
                                this.$router.go('/home')
                            } else {
                                this.error = response.data.message
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                    })
                }
            }
        },
        
        beforeRouteEnter(to, from, next) {
            if (window.Laravel.isLoggedin) {
                return next('home');
            }
            next();
        }
    }
</script>