<script>
import { useEventsStore } from '../../stores/eventsStore';  
import { defineComponent } from 'vue';
import ModalUpdateEvent from './ModalUpdateEvent.vue';
import ModalStatuses from './ModalStatuses.vue';
import ModalHistoryStatus from './ModalHistoryStatus.vue';




export default defineComponent ({
    setup: () => {
        const event_store = useEventsStore();
        return { event_store }
    },
    components: {ModalUpdateEvent, ModalStatuses, ModalHistoryStatus},

})
</script>

<template>

<div class="main-modal fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster" style="background: rgba(0,0,0,.5);">
    <ModalUpdateEvent v-if="event_store.ModalUpdate === true"/>
    <ModalStatuses v-if="event_store.ModalStatuses === true"/>
    <ModalHistoryStatus v-if="event_store.ModalHistoryStatus === true">
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
    <section class="text-gray-400 bg-gray-100 dark:bg-gray-800 body-font relative rounded-lg" v-if="event_store.ModalUpdate === false">
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
            <div class="lg:w-2/3 md:w-1/2 bg-gray-500 dark:bg-gray-900 rounded-lg overflow-hidden sm:mr-10 p-10 flex items-end justify-start relative">
                <iframe width="100%" height="100%" title="map" class="absolute inset-0" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" src="https://maps.google.com/maps?width=100%&amp;height=600&amp;hl=en&amp;q=%C4%B0zmir+(My%20Business%20Name)&amp;ie=UTF8&amp;t=&amp;z=14&amp;iwloc=B&amp;output=embed" style="filter: grayscale(1) contrast(1.2) opacity(0.16);"></iframe>
            <div class="bg-gray-200 dark:bg-gray-900 relative flex flex-wrap py-6 rounded shadow-md">
                <div class="lg:w-1/2 px-6">
                <h2 class="title-font font-semibold text-black dark:text-white tracking-widest text-xs">Город: {{ event_store.event.city }}</h2>
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
                            Пост: <a>https://vk.com/public{{ event_store.event.vk_group_id }}</a>
                        </p>
                        <p class="leading-relaxed">
                            Источник: <a>https://vk.com/wall-{{ event_store.event.vk_group_id }}_{{ event_store.event.vk_post_id }}</a>
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
                    <button v-on:click="event_store.showModalHistory()" class="px-3 text-blue-300 hover:text-blue-500 dark:text-blue-600 dark:hover:text-blue-700">История статусов</button>
                    <div v-if="event_store.event.statuses.length === 0" class="w-full bg-red-300 dark:bg-red-800 rounded border border-red-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-500 dark:text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <p class="leading-relaxed">
                            Статус не определён
                        </p>
                    </div>
                </div>
                <div class="relative mb-4">
                    <label for="message" class="leading-7 text-sm text-gray-400">писание</label>
                    <textarea disabled wrap="soft | hard" class="w-full bg-gray-300 dark:bg-gray-800 rounded border-gray-400 border dark:border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 h-32 text-base outline-none text-gray-500 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">{{event_store.event.description}}</textarea>
                </div>
                <div class="flex justify-center">
                    <button v-on:click="event_store.showUpdate()" class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Редактировать</button>
                    <div class="px-6"></div>
                    <button v-on:click="event_store.showStatuses()" class="text-white bg-green-500 border-0 py-2 px-6 focus:outline-none hover:bg-green-600 rounded text-lg">Сменить статус</button>
                </div>
                <div class="py-2"></div>
                <button v-on:click="event_store.closeUpdateEvent()" class="text-white bg-gray-500 border-0 py-2 px-6 focus:outline-none hover:bg-gray-600 rounded text-lg">Закрыть</button>
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