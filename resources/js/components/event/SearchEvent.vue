<script>
import { defineComponent } from 'vue';
import { useEventsStore } from '../../stores/eventsStore';
import VueTailwindDatepicker from 'vue-tailwind-datepicker'

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
    },
    components: {VueTailwindDatepicker}
})


</script>

<template>
<div class="border border-gray-300 dark:border-gray-800 p-6 grid grid-cols-1 gap-6 dark:bg-gray-700 shadow-lg rounded-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="grid grid-cols-2 gap-2 rounded">
            <input type="text" v-model="event_name" placeholder="Название мероприятия"
                class="rounded px-5 py-2 bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            <input type="text" v-model="event_sponsor" placeholder="Спонсор мероприятия"
                class="rounded px-5 py-2 bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none"/>
            <div class="flex rounded  bg-gray-300 dark:bg-gray-800 p-1 px-5 text-gray-200 items-center"> 
                <h1 class="px-2 text-gray-400 dark:text-gray-200">Статус: </h1>
                <select v-model="event_status" class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none">
                    <option v-for="status in event_store.status" :value="status.name">
                        {{ status.name }}
                    </option>
                    <option>Все</option>
                </select>
            </div>
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 p-1 px-5 text-gray-200 items-center"> 
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
            <input type="text" v-model="event_author" placeholder="Имя или почта автора"
                class="rounded px-5 py-3 bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            <VueTailwindDatepicker class="py-1" v-model="event_date" placeholder="Временные рамки" />
            <input type="text" v-model="event_city" placeholder="Город Мероприятия"
                class="rounded px-5 py-3 bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>
            <input type="text" v-model="event_address" placeholder="Адрес мероприятия"
                class="rounded px-5 py-3 bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>         
        </div>
        </div>   
        <div class="flex justify-center ext-gray-400 dark:text-gray-200">
            <button v-on:click="event_store.getEventSearch(event_name, event_sponsor, event_date, event_author, event_city, event_address, event_status, event_types), clear()" class="p-2 w-1/4 rounded-md bg-blue-800 text-blue-100">
                Найти
            </button>
        </div>
</div>
</template>