<script>
import { useEventsStore } from '../../stores/eventsStore';  
import { defineComponent } from 'vue';
import ModalUpdateEvent from './ModalUpdateEvent.vue';
import ModalStatuses from './ModalStatuses.vue';
import ModalHistoryStatus from './ModalHistoryStatus.vue';
import ModalLikedFavorites from './ModalLikedFavorites.vue';
import Loader from '../Loader.vue'

import showMap from './showMap.vue'


export default defineComponent ({
    setup: () => {
        const event_store = useEventsStore();
        return { 
            event_store
        }
    },
    components: {
        ModalUpdateEvent, 
        ModalStatuses, 
        ModalHistoryStatus,
        ModalLikedFavorites,
        showMap,
        Loader
    },  
})


</script>

<style>
</style>

<template>
<div class="main-modal fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster" style="background: rgba(0,0,0,.5);">
    <!-- Окно изменения события статусов-->
    <ModalUpdateEvent v-if="event_store.ModalUpdate === true"/>
    <!--Окно изменения статусов-->
    <ModalStatuses v-if="event_store.ModalStatuses === true"/>
    <!--Окно просмотра истории статусов-->
    <ModalHistoryStatus v-if="event_store.ModalHistoryStatus === true"> <!-- История статусов-->
        <table class="text-center w-full">
          <thead class="border-b">
            <tr>
              <th scope="col" class="text-sm font-medium text-gray-900 dark:text-gray-300 px-6 py-4">
                Статус
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 dark:text-gray-300 px-6 py-4">
                Дата
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 dark:text-gray-300 px-6 py-4">
                Описание
              </th>
            </tr>
          </thead>
          <tbody v-for="status of event_store.event.statuses">
            <tr v-if="status.pivot.last === true" class="border-b">
              <td class="text-sm text-green-600 dark:text-green-300 font-medium px-6 py-4">
                {{ status.name }}
              </td>
              <td class="text-sm text-green-600 dark:text-green-300 font-light px-6 py-4">
                {{ status.pivot.created_at }}
              </td>
              <td v-if="status.pivot.descriptions && status.pivot.descriptions !== 'null'" class="flex text-sm text-gray-300 dark:text-gray-300 font-light py-4"><textarea disabled wrap="soft | hard" class="rounded dark:bg-gray-800 bg-gray-200 text-sm text-gray-600 dark:text-green-300 ease-in-out text-base outline-none" id="journal-scroll">{{ status.pivot.descriptions }}</textarea>
              </td>
            </tr>
            <tr v-if="status.pivot.last === false" class="border-b">
              <td class="text-sm text-gray-900 dark:text-gray-300 font-medium px-6 py-4">
                {{ status.name }}
              </td>
              <td class="text-sm text-gray-900 dark:text-gray-300 font-light px-6 py-4">
                {{ status.pivot.created_at }}
              </td>
              <td v-if="status.pivot.descriptions && status.pivot.descriptions !== 'null'" class="flex text-sm text-gray-900 dark:text-gray-300 font-light py-4"><textarea disabled wrap="soft | hard" class="dark:bg-gray-800 bg-gray-200 text-sm text-gray-900 dark:text-gray-300 ease-in-out text-base outline-none" id="journal-scroll">{{ status.pivot.descriptions }}</textarea>
              </td>
            </tr>
            </tbody>
        </table>
    </ModalHistoryStatus>
    <!-- Окно списка коментариев (пока сломано) -->

    <!-- Окно просмотра юзеров лайнувцих и добавивших в избранное -->
    <ModalLikedFavorites  v-if="event_store.ModalLikedFavorites.status === true"/>

    <section class="max-h-full text-gray-400 bg-gray-100 dark:bg-gray-800 body-font relative rounded-lg overflow-scroll" id="journal-scroll" v-if="event_store.ModalUpdate === false">
        <Loader class="min-h-full" v-if="event_store.loader === true"/> 
        <div v-if="event_store.loader === false">
        <div class="flex justify-between items-center pb-3">
            <p class="text-2xl font-bold text-gray-700 dark:text-gray-300 px-5">Название: {{ event_store.event.name }}</p>
            <p class="text-2xl font-bold text-gray-700 dark:text-gray-300 px-5">Спонсор: {{ event_store.event.sponsor }}</p>
            <div @click="event_store.closeUpdateEvent()" class="relative p-5 modal-close cursor-pointer z-50">
                <svg class="fill-current text-gray-500" xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                    viewBox="0 0 18 18">
                    <path
                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                    </path>
                </svg>
            </div>
        </div>
        <div class="container px-5 py-5 mx-auto flex sm:flex-nowrap flex-wrap">
            <div v-if="event_store.event.files.length !== 0" class="flex 2xl:w-2/12 lg:2/12 xl:w-2/12 sm:w-10/12 md:w-10/12 max-h-screen px-2 flex-col overflow-y-scroll" id="journal-scroll">
                <div v-for="img in event_store.event.files">
                    <a v-if="img.file_types[0].name === 'image'" :href="img.link"><img class=" max-w-full rounded-lg my-2 hover:scale-110 transition duration-500 cursor-pointer object-cover" :src="img.link" :alt="img.name"></a>
                    <iframe v-if="img.file_types[0].name === 'video'" :src="img.link + '&autoplay=1&mute=1'" allow="encrypted-media; fullscreen; picture-in-picture;" autoplay width="100%" height="100%" frameborder="0"></iframe>
                </div>
            </div>
            <div class="lg:w-10/12 md:w-5/12 min-w-8/12 rounded-lg overflow-hidden sm:mr-10 p-10 flex items-end justify-start relative">
                <showMap/>
                <div class="bg-gray-200/50 hover:bg-gray-200/80 dark:bg-gray-900/30 hover:dark:bg-gray-900/50 relative flex flex-wrap py-6 rounded shadow-md max-w-[600px]">
                    <div class="lg:w-1/2 px-6">
                    <h2 class="title-font font-semibold text-black dark:text-white tracking-widest text-xs">Город: {{ event_store.event.city }} </h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-200">Адрес мероприятия: {{ event_store.event.address }}</p>
                    </div>
                    <div class="lg:w-1/2 px-6 mt-4 lg:mt-0">
                    <h2 class="title-font font-semibold text-black dark:text-white tracking-widest text-xs">Время</h2>
                    <p class="leading-relaxed text-gray-600 dark:text-gray-200">Начало: {{ event_store.event.date_start }}</p>
                    <p class="leading-relaxed text-gray-600 dark:text-gray-200">Конец: {{ event_store.event.date_end }}</p>
                    <h2 class="title-font font-semibold mt-4 text-black dark:text-white tracking-widest text-xs">Цена</h2>
                    <p class="leading-relaxed text-gray-600 dark:text-gray-200">{{ event_store.event.price }} руб.</p>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/3 md:w-1/2 flex flex-col md:ml-auto w-full md:py-8 mt-8 md:mt-0">
                <div class="items-center">
                    <button v-on:click="event_store.showModalLiked()" class="rounded text-sm mr-3 border dark:border-gray-500/30 p-1 dark:bg-gray-400/20 dark:hover:bg-gray-400/30 dark:text-gray-300 dark:hover:text-gray-100 text-gray-600 bg-gray-500/30 hover:bg-gray-500/20">Просмотр пользователей</button>
                </div>
                <label for="email" class="leading-7 text-sm text-gray-400">Автор</label>
                    <div class="w-full bg-gray-300 dark:bg-gray-800 rounded border-gray-400 border dark:border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-500 dark:text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <p class="leading-relaxed">
                           Имя: {{ event_store.event.author.name }}
                        </p>
                        <p class="leading-relaxed">
                           Email: {{ event_store.event.author.email }}
                        </p>
                    </div>
                <div class="relative mb-4">
                    <label for="name" class="leading-7 text-sm text-gray-400">ВК</label>
                    <div  class="w-full bg-gray-300 dark:bg-gray-800 rounded border-gray-400 border dark:border-gray-700 dark:border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-500 dark:text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <p class="leading-relaxed">
                            Источник: <a class="text-blue-500 dark:hover:text-blue-600 hover:text-blue-700" :href="'https://vk.com/public' + event_store.event.vk_group_id">{{ event_store.event.vk_group_id }}</a>
                        </p>
                        <p class="leading-relaxed">
                            Пост: <a class="text-blue-500 dark:hover:text-blue-600 hover:text-blue-700" :href="'https://vk.com/wall-' + event_store.event.vk_group_id + '_' + event_store.event.vk_post_id">{{ event_store.event.vk_post_id }}</a>
                        </p>
                    </div>
                </div>
                <div class="relative mb-4">
                        <label for="email" class="leading-7 text-sm text-gray-400">Тип мероприятия</label>
                        <div v-if="event_store.event.types.length !== 0" class="w-full bg-gray-300 dark:bg-gray-800 rounded border-gray-400 border dark:border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-500 dark:text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <p class="leading-relaxed" v-for="types of event_store.event.types">
                                <p>{{ types.name }}</p>
                            </p>
                        </div>
                        <div v-if="event_store.event.types.length === 0" class="w-full bg-red-300 dark:bg-gray-800 rounded border border-red-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-500 dark:text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <p class="leading-relaxed">
                                <p>Тип неопределён</p>
                            </p>
                        </div>
                    <label for="email" class="leading-7 text-sm text-gray-400">Статус мероприятия</label>
                    <div v-if="event_store.event.statuses.length !== 0" class="w-full bg-gray-300 dark:bg-gray-800 rounded border-gray-400 border dark:border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-500 dark:text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <p class="leading-relaxed" v-for="status of event_store.event.statuses">
                            <div v-if="status.pivot.last === true">
                                    {{ status.name }}
                            </div>
                        </p>
                    </div>
                    <button v-on:click="event_store.showModalHistory()" class="text-sm px-1 text-blue-300 hover:text-blue-500 dark:text-blue-600 dark:hover:text-blue-700">История статусов</button>
                    <div v-if="event_store.event.statuses.length === 0" class="w-full bg-red-300 dark:bg-red-800 rounded border border-red-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-500 dark:text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <p class="leading-relaxed">
                            Статус не определён
                        </p>
                    </div>
                </div>
                <div class="relative mb-1">
                    <label class="leading-7 text-sm text-gray-400">Описание</label>
                    <textarea disabled wrap="soft | hard" class="w-full bg-gray-300 dark:bg-gray-800 rounded border-gray-400 border dark:border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 h-32 text-base outline-none text-gray-500 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">{{event_store.event.description}}</textarea>
                </div>
                <!-- Коменты пока сломаны -->
                <!-- <button class="rounded text-sm text-blue-300 hover:text-blue-500 dark:text-blue-600 dark:hover:text-blue-700">Просмотреть коментарии</button> -->
                <div class="flex justify-center mt-3">
                    <button v-on:click="event_store.showUpdate()" class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Редактировать</button>
                    <div class="px-6"></div>
                    <button v-on:click="event_store.showStatuses()" class="text-white bg-green-500 border-0 py-2 px-6 focus:outline-none hover:bg-green-600 rounded text-lg">Сменить статус</button>
                </div>
                <div class="py-2"></div>
                <button v-on:click="event_store.closeUpdateEvent()" class="text-white bg-gray-500 border-0 py-2 px-6 focus:outline-none hover:bg-gray-600 rounded text-lg">Закрыть</button>
            </div>
        </div>
        </div>
    </section>
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

