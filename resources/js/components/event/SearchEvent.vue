<script>
import { defineComponent } from 'vue';
import { useEventsStore } from '../../stores/eventsStore';

export default defineComponent({
    setup: () => {
        const event_name = '';
        const event_sponsor = '';
        const event_date = '';
        const event_end = '';
        const event_author = '';
        const event_types = 'Все';
        const event_city = '';
        const event_address = '';
        const event_status = 'На модерации';
        const event_store = useEventsStore();
        return {
            event_store,
            event_name,
            event_sponsor,
            event_date,
            event_end,
            event_author,
            event_types,
            event_city,
            event_address,
            event_status,
        }
    },
    methods: {
        clear() {
            this.event_name = '';
            this.event_sponsor = '';
            this.event_date = '';
            this.event_end = '';
            this.event_author = '';
            this.event_types = '';
            this.event_city = '';
            this.event_address = '';
            this.event_status = '';
        }
    }
})


</script>

<template>
<div class="border border-gray-300 dark:border-gray-800 p-6 grid grid-cols-1 gap-6 dark:bg-gray-700 shadow-lg rounded-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="grid grid-cols-2 gap-2 rounded">
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 items-center p-2 ">
                <input type="text" v-model="event_name" placeholder="Название мероприятия"
                       class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            </div>
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 items-center p-2 ">
                <input type="text" v-model="event_sponsor" placeholder="Спонсор мероприятия"
                       class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>
            </div>
            <div class="flex rounded -center bg-gray-300 dark:bg-gray-800 p-1 px-2 text-gray-200 justify-center items-center"> 
                <h1 class="px-2 text-gray-400 dark:text-gray-200">Статус: </h1>
                <select v-model="event_status" class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none">
                    <option v-for="status in event_store.status" :value="status.name">
                        {{ status.name }}
                    </option>
                    <option>Все</option>
                </select>
            </div>
            <div class="flex rounded -center bg-gray-300 dark:bg-gray-800 p-1 px-2 text-gray-200 justify-center items-center"> 
                <h1 class="px-2 text-gray-400 dark:text-gray-200">Тип: </h1>
                <select v-model="event_types" class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none">
                    <option v-for="types in event_store.types" :value="types.id">
                        {{ types.name }}
                    </option>
                    <option>Все</option>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 rounded">
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 items-center p-2 ">
                <input type="text" v-model="event_author" placeholder="Имя или почта автора"
                       class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            </div>
            <vue-tailwind-datepicker v-model="event_date" placeholder="Временные рамки" />
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 items-center p-2 ">
                <input type="text" v-model="event_city" placeholder="Город Мероприятия"
                       class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>
            </div>
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 items-center p-2 ">
                <input type="text" v-model="event_address" placeholder="Адрес мероприятия"
                       class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>
            </div>           
        </div>
        </div>   
        <div class="flex justify-center ext-gray-400 dark:text-gray-200"><button v-on:click="event_store.getEventSearch(event_name, event_sponsor, event_date, event_author, event_city, event_address, event_status, event_types), clear()" class="p-2 w-1/4 rounded-md bg-blue-800 text-blue-100">Найти</button></div>
</div>
</template>