<script>
import { defineComponent } from 'vue';
import { useLogsApiStore } from '../../stores/logsApiStore';
import VueTailwindDatepicker from 'vue-tailwind-datepicker'

export default defineComponent({
    setup: () => {
        const log_api_store = useLogsApiStore();
        return {
            log_api_store,
        }
    },
    components: {VueTailwindDatepicker}
})


</script>

<template>
<div class="border border-gray-300 dark:border-gray-700 p-6 grid grid-cols-1 gap-6 dark:bg-gray-800 shadow-lg rounded-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="grid grid-cols-2 gap-2 rounded">
            <input type="text"  placeholder="Метод" v-model="log_api_store.search_logs.method"
                class="rounded px-5 bg-gray-300 dark:bg-gray-900 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            <input type="text"  placeholder="url..." v-model="log_api_store.search_logs.url"
                class="rounded px-5 bg-gray-300 dark:bg-gray-900 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none"/>
            <div class="inline-flex text-sm p-1 py-3 my-7 items-center bg-gray-300  dark:bg-gray-900 rounded border border-gray-500">
                <label class="px-2 text-gray-600 dark:text-gray-300 w-8/12">
                    Динамический режим
                </label>
                <label
                    class="relative flex cursor-pointer items-center rounded-full py-1 px-2"
                    data-ripple-dark="true"
                >
                    <input
                    id="login"
                    type="checkbox"
                    v-bind:true-value="true"
                    v-bind:false-value="false"
                    v-model="log_api_store.search_logs.dynamic"
                    class="before:content[''] peer relative h-4 w-4 cursor-pointer appearance-none rounded border border-blue-gray-200 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-10 before:w-10 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-blue-500 checked:bg-blue-500 checked:before:bg-blue-500 hover:before:opacity-10"
                    />
                    <div class="pointer-events-none absolute top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 text-white opacity-0 transition-opacity peer-checked:opacity-100">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-3.5 w-3.5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        stroke="currentColor"
                        stroke-width="1">
                        <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                        ></path>
                    </svg>
                    </div>
                </label>
            </div>
                <div v-if="log_api_store.search_logs.dynamic === true" class="inline-flex text-sm px-2 my-7 items-center bg-gray-300  dark:bg-gray-900 rounded border border-gray-500 dark:text-gray-300 text-gray-600">
                    <h1 class="mx-1">Обновлять каждые: </h1>
                    <select @click="log_api_store.reloadPage()" v-model="log_api_store.search_logs.time" 
                    class="bg-gray-300 dark:bg-gray-900 rounded border border-gray-500 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">
                        <option @click="log_api_store.reloadPage()" :value="60000">
                            1 мин.
                        </option>
                        <option @click="log_api_store.reloadPage()" :value="30000">
                            30 мин.
                        </option>
                        <option @click="log_api_store.reloadPage()" :value="15000">
                            15 сек.
                        </option>
                        <option @click="log_api_store.reloadPage()" :value="5000">
                            5 сек.
                        </option>
                    </select>
                </div>
                <div v-if="log_api_store.search_logs.dynamic === false" class="inline-flex text-sm px-2 my-7 items-center bg-gray-300  dark:bg-gray-900 rounded border border-gray-500 dark:text-gray-300 text-gray-600">
                    <h1 class="mx-1 text-gray-400 dark:text-gray-700">Обновлять каждые: </h1></div>
        </div>
        <div class="grid grid-cols-2 gap-2 rounded">
            <input type="text" placeholder="ID пользователя..." v-model="log_api_store.search_logs.user_id"
                class="rounded mb-2 py-3 px-5 bg-gray-300 dark:bg-gray-900 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>
            <input type="text"  placeholder="IP..." v-model="log_api_store.search_logs.ip"
                class="rounded px-5 bg-gray-300 dark:bg-gray-900 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/> 
            <input type="text" placeholder="Код статуса..." v-model="log_api_store.search_logs.status_code"
                class="rounded mt-6 mb-7 py-4 px-5 bg-gray-300 dark:bg-gray-900 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            <VueTailwindDatepicker class="rounded mt-4"  placeholder="Временные рамки" v-model="log_api_store.search_logs.date"/>
        </div>
        </div>   
        <div class="flex justify-center text-gray-400 dark:text-gray-200">
            <button @click="log_api_store.getLogs" class="p-2 w-1/4 rounded-md bg-blue-600 dark:bg-blue-800 hover:bg-blue-500 dark:hover:bg-blue-700 text-blue-100">
                Найти
            </button>
        </div>
        <div class=" text-gray-400 dark:text-gray-200">
            <button @click="log_api_store.clearSearch" class="px-2 py-1 rounded-md dark:bg-green-800 text-green-100 dark:hover:bg-green-700 bg-green-500 hover:bg-green-400 dark:hover:bg-green-700">
                Сбросить фильтры
            </button>
        </div>
</div>
</template>