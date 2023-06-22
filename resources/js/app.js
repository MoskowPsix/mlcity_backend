import './bootstrap'

import {createApp} from 'vue'
import App from './app.vue'
import router from './router'
import axios from 'axios'
import { createPinia } from 'pinia'
import VueTailwindDatepicker from 'vue-tailwind-datepicker'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";



// Пока хз зачем, но вроде надо
axios.defaults.baseURL = 'http://localhost:8000/api/';

const options = {
    transition: "Vue-Toastification__bounce",
    maxToasts: 20,
    newestOnTop: true
};


createApp(App)
.use(Toast, options)
.use(router)
.use(VueTailwindDatepicker)
.use(createPinia())
.mount("#app")

