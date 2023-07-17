<script>
import { useEventsStore } from '../../stores/eventsStore';  
import { defineComponent } from 'vue';
import { YandexMap, YandexMarker} from 'vue-yandex-maps'


export default defineComponent({
    setup: () => {
        const event_store = useEventsStore();
        const settings = {
            apiKey: '226cca4a-d7de-46b5-9bc8-889f70ebfe64', // Индивидуальный ключ API
            lang: 'ru_RU', // Используемый язык
            coordorder: 'latlong', // Порядок задания географических координат
            debug: true, // Режим отладки
            version: '2.1' // Версия Я.Карт
        };
        const onMarker = (e) => {
            console.log(e.originalEvent.target.geometry._coordinates);
            useEventsStore().updateCoord(e.originalEvent.target.geometry._coordinates[0], e.originalEvent.target.geometry._coordinates[1])
            // + event_store.event.longitude)
        };
        const close = async () => {
            await event_store.getEventId(event_store.event.id); 
            await event_store.closeUpdate();
        };
        return { 
            settings,
            event_store,
            onMarker,
            close,
        }
    },
    components: {YandexMap, YandexMarker},
    methods: {
        updateTypes(new_types) {
            if(new_types) {
                this.event_store.updateEventTypes(new_types);
                this.new_types = ''
            }
        },
    },
    
})

useEventsStore().getTypes();

</script>


<template>
    <section class="text-gray-400 bg-gray-100 dark:bg-gray-800 body-font relative rounded-lg">
        <div class="flex justify-between items-center pb-3 py-5 px-5">
            <label for="name" class="leading-7 px-5 text-sm text-gray-600 dark:text-gray-200">Имя</label>
                    <input type="text" v-model="event_store.event.name" placeholder="Имя"
                        class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                        <label for="email" class="leading-7 px-5 text-sm text-gray-600 dark:text-gray-200">Спонсор</label>
                    <input type="text" v-model="event_store.event.sponsor" placeholder="Спонсор"
                        class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
        </div>
        <div class="container px-5 py-5 mx-auto flex sm:flex-nowrap flex-wrap">
            <div class="lg:w-2/3 md:w-1/2 bg-gray-500 dark:bg-gray-800 rounded-lg overflow-hidden sm:mr-10 p-10 flex items-end justify-start relative">
                <YandexMap
                    v-model="event_store.event.name"
                    class="absolute inset-0"
                    :settings="settings"
                    :zoom="16"
                    :behaviors="['drag', 'scrollZoom']"
                    :controls="['fullscreenControl', 'rulerControl', 'typeSelector', 'searchControl']"
                    :coordinates="[ event_store.event.latitude, event_store.event.longitude]">
                    <YandexMarker 
                    
                    :coordinates="[event_store.event.latitude, event_store.event.longitude]" 
                    :marker-id="event_store.event.id"               
                    :options="{
                        preset: 'islands#violetDotIcon',
                        draggable:'true',
                    }"
                    :events="['dragend']"
                    @dragend="onMarker">
                    </YandexMarker>
                </YandexMap>
                <div class="bg-gray-200/50 hover:bg-gray-200/80 dark:bg-gray-900/30 hover:dark:bg-gray-900/50 relative flex flex-wrap py-6 rounded shadow-md">
                <div class="lg:w-1/2 px-6">
                    <label for="city" class="leading-7 text-sm text-gray-700 dark:text-gray-200">Город</label>
                            <input type="text" v-model="event_store.event.city" placeholder="Город"
                                class="w-full bg-gray-400/30 dark:bg-gray-900/30 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                    <label for="address" class="leading-7 text-sm text-gray-700 dark:text-gray-200">Адресс</label>
                        <input type="text" v-model="event_store.event.address" placeholder="Адрес"
                            class="w-full bg-gray-400/30 dark:bg-gray-900/30 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                </div>
                <div class="lg:w-1/2 px-6 mt-4 lg:mt-0">
                <h2 class="title-font font-semibold text-black dark:text-white tracking-widest text-xs">Время</h2>
                <label for="email" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Начало</label>
                        <input type="text" v-model="event_store.event.date_start" placeholder="Адрес"
                            class="w-full bg-gray-400/30 dark:bg-gray-900/30 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                            <label for="email" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Конец</label>
                        <input type="text" v-model="event_store.event.date_end" placeholder="Адрес"
                            class="w-full bg-gray-400/30 dark:bg-gray-900/30 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                <label for="email" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Цена</label>
                    <input type="text" v-model="event_store.event.price" placeholder="Имя"
                        class="w-full bg-gray-400/30 dark:bg-gray-900/30 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                </div>
            </div>
            </div>
            <div class="lg:w-1/3 md:w-1/2 flex flex-col md:ml-auto w-full  mt-8 md:mt-0">
                <label for="name" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Имя</label>
                    <p type="text" class="w-full bg-gray-400 text-gray-600 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">{{ event_store.event.author.name }}</p>
                <label for="Email" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Email</label>
                    <p type="text" class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">{{ event_store.event.author.email }}</p>
                <div class="relative mb-4">
                    <label for="Post_id" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Post_id</label>
                    <input type="text" v-model="event_store.event.vk_post_id" placeholder="Post id"
                        class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                    
                </div>
                <div class="relative mb-4">
                    <label for="Source_id" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Source_id</label>
                    <input type="text" v-model="event_store.event.vk_group_id" placeholder="Source id"
                        class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                </div>
                <div class="relative mb-4">
                    <label for="types" class="leading-7 text-sm text-gray-600 dark:text-gray-400" v-if="event_store.event.types.length !== 0" >Текущий тип мероприятия: {{ event_store.event.types[0].name }}</label>
                    <label for="types" class="leading-7 text-sm text-red-400" v-if="event_store.event.types.length === 0">Текущий тип не определён</label>
                    <div class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">
                        Изменить на:
                        <select v-model="event_store.new_types" class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">
                            <option v-for="types in event_store.types" :value="types.id">
                                {{ types.name }}
                            </option>
                            <option> </option>
                        </select>
                    </div>
                    <label for="email" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Статус</label>
                    <div v-if="event_store.event.statuses.length !== 0" class="w-full bg-gray-400 dark:bg-gray-800 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-600 dark:text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <p class="leading-relaxed" v-for="status of event_store.event.statuses">
                            <div v-if="status.pivot.last === true">
                                    {{ status.name }}     
                            </div>
                        </p>
                    </div>
                    <div v-if="event_store.event.statuses.length === 0" class="w-full bg-red-900 rounded border border-red-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <p class="leading-relaxed">
                            Статус не определён
                        </p>
                    </div>
                </div>
                <div class="relative mb-4">
                    <label for="message" class="leading-7 text-sm text-gray-400">Описание</label>
                        <textarea v-model="event_store.event.description"  class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-600 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                </div>
                <div class="flex justify-center">
                    <div class="px-6">
                        <button v-on:click="close()" class="text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded text-lg">Отмена</button>
                    </div>
                    <div class="px-6">
                        <button v-on:click="event_store.updateEvent();" class="text-white bg-green-500 border-0 py-2 px-6 focus:outline-none hover:bg-green-600 rounded text-lg">Применить</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>