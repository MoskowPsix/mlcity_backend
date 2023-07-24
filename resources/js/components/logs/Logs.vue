<script>
import Loader from '../Loader.vue';
import { useLogsApiStore } from '../../stores/logsApiStore';
import SearchLogs from './SearchLogs.vue';


export default {
    setup() {
        const log_api_store = useLogsApiStore();
        const pageN = "Вперёд &raquo;";
        const pageP = "&laquo; Назад";
        return {
            log_api_store,
            pageN,
            pageP,
        }
    },
    components: {
        Loader,
        SearchLogs
    },
    methods: {
        short: (str, maxlen) => str.length <= maxlen ? str : str.slice(0, maxlen) + '...',
    },
    mounted: async function () {
        this.log_api_store.reloadPage();
    },
}
useLogsApiStore().getLogs();
</script>


<template>
    <section class="container mx-auto">
        <SearchLogs class="m-1"/>
        <Loader v-if="log_api_store.loader === true"/>
        <div v-if="log_api_store.loader === false" class="flex flex-col">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8 " id="journal-scroll">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden border m-1 border-gray-200 dark:border-gray-700 md:rounded-lg">
                        <table class=" min-w-full md:w-auto divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-x-3">
                                                <span>ID</span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <div>Метод</div>
                                    </th>
    
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        URL
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Пользователь
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        IP
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Статус
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Дата и Время
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                                <tr class="capitalize transition-colors duration-200 rounded-md gap-x-2 hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-800" v-for="log of log_api_store.logs" :key="log.id">
                                    <td class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                        <div class="inline-flex items-center gap-x-3">
                                            <span>{{ log.id }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 w-1/12 text-sm text-gray-500 dark:text-gray-300 ">
                                        <h2 class="text-sm font-medium text-gray-800 dark:text-white ">{{ log.method }}</h2>
                                    </td>
                                    <td class="px-4 py-4 text-sm max-w-4/12 text-gray-500 dark:text-gray-300 " v-text="short(log.url, 100)"></td>
                                    <td v-if="log.log_user" class="px-4 py-4 text-sm text-gray-500 w-2/12 dark:text-gray-300">
                                        <div class="flex items-center gap-x-2">
                                            <img v-if="log.log_user.avatar" class="object-cover w-8 h-8 rounded-full" :src="log.log_user.avatar" >
                                            <svg v-if="!log.log_user.avatar" class="object-cover w-8 h-8 rounded-full" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM15 9C15 10.6569 13.6569 12 12 12C10.3431 12 9 10.6569 9 9C9 7.34315 10.3431 6 12 6C13.6569 6 15 7.34315 15 9ZM12 20.5C13.784 20.5 15.4397 19.9504 16.8069 19.0112C17.4108 18.5964 17.6688 17.8062 17.3178 17.1632C16.59 15.8303 15.0902 15 11.9999 15C8.90969 15 7.40997 15.8302 6.68214 17.1632C6.33105 17.8062 6.5891 18.5963 7.19296 19.0111C8.56018 19.9503 10.2159 20.5 12 20.5Z"/>
                                            </svg>
                                            <div>
                                                <h2 class="text-sm font-medium text-gray-800 dark:text-white ">{{log.log_user.id}} / {{log.log_user.name}}</h2>
                                                <p class="text-xs font-normal text-gray-600 dark:text-gray-400">{{log.log_user.email}}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td v-if="!log.log_user" class="px-4 py-4 text-sm text-gray-500 w-1/12 dark:text-gray-300">Пользователь не определён!</td>
                                    <td class="px-4 py-4 text-sm text-gray-500 w-1/12 dark:text-gray-300">{{ log.ip }}</td>
                                    <td  class="px-4 py-4 text-sm text-gray-500 w-1/12 dark:text-gray-300">
                                        {{ log.status_code }}
                                    </td>
                                    <td  class="px-4 py-4 text-sm text-gray-500 w-2/12 dark:text-gray-300">
                                        {{ log.created_at }}
                                    </td>
                                </tr>
                                <!-- Конец списка -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between mt-6">
            <div v-if="log_api_store.perv_page !== null">
                <a href="#/logs" @click="log_api_store.getLogUrl(log_api_store.perv_page)" class="flex items-center px-5 mx-2 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                    </svg>

                    <span>
                        Назад
                    </span>
                </a>
            </div>

            <div v-if="log_api_store.url !== null">
                <a href="#/logs" @click="log_api_store.getLogUrl(log_api_store.next_page)" class="flex items-center mx-2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
                    <span>
                        Вперёд
                    </span>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section> 
    </template>
    
    <style> 
    #journal-scroll::-webkit-scrollbar {
        width: 4px;
        cursor: pointer;
        /*background-color: rgba(229, 231, 235, var(--bg-opacity));*/
    
    }
    #journal-scroll::-webkit-scrollbar-track {
        background-color: rgba(229, 231, 235, var(--bg-opacity));
        cursor: pointer;
        
        /*background: red;*/
    }
    #journal-scroll::-webkit-scrollbar-thumb {
        cursor: pointer;
        background-color: #6c7786;
        border-radius: 25px;
        /*outline: 1px solid slategrey;*/
    }
    </style> 