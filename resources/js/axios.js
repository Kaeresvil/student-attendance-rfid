import axios from "axios";
import router from './router'


const api = process.env.VUE_APP_BASEURL;
const axiosRequest = axios.create({
    baseURL: api,
    timeout: 20000,
    // headers: {
    //     Authorization: `Bearer ${window.localStorage.getItem("PPORT_TOKEN")}`,
    //     // Content-Type: `multipart/form-data`
    //     // account: window.localStorage.getItem("selectedAccount"),
    //     // accountIndex: window.localStorage.getItem("accountIndex")
    // }
});

// RESPONSE
// Add a response interceptor
axiosRequest.interceptors.response.use(
    function(response) {
        // Do something with response data
        return response;
    },
    function(error) {
        // Do something with response error
console.log('error',error.response)
        if (error.response.status == 401) {
            window.location.href = "/"
           
        }
     

        return Promise.reject(error);
    }
);

export default axiosRequest;
// function else if() {
//     throw new Error("Function not implemented.");
// }

