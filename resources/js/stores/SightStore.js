import axios from 'axios'
import { defineStore } from 'pinia'
import { from } from 'rxjs'

export const useSightStore = defineStore('useSight', {
    actions: {
        getSights(params) {
            return from(axios.get('sights', { params }))
        },
        getSightsForAuthor(params) {
            return from(axios.get(`sights-for-author`, { params }))
        },
        getSightForIds(id) {
            return from(axios.get(`sights/${id}`))
        },

        saveSightHistory(data) {
            return from(
                axios.post('history-content', data, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                }),
            )
        },
        changeStatus(statusName, sigthId, description = " "){
            const params = {
                status: statusName,
                sight_id: sigthId,
                description: description
            }
            console.log(params)
            return from(axios.post("sights/statuses", params))
        }
    },
})
