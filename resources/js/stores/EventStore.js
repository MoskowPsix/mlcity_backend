import axios from 'axios'
import { defineStore } from 'pinia'
import { from } from 'rxjs'

export const useEventStore = defineStore('useEvent', {
    actions: {
        getEvents(params) {
            return from(axios.get('events', { params }))
        },
        getEventsForAuthor(params) {
            return from(axios.get(`events-for-author`, { params }))
        },
        getEventForIds(id) {
            return from(axios.get(`events/${id}`))
        },
        changeStatus(statusName, eventId, descriptions = ' ') {
            const params = {
                status: statusName,
                event_id: eventId,
                descriptions: descriptions,
            }
            return from(axios.post(`events/statuses`, params))
        },
    },
})
