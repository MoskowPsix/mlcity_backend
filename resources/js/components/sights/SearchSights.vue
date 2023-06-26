<script>

import { defineComponent } from 'vue';
import { useSightsStore } from '../../stores/SightsStore';

export default defineComponent({
    setup: () => {
        const sights_name = '';
        const sights_sponsor = '';
        const sights_author = '';
        const sights_city = '';
        const sights_address = '';
        const sights_status = '';
        const sights_types ='';
        const sights_text = '';
        const sights_store = useSightsStore();
        return {
            sights_store,
            sights_name,
            sights_sponsor,
            sights_author,
            sights_city,
            sights_address,
            sights_status,
            sights_types,
            sights_text,
        }
    },
    methods: {
        clear() {
            this.sights_name = '';
            this.sights_sponsor = '';
            this.sights_author = '';
            this.sights_city = '';
            this.sights_address = '';
            this.sights_status = '';
            this.sights_types = '';
            this.sights_text = '';
        }
    }
})


</script>

<template>
<div class="border border-gray-300 dark:border-gray-800 p-6 grid grid-cols-1 gap-6 dark:bg-gray-700 shadow-lg rounded-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="grid grid-cols-2 gap-2 rounded">
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 items-center p-2 ">
                <input type="text" v-model="sights_name" placeholder="Название мероприятия"
                       class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            </div>
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 items-center p-2 ">
                <input type="text" v-model="sights_sponsor" placeholder="Спонсор мероприятия"
                       class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>
            </div>
            <div class="flex rounded -center bg-gray-300 dark:bg-gray-800 p-1 px-2 text-gray-200 justify-center items-center"> 
                <h1 class="px-2 text-gray-400 dark:text-gray-200">Статус: </h1>
                <select v-model="sights_status" class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none">
                    <option v-for="status in sights_store.status" :value="status.name">
                        {{ status.name }}
                    </option>
                    <option>Все</option>
                </select>
            </div>
            <div class="flex rounded -center bg-gray-300 dark:bg-gray-800 p-1 px-2 text-gray-200 justify-center items-center"> 
                <h1 class="px-2 text-gray-400 dark:text-gray-200">Тип: </h1>
                <select v-model="sights_types" class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none">
                    <option v-for="types in sights_store.types" :value="types.name">
                        {{ types.name }}
                    </option>
                    <option>Все</option>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 rounded">
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 items-center p-2 ">
                <input type="text" v-model="sights_author" placeholder="Имя или почта автора"
                       class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            </div>

            <input type="text" v-model="sights_text" placeholder="Имя, почта или описание..."
                class="px-5 rounded bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>

            <div class="flex rounded bg-gray-300 dark:bg-gray-800 items-center p-2 ">
                <input type="text" v-model="sights_city" placeholder="Город Мероприятия"
                       class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>
            </div>
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 items-center p-2 ">
                <input type="text" v-model="sights_address" placeholder="Адрес мероприятия"
                       class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>
            </div>           
        </div>
        </div>   
        <div class="flex justify-center ext-gray-400 dark:text-gray-200"><button v-on:click="sights_store.getSightsSearch(sights_name, sights_sponsor, sights_author, sights_city, sights_address, sights_status, sights_types, sights_text), clear()" class="p-2 w-1/4 rounded-md bg-blue-800 text-blue-100">Найти</button></div>
</div>
</template>