<script setup>
import {useUsersStore} from '../../stores/usersStore'
import { useToastStore } from '../../stores/toastStore'
import Loader from '../Loader.vue'
import SearchUsers from './SearchUsers.vue'
import Modal from './ModalCreateUsers.vue'
import ModalDel from './ModalDelUsers.vue'


const store = useUsersStore();
const store_toast = useToastStore();

useUsersStore().getUsers();



const pageN = "Вперёд &raquo;";
const pageP = "&laquo; Назад";

</script>

<template>
<section class="container px-4 mx-auto"> 
    <SearchUsers class="my-1"/>
    <Loader v-if="store.loader === true"/>
    <Modal v-if="store.show_modal_users === true"/>
    <div v-if="store.loader === false" class="flex flex-col">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                   <!-- Начало полей таблицы  -->
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center gap-x-3">
                                        <input type="checkbox" class="text-blue-500 border-gray-300 rounded dark:bg-gray-900 dark:ring-offset-gray-900 dark:border-gray-700">
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
                                    Name and email
                                </th>

                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Обновлено
                                </th>
                                <th scope="col" class="relative py-3.5 px-4">
                                        <div class="flex justify-center bg text-gray-200">
                                            <button class="p-2 w-1/8 rounded-md bg-green-800 text-green-100" @click="store.showModal" >Создать пользователя</button>
                                            <!-- Modal -->

        
                                        
                        
                                            <!-- ModalEnd -->


                                        </div>
                                    
                                </th>
                            </tr>
                        </thead>
                         <!-- Конец полей таблицы  -->
                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800" v-for="user in store.users.users.data" :key="user.id">
                            <tr>
                                <td class="px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-x-3">                                        <span>{{ user.id }}</span>
                                    </div>
                                </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{user.created_at}}</td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full gap-x-2 text-emerald-500 bg-emerald-100/60 dark:bg-gray-800" v-for="role in user.roles" :key="roles">
                                        <h2 v-if="role.name === 'Admin'" class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs" >{{ role.name }}</h2>
                                        <h2 v-else-if="role.name === 'Moderator'" class="bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs" >{{ role.name }}</h2>
                                    </div>
                                    <div v-if="user.roles.length === 0" class="inline-flex items-center px-3 py-1 rounded-full gap-x-2 text-emerald-500 bg-emerald-100/60 dark:bg-gray-800">
                                        <h2 class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Роль не определина</h2>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                    <div class="flex items-center gap-x-2">
                                        <img class="object-cover w-8 h-8 rounded-full" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=880&q=80" alt="">
                                        <div>
                                            <h2 class="text-sm font-medium text-gray-800 dark:text-white ">{{ user.name }}</h2>
                                            <p class="text-xs font-normal text-gray-600 dark:text-gray-400">{{ user.email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{ user.updated_at }}</td>
                                <td class="px-4 py-4 text-sm whitespace-nowrap">
                                    <div class="flex items-center gap-x-6">
                                        <button class="text-gray-500 transition-colors duration-200 dark:hover:text-indigo-500 dark:text-gray-300 hover:text-indigo-500 focus:outline-none">
                                            Подробнее
                                        </button>

                                        <button class="text-blue-500 transition-colors duration-200 hover:text-indigo-500 focus:outline-none">
                                            Редактировать
                                        </button>
                                        <button @click="store.showModalDel(user.id, user.name)" class="text-red-500 transition-colors duration-200 hover:text-indigo-500 focus:outline-none">Удалить</button>
                                        <ModalDel v-if="store.del_modal_users === true">
                                            <div class="border border-gray-800 p-6 grid grid-cols-1 gap-6 dark:bg-gray-700 shadow-lg rounded-lg text-gray-200">
                                                <h2>ID Пользователя: {{ user.id }}</h2>
                                                <h2>Имя поьзователя: {{ user.name }}</h2>
                                                <h2>Почта пользователя: {{ user.email }}</h2>
				                            </div>
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
    
    <div class="flex items-center justify-between mt-6" >
        <div v-for="link in store.users.users.links" :key="link.id">
            <div v-if=" link.label  === pageP">
                <a @click="store.getPage(link.url)" class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
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
                    <a @click="store.getPage(link.url)" class="px-2 py-1 text-sm text-blue-500 rounded-md dark:bg-gray-800 bg-blue-100/60">{{ link.label }}</a>
                </div>

                <div v-else-if="link.active == false && link.label !== pageP && link.label !== pageN">
                    <a @click="store.getPage(link.url)" class="px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">{{ link.label }}</a>
                </div>
            </div>

            <div v-if="link.label === pageN">
                <a @click="store.getPage(link.url)" class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
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

