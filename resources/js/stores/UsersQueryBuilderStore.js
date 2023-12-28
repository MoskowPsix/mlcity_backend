import { defineStore, storeToRefs } from 'pinia'
import { useAuthStore } from './AuthStore'
import { useUsersFilterStore } from './UsersFilterStore'
import { catchError, map} from 'rxjs/operators'
import { of, EMPTY } from 'rxjs'


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
            useAuthStore().getUserForToken().pipe(
                map(response => {
                    this.userID = response.data.user.data.id
                }),
                catchError(err => {
                    return of(EMPTY)
                })
            ).subscribe()
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