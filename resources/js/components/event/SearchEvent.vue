<script>
import { defineComponent } from 'vue';
import { useEventsStore } from '../../stores/eventsStore';
import VueTailwindDatepicker from 'vue-tailwind-datepicker'

export default defineComponent({
    setup: () => {
        const event_store = useEventsStore();
        return {
            event_store,
        }
    },
    components: {VueTailwindDatepicker}
})


</script>

<template>
<div class="border border-gray-300 dark:border-gray-700 p-6 grid grid-cols-1 gap-6 dark:bg-gray-800 shadow-lg rounded-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="grid grid-cols-2 gap-2 rounded">
            <input type="text" v-model="event_store.event_search.name" placeholder="Название мероприятия"
                class="rounded px-5 bg-gray-300 dark:bg-gray-900 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            <input type="text" v-model="event_store.event_search.sponsor" placeholder="Спонсор мероприятия"
                class="rounded px-5 bg-gray-300 dark:bg-gray-900 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none"/>
            <div class=" rounded border border-gray-600 dark:border-gray-500 bg-gray-300 dark:bg-gray-900 px-5 text-gray-200 items-center"> 
                <h1 class="px-2 text-gray-600 text-sm dark:text-gray-200">Статус</h1>
                    <select v-model="event_store.event_search.status" class="rounded bg-gray-300 dark:bg-gray-900 w-10/12 text-gray-600 dark:text-gray-200 focus:outline-none">
                        <option v-for="status in event_store.status" :value="status.name">
                            {{ status.name }}
                        </option>
                        <option>Все</option>
                    </select>
                <p>
                    <div class="inline-flex text-sm items-center">
                        <label class="px-2 text-gray-600 dark:text-gray-300 w-8/12">
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
                            v-model="event_store.event_search.last"
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
            <div class="flex rounded border border-gray-600 dark:border-gray-500 bg-gray-300 dark:bg-gray-900 px-5 text-gray-200 items-center"> 
                <h1 class="px-2 text-gray-600 dark:text-gray-200 text-sm">Тип события
                <select v-model="event_store.event_search.types" class="rounded bg-gray-300 dark:bg-gray-900 w-10/12 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none">
                    <option v-for="types in event_store.types" :value="types.id">
                        {{ types.name }}
                    </option>
                    <option>Все</option>
                </select>
            </h1>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 rounded">
            <input type="text" v-model="event_store.event_search.city" placeholder="Город Мероприятия"
                class="rounded px-5 py-2 bg-gray-300 dark:bg-gray-900 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/>
            <input type="text" v-model="event_store.event_search.address" placeholder="Адрес мероприятия"
                class="rounded px-5 py-1 bg-gray-300 dark:bg-gray-900 text-gray-600 dark:text-gray-200 max-w-full focus:outline-none "/> 
            <input type="text" v-model="event_store.event_search.author" placeholder="Имя или почта автора"
                class="rounded my-6 px-5 bg-gray-300 dark:bg-gray-900 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            <VueTailwindDatepicker class="my-3 rounded" v-model="event_store.event_search.date" placeholder="Временные рамки" />
        </div>
        </div>   
        <div class="flex justify-center text-gray-400 dark:text-gray-200">
            <button v-on:click="event_store.getEventSearch()" class="p-2 w-1/4 rounded-md bg-blue-600 dark:bg-blue-800 hover:bg-blue-500 dark:hover:bg-blue-700 text-blue-100">
                Найти
            </button>
        </div>
        <div class=" text-gray-400 dark:text-gray-200">
            <button v-on:click="event_store.clearSearch()" class="px-2 py-1 rounded-md dark:bg-green-800 text-green-100 dark:hover:bg-green-700 bg-green-500 hover:bg-green-400 dark:hover:bg-green-700">
                Сбросить фильтры
            </button>
        </div>
</div>
</template>