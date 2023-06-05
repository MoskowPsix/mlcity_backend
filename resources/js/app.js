import './bootstrap'

import {createApp} from 'vue'
import App from './app.vue'
import router from './router'
import axios from 'axios'
import { createPinia } from 'pinia'
import VueTailwindDatepicker from 'vue-tailwind-datepicker'


// Пока хз зачем, но вроде надо
axios.defaults.baseURL = 'http://localhost:8000/api/';
axios.defaults.headers['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

createApp(App)
.use(router)
.use(VueTailwindDatepicker)
.use(createPinia())
.mount("#app")

