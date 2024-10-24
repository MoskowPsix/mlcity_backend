// import store from './store'
import { createApp, ref } from 'vue'
import App from './app.vue'
import router from './routes'
import axios from 'axios'
import { createPinia } from 'pinia'
import { useDark } from '@vueuse/core'
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'
import 'vue3-carousel/dist/carousel.css'
import helpers from './helpers'
// import '../css/app.css'

axios.defaults.baseURL = '/api/'
axios.defaults.headers = {
    'Content-Type': 'application/json',
    Authorization: `Bearer ${localStorage.getItem('token')}`,
}

const options = {
    transition: 'Vue-Toastification__bounce',
    maxToasts: 20,
    newestOnTop: true,
}

const app = createApp(App)

app.config.globalProperties.$helpers = helpers
app.config.globalProperties.$theme = ref(useDark())
app.use(Toast, options)
app.use(router)
// app.use(store)
app.use(createPinia())
app.mount('#app')
