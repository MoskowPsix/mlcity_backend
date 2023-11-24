import { defineStore } from 'pinia'
import { useAuthStore } from './AuthStore'


// const authStore = useAuthStore()
export const useFilterStore = defineStore('useFilter', {
    
    actions: {
        queryBuilder(func) {

        },
        updateParams() {
            
        }
    },
    state: () => ({
        userID: JSON.parse(localStorage.getItem("user") || '[]').id,
        users: {
            name: null,
            email: null,
            createdDateStart: new Date().toISOString().slice(0, 10),
            updatedDateStart:null,
            locationId: null
        },
        events: [

        ],
        sights: [

        ]
    }),
})