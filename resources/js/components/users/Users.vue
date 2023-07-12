<script>
import {useUsersStore} from '../../stores/usersStore'
import {useRoleStore} from '../../stores/roleStore'
import { useEventsStore } from '../../stores/eventsStore';
import { defineComponent } from 'vue';
import Loader from '../Loader.vue'
import SearchUsers from './SearchUsers.vue'
import Modal from './ModalCreateUsers.vue'
import ModalDel from './ModalDelUsers.vue'
import ModalUpdateUsers from './ModalUpdateUsers.vue'
import ModalDetailedUsers from './ModalDetailedUsers.vue';
import.meta.env.$APP_URL


export default defineComponent({
    setup() {
        const event_store = useEventsStore();
        const store = useUsersStore();
        const pageN = "Вперёд &raquo;";
        const pageP = "&laquo; Назад";
        return {store, pageN, pageP, event_store};
    },
    methods: {
        localeDate(date) {
            return (new Date(date)).toLocaleString()
        },
    },
    components: {Loader, SearchUsers, Modal, ModalDel, ModalUpdateUsers, ModalDetailedUsers},
})


useUsersStore().getUsers();
useRoleStore().getRole();


</script>

<template>
<section class="container px-4 mx-auto"> 
    <SearchUsers class="my-1"/>
    <Loader v-if="store.loader === true"/>
    <Modal v-if="store.show_modal_users === true"/>
    <ModalDetailedUsers v-if="store.detailed_modal_users === true"/>
    <ModalUpdateUsers v-if="store.user_upd === true"/>

    <div v-if="store.loader === false" class="flex flex-col">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                   <!-- Начало полей таблицы  -->
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center gap-x-3">
                                        <button class="flex items-center gap-x-2">
                                            <span>ID</span>

                                            <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                                <path d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                                <path d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z" fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                            </svg>
                                        </button>
                                    </div>
                                </th>

                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Зарегестрирован
                                </th>

                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Роль
                                </th>
                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Регион/Город
                                </th>
                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Name and email
                                </th>

                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Обновлено
                                </th>
                                <th scope="col" class="relative py-3.5 px-4">
                                        <div class="flex justify-center bg text-gray-200">
                                            <button class="p-2 w-1/8 rounded-md bg-green-800 text-green-100" @click="store.showModal" >Создать пользователя</button>
                                        </div>
                                </th>
                            </tr>
                        </thead>
                         <!-- Конец полей таблицы  -->
                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900" v-for="user in store.users.users.data" :key="user.id">
                            <tr class="capitalize transition-colors duration-200 rounded-md gap-x-2 hover:bg-gray-200 dark:bg-gray-900  dark:hover:bg-gray-800">
                                <td @click="store.showModalUpd(user)" class="px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-x-3">
                                        <span>{{ user.id }}</span>
                                    </div>
                                </td>
                                <td @click="store.showModalUpd(user)" class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{localeDate(user.created_at)}}</td>
                                <td @click="store.showModalUpd(user)" class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full gap-x-2 text-emerald-500 bg-gray-100/60 dark:bg-gray-700/20 dark:bg-gray-800" >
                                        <h2 v-if="user.roles.length === 0" class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Роль не определина</h2>
                                        <h2 v-else-if="user.roles[0].name" class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs" >{{ user.roles[0].name }}</h2>
                    
                                    </div>
                                </td>
                                <td @click="store.showModalUpd(user)" v-if="user.region && user.city" class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{ user.region }}, {{ user.city }}</td>
                                <td @click="store.showModalUpd(user)" v-if="!user.region && !user.city" class="px-4 py-4 text-sm text-red-500 dark:text-red-300 whitespace-nowrap">Не определено</td>
                                <td @click="store.showModalUpd(user)" class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                    <div class="flex items-center gap-x-2">
                                        <img v-if="user.avatar" class="object-cover w-8 h-8 rounded-full" :src="user.avatar" >
                                        <svg v-if="!user.avatar" class="object-cover w-8 h-8 rounded-full" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM15 9C15 10.6569 13.6569 12 12 12C10.3431 12 9 10.6569 9 9C9 7.34315 10.3431 6 12 6C13.6569 6 15 7.34315 15 9ZM12 20.5C13.784 20.5 15.4397 19.9504 16.8069 19.0112C17.4108 18.5964 17.6688 17.8062 17.3178 17.1632C16.59 15.8303 15.0902 15 11.9999 15C8.90969 15 7.40997 15.8302 6.68214 17.1632C6.33105 17.8062 6.5891 18.5963 7.19296 19.0111C8.56018 19.9503 10.2159 20.5 12 20.5Z"/>
                                        </svg>
                                        <div>
                                            <h2 class="text-sm font-medium text-gray-800 dark:text-white ">{{ user.name }}</h2>
                                            <p class="text-xs font-normal text-gray-600 dark:text-gray-400">{{ user.email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td @click="store.showModalUpd(user)" class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{ localeDate(user.updated_at) }}</td>
                                <td class="px-4 py-4 text-sm whitespace-nowrap">
                                    <div class="flex items-center gap-x-6">
                                        <button @click="store.showModalDetailed(user.id)" class="text-blue-500 transition-colors duration-200 dark:hover:text-blue-500 dark:text-blue-300 hover:text-blue-500 focus:outline-none">
                                            Лайки и Избронное
                                        </button>

                                        <!-- <button @click="store.showModalUpd(user)" class="text-blue-500 transition-colors duration-200 hover:text-indigo-500 focus:outline-none">
                                            Редактировать
                                        </button> -->
                                        <button @click="store.showModalDel(user.id, user.name, user.email)" class="text-red-500 transition-colors duration-200 hover:text-indigo-500 focus:outline-none">Удалить</button>
                                        <ModalDel v-if="store.del_modal_users === true">
                                        </ModalDel>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex items-center justify-between mt-6">
        <div v-for="link in store.links">
            <div v-if=" link.label  === pageP && link.url !== null">
                <a href="#" @click="store.getPage(link.url)" class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                    </svg>

                    <span>
                        Назад
                    </span>
                </a>
            </div>

            <div>
                <div v-if="link.active === true" class="items-center hidden md:flex gap-x-3">
                    <a href="#" @click="store.getPage(link.url)" class="px-2 py-1 text-sm text-blue-500 rounded-md dark:bg-gray-800 bg-blue-100/60">{{ link.label }}</a>
                </div>

                <div v-else-if="link.active == false && link.label !== pageP && link.label !== pageN">
                    <a href="#" @click="store.getPage(link.url)" class="px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">{{ link.label }}</a>
                </div>
            </div>

            <div v-if="link.label === pageN && link.url !== null">
                <a href="#" @click="store.getPage(link.url)" class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
                    <span>
                        Вперёд
                    </span>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section> 
</template>

