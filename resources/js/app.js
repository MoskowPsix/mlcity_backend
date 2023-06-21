import './bootstrap'

import {createApp} from 'vue'
import App from './app.vue'
import router from './router'
import axios from 'axios'
import { createPinia } from 'pinia'
import VueTailwindDatepicker from 'vue-tailwind-datepicker'
import Toast from "vue-toastification";
import YmapPlugin from 'vue-yandex-maps'
import "vue-toastification/dist/index.css";



// Пока хз зачем, но вроде надо
axios.defaults.baseURL = 'http://localhost:8000/api/';

const options = {
    transition: "Vue-Toastification__bounce",
    maxToasts: 20,
    newestOnTop: true
};

const settings_yandex_map = {
    apiKey: '226cca4a-d7de-46b5-9bc8-889f70ebfe64',
    lang: 'ru_RU',
    coordorder: 'latlong',
    enterprise: false,
    version: '2.1'
  }

createApp(App)
.use(Toast, options)
.use(YmapPlugin, settings_yandex_map)
.use(router)
.use(VueTailwindDatepicker)
.use(createPinia())
.mount("#app")

