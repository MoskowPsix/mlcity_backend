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
        },
        getLocationByCoords(coords) {
            const params = {
                latitude: coords[0],
                longitude: coords[1]
            }
            return from(axios.get(`locations/search/coords`, {params}))
        }
    },
})