import axios from 'axios';
import router from "./router/index.js";
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

window.axios.interceptors.response.use({}, error => {
    if (error.response.status === 401 || error.response.status === 419) {
        localStorage.removeItem('x_xsrf_token')
        router.push({ name: 'users.login' })
    }
    throw error
})
