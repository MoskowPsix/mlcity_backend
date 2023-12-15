import axios from 'axios';
import { defineStore } from 'pinia';
import { from } from 'rxjs';
import { useToast } from "vue-toastification";


const toast = useToast()
export const useLocationStore = defineStore('useLocation', {
    state: () =>({
        
    }),
    actions: {
        getLocationsByName(name) {
            return from(axios.get(`location/name/${name}`))
        }

    },
})