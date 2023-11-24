import { defineStore } from 'pinia'
import { useAuthStore } from './AuthStore'


// const authStore = useAuthStore()
export const useUsersQueryBuilderStore = defineStore('useUsersQueryBuilder', {
    
    actions: {
        queryBuilder(func) {
        this.updateParams()

        switch(func) {
            case 'usersForPageUsers':
                this.usersForPageUsers()
            break;
        }
        
        return this.queryParams
        },
        getUserId() {
            useAuthStore().getUserForToken()
            .then(response => {
                console.log(response)
                this.userID = user.data.id
            })
            .catch(err => {
                console.log(err)
            })
        },
        updateParams() {
            this.getUserId()
            this.name = null,
            this.email = null,
            this.createdDateStart = new Date().toISOString().slice(0, 10),
            this.updatedDateStart = null,
            this.locationId = null
        },
        usersForPageUsers() {
            this.name = null,
            this.email = null,
            this.createdDateStart = new Date().toISOString().slice(0, 10),
            this.updatedDateStart = null,
            this.locationId = null
        }
    },
    state: () => ({
        queryParams: [],
        userID: null,
        name: null,
        email: null,
        createdDateStart: new Date().toISOString().slice(0, 10),
        updatedDateStart:null,
        locationId: null
    }),
})