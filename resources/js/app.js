import {createApp} from 'vue';

require('./bootstrap');


import App from './App.vue';
import axios from 'axios';
import router from './router';
import Antd from "ant-design-vue";
import 'ant-design-vue/dist/antd.css';
import {
 EyeFilled
} from '@ant-design/icons-vue'

const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.use(router);
app.use(Antd)
app.component('EyeFilled', EyeFilled)
app.mount('#app');