<template lang="">
    <!-- delete modal -->
<!-- Main modal -->
<div id="crud-modal" tabindex="-1" aria-hidden="true" class="flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-950/70">
    <DeleteUserModal v-if="deleteModal" :user="user" @onClose="closeModalDelete()"/>
    <div class="relative p-4 w-full max-w-md max-h-full">
        <LoaderSpinLocal v-if="loaderUpdate"/>
        <!-- Modal content -->
        <div v-if="!loaderUpdate" class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Редактировать пользователя
                </h3>
                <button @click="onCloseModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Имя</label>
                        <input min="3" v-model="user.name" type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Имя">
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input v-model="user.email" min="3" type="email" name="email" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Email" required="">
                    </div>
                    <div  v-if="user.roles.length !== 0" class="col-span-2 ">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Роль</label>
                        <select v-model="user.roles[0].id" id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option :value="noRole">{{noRole}}</option>
                            <option :value="role.id" v-for="role in roles">{{role.name}}</option>
                            
                        </select>
                    </div>
                    <div  v-if="user.roles.length === 0" class="col-span-2 ">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Роль</label>
                        <select v-model="newRole" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option :value="noRole">{{noRole}}</option>
                            <option :value="role.id" v-for="role in roles">{{role.name}}</option>
                            
                        </select>
                    </div>
                </div>
                <div class="flex">
                    <button @click.prevent="updUser()" disable="" class="flex-1 mr-2 text-white inline-flex items-center bg-blue-600/90 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Применить изменения
                    </button>
                    <button @click="openModalDelete()" class="flex-1 ml-2 text-white inline-flex items-center bg-red-600/90 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                        Удалить пользователя
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 
</template>
<script>
import { catchError, tap, map, retry, delay, takeUntil} from 'rxjs/operators';
import { of, EMPTY, Subject } from 'rxjs';
import { mapActions, mapState } from 'pinia'
import { reactive, toRaw, unref } from 'vue';
import { useToastStore } from '../../../../stores/ToastStore'
import { useLoaderStore } from '../../../../stores/LoaderStore';
import { useUsersStore } from '../../../../stores/UsersStore'
import { MessagesUsers } from '../../..//../enums/users_messages'
import DeleteUserModal from '../delete_user_modal/DeleteUserModal.vue'
import LoaderSpinLocal from '../../../loaders/LoaderSpinLocal.vue'


export default {
    name: 'UserModal',
    props: ['user'],
    setup() {
        let oldUser = []
        const noRole = 'Без роли'
        const destroy =  new Subject()
        let oldRole = ''
        return {
            oldUser,
            noRole,
            destroy,
            oldRole
        }
    },
    data() {
        return {
            roles: null,
            newRole: 'Без роли',
            deleteModal: false,
            loaderUpdate: true,
        }
    },
    components: {
        DeleteUserModal,
        LoaderSpinLocal
    },
    computed: {
        ...mapState(useLoaderStore, 'loaderFullPageState')
    },
    methods: {
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useUsersStore, ['getRoles', 'updateUser', 'updateRoleUser', 'deleteRoleUser']),
        onCloseModal() {
            this.$emit('onClose', true)
        },
        openModalDelete() {
            this.deleteModal = true
        },
        closeModalDelete() {
            this.deleteModal = false
            this.onCloseModal()
        },
        getAllRole() {
            this.getRoles().pipe(
                retry(3),
                delay(100),
                map(response => {
                    this.roles = response.data.roles
                    this.loaderUpdate = false
                }),
                catchError(err => {
                    this.loaderUpdate = false
                    return of(EMPTY)
                }),
                takeUntil(this.destroy)
            ).subscribe()
        },
        updUser() {
            if (this.user !== this.oldUser) {
                this.loaderUpdate = true
                this.updateUser(this.user).pipe(
                    map(response => {
                        this.showToast(MessagesUsers.success_user_update, 'success')
                        this.updRole()
                        this.loaderUpdate = false
                        this.onCloseModal()
                    }),
                    catchError(err => {
                        399 < err.response.status && err.response.status < 500 ? this.showToast(MessagesUsers.warning_user_update + ': ' + err.message, 'warning') : null
                        499 < err.response.status && err.response.status < 600 ? this.showToast(MessagesUsers.error_user_update + ': ' + err.message, 'error') : null
                        this.loaderUpdate = false
                        return of(EMPTY)
                    }),
                    takeUntil(this.destroy)
                ).subscribe()
            } else {
                this.showToast(MessagesUsers.info_user_update + ', так как поля остались не тронутыми', 'info')
                this.updRole()
                this.onCloseModal()
            }
        },
        updRole() {
            this.loaderUpdate = true
            if (this.oldRole === this.noRole) {
                if (this.newRole !== this.oldRole) {
                    this.updateRoleUser(this.user.id, this.newRole).pipe(
                        map(response => {
                            this.loaderUpdate = false
                            this.showToast(MessagesUsers.success_role_user, 'success')
                        }),
                        catchError(err => {
                            this.loaderUpdate = false
                                399 < err.response.status && err.response.status < 500 ? this.showToast(MessagesUsers.warning_role_user + ': ' + err.message, 'warning') : null
                                499 < err.response.status && err.response.status < 600 ? this.showToast(MessagesUsers.error_role_user + ': ' + err.message, 'error') : null
                           return of(EMPTY) 
                        }),
                        takeUntil(this.destroy)
                    ).subscribe()
                } else {
                    this.showToast(MessagesUsers.info_role_user, 'info')
                }
            } else {
                if (this.oldRole !== this.user.roles[0].id) {
                    if (this.user.roles[0].id === this.noRole) {
                        this.deleteRoleUser(this.user.id, this.oldRole).pipe(
                            map(response => {
                                this.loaderUpdate = false
                                this.showToast(MessagesUsers.success_role_user_delete, 'success')
                            }),
                            catchError(err => {
                                this.loaderUpdate = false
                                399 < err.response.status && err.response.status < 500 ? this.showToast(MessagesUsers.warning_role_user_delete + ': ' + err.message, 'warning') : null
                                499 < err.response.status && err.response.status < 600 ? this.showToast(MessagesUsers.error_role_user_delete + ': ' + err.message, 'error') : null
                                return of(EMPTY) 
                            }),
                            takeUntil(this.destroy)
                        ).subscribe()
                    } else {
                        this.updateRoleUser(this.user.id, this.user.roles[0].id).pipe(
                            map(response => {
                                this.loaderUpdate = false
                                this.showToast(MessagesUsers.success_role_user, 'success')
                            }),
                            catchError(err => {
                                this.loaderUpdate = false
                                399 < err.response.status && err.response.status < 500 ? this.showToast(MessagesUsers.warning_role_user + ': ' + err.message, 'warning') : null
                                499 < err.response.status && err.response.status < 600 ? this.showToast(MessagesUsers.error_role_user + ': ' + err.message, 'error') : null
                                return of(EMPTY) 
                            }),
                            takeUntil(this.destroy)
                        ).subscribe()
                    }
                } else {
                    this.showToast(MessagesUsers.info_role_user, 'info')
                }
            }
        },
        checkUserRole() {
            if (this.user.roles.length) {
                this.oldRole = toRaw(this.user.roles[0].id)
            } else {
                this.oldRole = this.noRole
            }
        },
    },
    mounted() {
        this.getAllRole(),
        this.oldUser = toRaw(this.user),
        this.checkUserRole()
    },
}
</script>
<style lang="">
    
</style>