<template lang="">
    <div class="p-2">
        <!-- Модальное окно -->
        <UserModal v-if="user" :user="user" @onClose="onCloseModal"/>
        <!-- Фильтры -->
        <UsersFilter/>
        <!-- Список пользователей -->
        <UsersTable v-if="users" :users="users" @user="onUserModal" class="mt-1"/>
        <!-- Кнопки пагинации -->
        <div class="grid grid-cols-2 w-full">
            <div class="flex justify-evenly">
                <button v-if="backPage" @click.prevent="viewBackPage()" class="mr-2 border bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-gray-700 border-gray-400 dark:hover:text-color-300/70 dark:text-gray-400/70 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700  pl-2 pr-2 shadow rounded">Назад</button>
            </div>
            <div class="flex justify-evenly">
                <button v-if="nextPage" @click.prevent="viewNextPage()" class=" border bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-gray-700 border-gray-400 dark:hover:text-color-300/70 dark:text-gray-400/70 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700  pl-2 pr-2 shadow rounded">Далее</button>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions, mapState } from 'pinia'
import { useUsersStore } from './../../stores/UsersStore'
import { useUsersQueryBuilderStore } from './../../stores/UsersQueryBuilderStore'
import { useLoaderStore } from '../../stores/LoaderStore';
import { catchError, tap, map, retry, delay, takeUntil} from 'rxjs/operators';
import { of, EMPTY, Subject } from 'rxjs';
import { useToastStore } from '../../stores/ToastStore'
import { MessagesUsers } from '../../enums/users_messages'
import { useUsersFilterStore } from '../../stores/UsersFilterStore';

import UsersFilter from '../../components/filters/users_filters/UsersFilter.vue'
import UsersTable from '../../components/tables/users_table/UsersTable.vue';
import UserModal from '../../components/modals/user/user_modal/UserModal.vue';

export default {
    name: 'Users',
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        }
    },
    data() {
        return {
            users: null,
            user: null,
            total: 0,
            nextPage: null,
            backPage: null
        }
    },
    computed: {
        ...mapState(useUsersFilterStore, {
            userName: 'name',
            userEmail: 'email',
            createdUser: 'createdDate',
            updatedUser: 'updatedDate',
            locationUser: 'locationId',
        })
    },
    components: {
        UsersFilter,
        UsersTable,
        UserModal
    },
    methods: {
        onCloseModal() {
            this.user = null
            this.getAllUsers()
        },
        onUserModal(data) {
            this.user = data
        },
        ...mapActions(useUsersStore, ['getUsers']),
        ...mapActions(useUsersQueryBuilderStore, ['queryBuilder', 'setPageUsersForPageUsers']),
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useToastStore, ['showToast']),
        viewNextPage() {
            this.setPageUsersForPageUsers(this.nextPage)
            this.getAllUsers()
        },
        viewBackPage() {
            this.setPageUsersForPageUsers(this.backPage)
            this.getAllUsers()
        },
        getAllUsers() {
            this.openLoaderFullPage()
            this.getUsers(this.queryBuilder('usersForPageUsers')).pipe(
                retry(3),
                delay(100),
                tap(()=> {this.closeLoaderFullPage()}),
                map(response => {
                    this.users = null
                    this.nextPage = null
                    this.backPage = null
                    response.data.users.data.length ? this.users = response.data.users.data : this.showToast(MessagesUsers.success_info_users_null, 'info')
                    response.data.users.next_cursor ? this.nextPage = response.data.users.next_cursor : null
                    response.data.users.prev_cursor ? this.backPage = response.data.users.prev_cursor : null
                    this.total = response.data.users.total
                }),
                catchError(err => {
                    this.closeLoaderFullPage()
                    this.users = ''
                    console.log(err.messages)
                    399 < err.response.status && err.response.status < 500 ? this.showToast(MessagesUsers.warning_users + ': ' + err.message, 'warning') : null
                    499 < err.response.status && err.response.status < 600 ? this.showToast(MessagesUsers.error_users + ': ' + err.message, 'error') : null
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$)
            ).subscribe()
        }

    },
    mounted() {
        this.openLoaderFullPage(),
        this.getAllUsers()
    },
    watch: {
        userName(newName, oldName) {
            this.getAllUsers()
        },
        userEmail() {
            this.getAllUsers()
        },
        createdUser() {
            this.getAllUsers()
        },
        updatedUser() {
            this.getAllUsers()
        },
        locationUser() {
            this.getAllUsers()
        },
    },

}
</script>
<style lang="">
    
</style>