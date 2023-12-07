import {createApp} from 'vue'
import App from './app.vue'
import router from './routes'
import axios from 'axios'
import { createPinia } from 'pinia'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import 'vue3-carousel/dist/carousel.css'



axios.defaults.baseURL = '/api/';
axios.defaults.headers = {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`
}


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

