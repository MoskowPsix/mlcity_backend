<script>

import { defineComponent } from 'vue';
import { useSightsStore } from '../../stores/SightsStore';

export default defineComponent({
    setup: () => {
        const sights_store = useSightsStore();
        return {
            sights_store,
        }
    },
})


</script>

<template>
<div class="border border-gray-300 dark:border-gray-800 p-6 grid grid-cols-1 gap-6 dark:bg-gray-700 shadow-lg rounded-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="grid grid-cols-2 gap-2 rounded">
            <input type="text" v-model="sights_store.sight_search.name" placeholder="Название мероприятия"
                    class="rounded px-5 py-1 bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            <input type="text" v-model="sights_store.sight_search.sponsor" placeholder="Спонсор мероприятия"
                    class="rounded px-5 bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>
            <div class="rounded bg-gray-300 dark:bg-gray-800 p-1 px-5 text-gray-200  items-center"> 
                <h1 class="px-2 text-gray-400 dark:text-gray-200">Статус: 
                <select v-model="sights_store.sight_search.status" class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none">
                    <option v-for="status in sights_store.status" :value="status.name">
                        {{ status.name }}
                    </option>
                    <option>Все</option>
                </select>
                </h1>
                <p>
                    <div class="inline-flex items-center">
                        <label class="px-2 text-gray-400 dark:text-gray-300">
                            Искать по всем статусам?
                        </label>
                        <label
                            class="relative flex cursor-pointer items-center rounded-full py-1 px-2"
                            data-ripple-dark="true"
                        >
                            <input
                            id="login"
                            type="checkbox"
                            v-bind:true-value="false"
                            v-bind:false-value="true"
                            v-model="sights_store.sight_search.last"
                            class="before:content[''] peer relative h-4 w-4 cursor-pointer appearance-none rounded border border-blue-gray-200 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-10 before:w-10 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-blue-500 checked:bg-blue-500 checked:before:bg-blue-500 hover:before:opacity-10"
                            />
                            <div class="pointer-events-none absolute top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 text-white opacity-0 transition-opacity peer-checked:opacity-100">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-3.5 w-3.5"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                stroke="currentColor"
                                stroke-width="1"
                            >
                                <path
                                fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"
                                ></path>
                            </svg>
                            </div>
                        </label>
                        </div>
                </p>
            </div>
            <div class="flex rounded  bg-gray-300 dark:bg-gray-800 p-1 px-5 text-gray-200 items-center"> 
                <h1 class="px-2 text-gray-400 dark:text-gray-200">Тип: </h1>
                <select v-model="sights_store.sight_search.types" class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none">
                    <option v-for="types in sights_store.types" :value="types.id">
                        {{ types.name }}
                    </option>
                    <option>Все</option>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 rounded">
            <input type="text" v-model="sights_store.sight_search.author" placeholder="Имя или почта автора"
                    class="rounded px-5 py-2 bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            <input type="text" v-model="sights_store.sight_search.text" placeholder="Имя, почта или описание..."
                class="px-5 py-2 rounded bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            <input type="text" v-model="sights_store.sight_search.city" placeholder="Город Мероприятия"
                    class="rounded px-5 py-2 bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>
            <input type="text" v-model="sights_store.sight_search.address" placeholder="Адрес мероприятия"
                class="rounded px-5 py-2 bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>
        </div>
        </div>   
        <div class="flex justify-center ext-gray-400 dark:text-gray-200"><button v-on:click="sights_store.getSightsSearch()" class="p-2 w-1/4 rounded-md bg-blue-800 text-blue-100">Найти</button></div>
        <div class=" text-gray-400 dark:text-gray-200">
            <button v-on:click="sights_store.clearSightsSearch()" class="px-1 rounded-md dark:bg-green-800 text-green-100 dark:hover:bg-green-700 bg-green-600 hover:bg-green-500">
                Сбросить фильтры
            </button>
        </div>
</div>
</template>