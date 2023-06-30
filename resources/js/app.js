import {createApp} from 'vue'
import App from './app.vue'
import router from './router'
import axios from 'axios'
import { createPinia } from 'pinia'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";


axios.defaults.baseURL = '/api/';
axios.defaults.headers = {'Authorization': `Bearer ${localStorage.token}`}

const options = {
    transition: "Vue-Toastification__bounce",
    maxToasts: 20,
    newestOnTop: true
};


createApp(App)
.use(Toast, options)
.use(router)
.use(createPinia())
.mount("#app")

