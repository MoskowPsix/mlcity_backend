import axios from 'axios'
import { defineStore } from 'pinia'
import { from } from 'rxjs'

export const usePlaceStore = defineStore('usePlace', {
    state: () => ({}),
    actions: {
        getPlaces(id, params) {
            return from(axios.get(`events/${id}/places`, { params }))
        },
    },
})
