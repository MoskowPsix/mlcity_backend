import axios from 'axios';
import { defineStore } from 'pinia';
import { from } from 'rxjs';



export const useEventStore = defineStore('useEvent', {
    actions: {
        getEvents(params)  {
            return from(axios.get('events', {params}))
        },
        getEventForIds(id) {
            return from(axios.get(`events/${id}`))
        }
    },
})