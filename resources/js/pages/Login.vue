<template>
    <div class="main">
      <div style="padding-bottom: 100px">
        <div class="login-logo">
          <img class="center" style="width:300px;" src="/img/RFIDLOGO.png" />
        </div>
        <div
          class="section"
          style="
            padding: 25px;
            border-radius: 1rem;
            min-width: 400px;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
          "
        >
          <h4 class="card-title text-center mb-2" id="login">
            Sign in to start your session
          </h4>

          <div v-if="error !== null" class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                
                                    <strong>{{error}}</strong>
                                </div>
          <div>
            <span
              style="color: red; display: flex; margin-bottom: 10px"
              v-for="(error, index) in errors"
              :key="index"
            >
              {{ error[0] }}
            </span>
          </div>
          <a-form
            id="components-form-demo-normal-login"
            :model="userState"
            :labelCol="{ span: 30 }"
            :wrapperCol="{ span: 25 }"
            @finish="handleFinish"
          >
            <a-form-item>
              <a-input
                type="email"
                placeholder="Email"
                v-model:value="email"
                required
              >
                <template #prefix>
                  <UserOutlined style="color: rgba(0, 0, 0, 0.25)" />
                </template>
              </a-input>
            </a-form-item>
  
            <a-form-item>
              <a-input
                type="password"
                placeholder="Password"
                v-model:value="password"
                required
              >
                <template #prefix>
                  <LockOutlined style="color: rgba(0, 0, 0, 0.25)" />
                </template>
              </a-input>
            </a-form-item>
  
            <a-form-item>
              <a-button
                class="login-form-button"
                @click="handleSubmit"
                type="primary"
                :loading="issubmit"
              >
                Sign In
              </a-button>

              <div style="text-align: center; margin-top: 10px">
                <a @click="system()" target="_blank" class="cursor-pointer inline-flex items-center font-bold text-blue-500 hover:text-blue-700 text-xs text-center">
                                <span class="ml-2">Attendance System</span>
                            </a>
            </div>

            </a-form-item>
          </a-form>
        </div>
      </div>
    </div>
  </template>

<script>
import axios from "../axios"
import Swal from "sweetalert2";
    export default {
        data() {
            return {
                email: "",
                password: "",
                isOn: "",
                error: null,
                issubmit: false
            }
        },
        methods: {
            system (){
                  /// get system switch
            axios.get('/api/getswitch')
                .then(response => {
                    this.isOn =  response.data.isSet === 1? true:false
                    if(this.isOn){
                    this.$router.push('/attendance')
                    }else{
                        Swal.fire({
                    icon: "error",
                    title: 'Attendance System is not Available this time!',
                    text: 'Please try again later',
                    showConfirmButton: true,
                });     
                    }
                })
                .catch(function(error) {
                    console.log(error);
                }); 
               
            
            },
         
            handleSubmit(e) {
                e.preventDefault()
                if(this.password.length > 0) {
                    this.issubmit = true
                 axios.get('/sanctum/csrf-cookie').then(response => {
                     axios.post('api/login', {
                            email: this.email,
                            password: this.password
                        })
                        .then(response => {
                            if (response.data.success) {
                                this.$router.go('/reports')
                                this.issubmit = false
                            } else {
                                this.error = response.data.message
                                this.issubmit = false
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                            this.issubmit = false
                        });
                    })
                }
            }
        },
        
        beforeRouteEnter(to, from, next) {
            if (window.Laravel.isLoggedin) {
                return next('reports');
            }
            next();
        }
    }
</script>

<style lang="css" scoped>
html,
body {
  margin: 0px;
  height: 100%;
}

.login-logo {
  font-size: 25px;
  align-items: center;
  margin-bottom: 25px;
  font-weight: 300;
}

.login-logo a {
  color: #444;
}

.main {
  width: 100%;
  height: 100vh;
  margin-bottom: 30px;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #d2d6de;
}

.section {
  color: #fff;
  background: #2d2d2d;
  padding: 12px;
  margin-right: 4px;
  min-width: 400px;
  background: red;
  padding: 25px;
  background-color: #fff;
}

.login-form-button {
  width: 100%;
  background-color: #3c8dbc;
  color: white;
  font-weight: 300;
}

.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
}
</style>
