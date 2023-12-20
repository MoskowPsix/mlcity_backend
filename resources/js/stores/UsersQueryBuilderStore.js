import { defineStore, storeToRefs } from 'pinia'
import { useAuthStore } from './AuthStore'
import { useUsersFilterStore } from './UsersFilterStore'
import { BehaviorSubject } from 'rxjs';


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
            .then(user => {
                this.userID = user.data.id
            })
            .catch(err => {
                console.log(err)
            })
        },
        updateParams() {
            this.getUserId()
            this.name = useUsersFilterStore().getName(),
            this.email = useUsersFilterStore().getEmail(),
            this.createdDate = useUsersFilterStore().getCreatedDate(),
            this.updatedDate = useUsersFilterStore().getUpdatedDate(),
            this.locationId = useUsersFilterStore().getLocation()
        },
        usersForPageUsers() {
            // console.log(this.createdDate)
            // console.log(this.updatedDate)
            let createdDate = ['','']
            let updatedDate = ['','']
            if (this.createdDate) {
                createdDate = this.createdDate.split('~')
                console.log(createdDate)
            }
            if (this.updatedDate) {
                updatedDate = this.updatedDate.split('~')
            }
            
            this.queryParams = {
                name: this.name,
                email: this.email,
                createdDateStart: createdDate[0],
                createdDateEnd: createdDate[1],
                updatedDateStart: updatedDate[0],
                updatedDateEnd: updatedDate[1],
                page: this.pageUsersForPageUsers
            }
            // console.log(this.queryParams)
        },
        setPageUsersForPageUsers(page) {
            this.pageUsersForPageUsers = page
        },
    },
    state: () => ({
        queryParams: [],
        userID: null,
        name: null,
        email: null,
        createdDateStart: [],
        updatedDateStart: [],
        locationId: null,
        pageUsersForPageUsers: null,
    }),
})