<script>
import { useLogsApiStore } from '../../stores/logsApiStore';


export default {
    setup() {
        const log_api_store = useLogsApiStore();
        return {
            log_api_store,
        }
    },
    methods: {
        short: (str, maxlen) => str.length <= maxlen ? str : str.slice(0, maxlen) + '...',
        localeDate(date) {
            return (new Date(date)).toLocaleString()
        },
    },
    filters: {
        pretty: function(value) {
        return JSON.stringify(JSON.parse(value), null, 2);
        }
    },
}
</script>

<template>
<div class="main-modal fixed w-full h-full inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster overflow-y-scroll" id="journal-scroll" style="background: rgba(0,0,0,.5);">
    <div class="rounded-lg dark:bg-gray-800 h-5/6 w-10/12 shadow-lg rounded p-8 bg-white overflow-y-auto" id="journal-scroll">
        <div @click="log_api_store.closeModalLog" class="relative p-5 modal-close cursor-pointer z-50">
                <svg class="absolute right-0 fill-current text-gray-500 " xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                    viewBox="0 0 18 18">
                    <path

                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                    </path>
                </svg>
            </div>
        <h1 class="dark:text-gray-300">ID Log</h1>
        <p class="font-light text-gray-700 leading-relaxed dark:text-gray-400">{{ log_api_store.log.id }}</p>
        <h1 class="dark:text-gray-300">Date and Time</h1>
        <p class="font-light text-gray-700 leading-relaxed dark:text-gray-400">{{ localeDate(log_api_store.log.created_at) }}</p>
        <h1 class="dark:text-gray-300">User</h1>
        <div class="bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 p-1 px-5">
            <h1 class=" text-gray-600 dark:text-gray-300">ID</h1>
            <p class="px-2 text-xs font-normal text-gray-600 dark:text-gray-400">{{log_api_store.log.log_user.id}}</p> 
            <h1 class=" text-gray-600 dark:text-gray-300">Name</h1>
            <p class="px-2 text-xs font-normal text-gray-600 dark:text-gray-400">{{log_api_store.log.log_user.name}}</p>
            <h1 class=" text-gray-600 dark:text-gray-300">Email</h1>
            <p class="px-2 text-xs font-normal text-gray-600 dark:text-gray-400">{{log_api_store.log.log_user.email}}</p>
            <!-- <h1 class=" text-gray-600 dark:text-gray-300">Role</h1> -->
        </div>
        <h1 class="dark:text-gray-300">Statuse Code</h1>
        <p v-if="499 < log_api_store.log.status_code && log_api_store.log.status_code < 600" class="bg-red-200/20 text-red-600 py-1 px-3 rounded-lg ">{{ log_api_store.log.status_code }}</p>
        <p v-if="399 < log_api_store.log.status_code && log_api_store.log.status_code < 500" class="bg-yellow-200/20 text-yellow-600 py-1 px-3 rounded-lg ">{{ log_api_store.log.status_code }}</p>
        <p v-if="299 < log_api_store.log.status_code && log_api_store.log.status_code < 400" class="bg-orange-200/20 text-orange-600 py-1 px-3 rounded-lg ">{{ log_api_store.log.status_code }}</p>
        <p v-if="199 < log_api_store.log.status_code && log_api_store.log.status_code < 300" class="bg-green-200/20 text-green-600 py-1 px-3 rounded-lg  ">{{ log_api_store.log.status_code }}</p>
        <p v-if="99 < log_api_store.log.status_code && log_api_store.log.status_code < 200" class="bg-blue-200/20 text-blue-600 py-1 px-3 rounded-lg ">{{ log_api_store.log.status_code }}</p>
        <h1 class="dark:text-gray-300">Full Url</h1>
        <textarea id="journal-scroll" class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 min-h-[10px] max-h-[50px] text-base outline-none text-gray-600 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out" > {{ log_api_store.log.url }}</textarea>
        <h1 class="dark:text-gray-300">Method Request</h1>
        <p class="font-light text-gray-700 leading-relaxed dark:text-gray-400">{{ log_api_store.log.method }}</p>
        <h1 class="dark:text-gray-300">Ip Address</h1>
        <p class="font-light text-gray-700 leading-relaxed dark:text-gray-400">{{ log_api_store.log.ip }}</p>
        <h1 class="dark:text-gray-300">Request Header</h1>
        <textarea id="journal-scroll" class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 min-h-[300px] text-base outline-none text-gray-600 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out" > {{ JSON.stringify(JSON.parse(log_api_store.log.request_header), null, 2) }}</textarea>
        <h1 class="dark:text-gray-300">Request Arguments</h1>
        <textarea id="journal-scroll" class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 min-h-[200px] text-base outline-none text-gray-600 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out" > {{ JSON.stringify(JSON.parse(log_api_store.log.request_arg), null, 2) }}</textarea>
        <h1 class="dark:text-gray-300">Response</h1>
        <textarea id="journal-scroll" class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 min-h-[300px] text-base outline-none text-gray-600 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out" > {{ JSON.stringify(JSON.parse(JSON.parse(log_api_store.log.response)), null, 2) }}</textarea>
        <div class="flex items-center justify-end mt-4">
            <button @click="log_api_store.closeModalLog()" class="py-2 px-4 bg-white border border-gray-200 text-gray-600 rounded hover:bg-gray-100 active:bg-gray-200 disabled:opacity-50 mr-4">
            Закрыть
            </button>
        </div>
    </div>
</div>
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