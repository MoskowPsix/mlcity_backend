import axios from 'axios';
import { defineStore } from 'pinia';
import { from } from 'rxjs';



export const useSightStore = defineStore('useSight', {
    actions: {
        getSights(params)  {
            return from(axios.get('sights', {params}))
        },
        getSightForIds(id) {
            return from(axios.get(`sights/${id}`))
        }
    },
})