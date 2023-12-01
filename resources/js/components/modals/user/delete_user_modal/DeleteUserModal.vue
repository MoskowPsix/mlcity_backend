<template lang="">
    <div id="popup-modal" tabindex="-1" class="flex bg-gray-950/70 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <LoaderSpinLocal v-if="loadDelete"/>
        <div v-if="!loadDelete" class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- <button @click="onCloseModal()" type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button> -->
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Вы действительно хотите удалить этого пользователя?</h3>
                <button  @click="delUser()" type="button" class="text-white bg-red-600/90 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                    Да, конечно
                </button>
                <button @click="onCloseModal()" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Нет, закрыть</button>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import { mapActions } from 'pinia'
import { useUsersStore } from '../../../../stores/UsersStore'
import { catchError, tap, map, retry, delay, takeUntil} from 'rxjs/operators';
import { of, EMPTY, Subject } from 'rxjs';
import { MessagesUsers } from '../../../../enums/users_messages'
import { useToastStore } from '../../../../stores/ToastStore'
import LoaderSpinLocal from '../../../loaders/LoaderSpinLocal.vue'



export default {
    name: 'DeleteUserModal',
    props: ['user'],
    components: {
        LoaderSpinLocal
    },
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        }
    },
    data() {
        return {
            loadDelete: false
        }
    },
    methods: {
        ...mapActions(useUsersStore, ['deleteUser']),
        ...mapActions(useToastStore, ['showToast']),
        onCloseModal() {
            this.$emit('onClose', true)
        },
        delUser() {
            this.loadDelete = true
            this.deleteUser(this.user.id).pipe(
                map(() => {
                    this.loadDelete = false
                    this.showToast(MessagesUsers.success_user_delete, 'success')
                    this.onCloseModal()
                }),
                catchError(err => {
                    this.loadDelete = false
                    if (err.status)
                    399 < err.response.status && err.response.status < 500 ? this.showToast(MessagesUsers.warning_user_delete + ': ' + err.message, 'warning') : null
                    499 < err.response.status && err.response.status < 600 ? this.showToast(MessagesUsers.error_user_delete + ': ' + err.message, 'error') : null
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$),
            ).subscribe()
        }
    },
}
</script>
<style lang="">
    
</style>