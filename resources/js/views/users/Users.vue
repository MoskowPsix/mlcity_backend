<template lang="">
    <div>
        <UsersFilter/>
        Users
    </div>
</template>
<script>
import { mapActions, mapState } from 'pinia'
import { useUsersStore } from './../../stores/UsersStore'
import { useUsersQueryBuilderStore } from './../../stores/UsersQueryBuilderStore'
import { useLoaderStore } from '../../stores/LoaderStore';
import { catchError, tap, map, retry, delay} from 'rxjs/operators';
import { Subject, of, EMPTY } from 'rxjs';
import { useToastStore } from '../../stores/ToastStore'
import { MessagesUsers } from '../../enums/users_messages'
import { useUsersFilterStore } from '../../stores/UsersFilterStore';
import UsersFilter from '../../components/filters/users_filters/UsersFilter.vue'


export default {
    name: 'Users',
    data() {
        return {
            users: null,
            // destroy$: new Subject()
        }
    },
    computed: {
        ...mapState(useUsersFilterStore, ['name'])
    },
    components: {
        UsersFilter
    },
    methods: {
        ...mapActions(useUsersStore, ['getUsers']),
        ...mapActions(useUsersQueryBuilderStore, ['queryBuilder']),
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useToastStore, ['showToast']),
        getAllUsers() {
            this.openLoaderFullPage()
            this.getUsers(this.queryBuilder('usersForPageUsers')).pipe(
                retry(3),
                delay(100),
                tap(()=> {this.closeLoaderFullPage()}),
                map(response => {
                    console.log(response)
                    response.data.users.data.length ? this.users = response.data.users : this.showToast(MessagesUsers.success_info_users_null, 'info')
                }),
                catchError(err => {
                    this.closeLoaderFullPage()
                    this.users = ''
                    console.log(err.messages)
                    399 < err.response.status && err.response.status < 500 ? this.showToast(MessagesUsers.warning_users + ': ' + err.message, 'warning') : null
                    499 < err.response.status && err.response.status < 600 ? this.showToast(MessagesUsers.error_users + ': ' + err.message, 'error') : null
                    return of(EMPTY)
                }),
                // takeUntil(this.destroy$)
            ).subscribe()
        }

    },
    mounted() {
        this.openLoaderFullPage(),
        this.getAllUsers()
    },
    watch: {
        name(newName, oldName) {
            this.getAllUsers()
        }
    },

}
</script>
<style lang="">
    
</style>