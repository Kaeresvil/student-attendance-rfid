import axios from "axios";
import router from './router'
import Swal from "sweetalert2";


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
        if (error.response.status == 401) {
            window.location.href = "/"
           
        }

        if (error.response.status == 422) {
            const item = error.response.data.errors;
            let errors = "";
            for (const key in item) {
                errors += `${item[key]}`;
                break
            }

            Swal.fire({
                icon: "error",
                title: 'Failed to process data!',
                text: errors,
                showConfirmButton: true,
                });
        }
     

        return Promise.reject(error);
    }
);

export default axiosRequest;
// function else if() {
//     throw new Error("Function not implemented.");
// }

