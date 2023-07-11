<script>
import { useUsersStore } from '../../stores/usersStore';
import { useEventsStore } from '../../stores/eventsStore';
import Loader from '../Loader.vue'
import { defineComponent } from 'vue';

export default defineComponent({
    setup() {
        const user_store = useUsersStore();
        const event_store = useEventsStore();
        const pageN = "Вперёд &raquo;";
        const pageP = "&laquo; Назад";
        return {user_store, event_store, pageN, pageP }
    },
    components: {Loader},
})
</script>

<template>
    <div class="main-modal fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster" style="background: rgba(0,0,0,.7);">   
        <div class="flex h-screen w-full flex-col items-center justify-center gap-y-2">
            <div class="w-[500px] rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-800 bg-white py-4 px-2 shadow-md shadow-gray-600">
                <div class="flex items-center justify-between px-2 text-base font-medium text-gray-700 dark:text-gray-300">

                    <div v-if="user_store.link_menu_user === 'LikeEvent'" class="rounded px-2 py-1 bg-green-300 dark:bg-green-700">Like Event<p class="text-xs" v-if="user_store.menu_user_loader === false">Всего: {{ user_store.events_sights_user.total }}</p></div>
                    <button v-on:click="user_store.showLikeEvent()" v-if="user_store.link_menu_user !== 'LikeEvent'" class="rounded px-2 py-3 ">Like Event</button>

                    <div v-if="user_store.link_menu_user === 'FavoritesEvent'" class="rounded px-2 py-1 bg-green-300 dark:bg-green-700">Favorites Event<p class="text-xs" v-if="user_store.menu_user_loader === false">Всего: {{ user_store.events_sights_user.total }}</p></div>
                    <button v-on:click="user_store.showFavoritesEvent()" v-if="user_store.link_menu_user !== 'FavoritesEvent'" class="rounded px-2 py-3 ">Favorites Event</button>

                    <div v-if="user_store.link_menu_user === 'LikeSight'" class="rounded px-2 py-1 bg-green-300 dark:bg-green-700">Like Sight<p class="text-xs" v-if="user_store.menu_user_loader === false">Всего: {{ user_store.events_sights_user.total }}</p></div>
                    <button v-on:click="user_store.showLikeSight()" v-if="user_store.link_menu_user !== 'LikeSight'" class="rounded px-2 py-3 ">Like Sight</button>

                    <div v-if="user_store.link_menu_user === 'FavoritesSight'" class="rounded px-2 py-1 bg-green-300 dark:bg-green-700">Favorites Sight<p class="text-xs" v-if="user_store.menu_user_loader === false">Всего: {{ user_store.events_sights_user.total }}</p></div>
                    <button v-on:click="user_store.showFavoritesSight()" v-if="user_store.link_menu_user !== 'FavoritesSight'" class="rounded px-2 py-3 ">Favorites Sight</button>

                    <div>
                        <button @click="user_store.closeModalDetailed" class="flex h-5 w-5 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-800 dark:hover:bg-red-800 text-black">
                <svg class="h-5 w-5" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                </button>
                    </div>
                </div>
                <div class="mt-4 min-h-[400px]">
                    <div class="flex max-h-[451px] min-h-[451px] items-center justify-center p-5 bg-gray-100 min-w-screen dark:bg-gray-800" v-if="user_store.events_sights_user.total === 0 && user_store.menu_user_loader === false">
                        <div class="flex space-x-2">
                            <div class="rounded-full text-gray-500 text-2xl">Нет результатов</div>
                        </div>
                    </div>
                    <div class="flex min-h-[400px] flex items-center justify-center p-5 bg-gray-100 min-w-screen dark:bg-gray-800" v-if="user_store.menu_user_loader === true">
                        <div class="flex space-x-2 animate-pulse">
                            <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                        </div>
                    </div>
                    <div class="flex max-h-[400px] w-full flex-col  overflow-y-scroll" id="journal-scroll" v-if="user_store.menu_user_loader === false">
                        <button class="group flex items-center gap-x-5 rounded-md px-2.5 py-2 my-0.5 transition-all duration-75 bg-gray-200 dark:bg-gray-700 hover:bg-green-100 dark:bg-gray-700 dark:hover:bg-green-700 rounded-xl" v-for="item in user_store.events_sights_user.data" :key="item.id">
                            <div class="flex h-12 w-12 items-center rounded-lg bg-gray-200 text-black group-hover:bg-green-200">
                                <span class="tag w-full text-center text-2xl font-medium text-gray-700 group-hover:text-green-900">{{ item.id }}</span>
                            </div>
                        <div class="flex flex-col items-start justify-between font-light text-gray-600 dark:text-gray-300">
                            <p class="text-[15px]">Имя: {{ item.name }}</p>
                            <span class="text-xs font-light text-gray-400 dark:text-gray-200">Адрес: {{ item.address }}</span>
                            <span class="text-xs font-light text-gray-400 dark:text-gray-200">Тип: {{ item.types[0].name }}</span>
                            <span class="text-xs font-light text-gray-400 dark:text-gray-200">Автор: {{ item.author.name }}</span>
                        </div>
                        </button>
                    </div>
                    
                </div>
                <div class="flex items-center justify-between mt-6" v-if="user_store.events_sights_user.total !== 0 ">
                <div v-for="link in user_store.events_sights_user.links">
                    <div v-if=" link.label  === pageP && link.url !== null">
                        <a href="#" @click="user_store.showPage(link.url)" class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                            </svg>

                            <span>
                                Назад
                            </span>
                        </a>
                    </div>

                    <div>
                        <div v-if="link.active === true" class="items-center hidden md:flex gap-x-3">
                            <a href="#" @click="user_store.showPage(link.url)" class="px-2 py-1 text-sm text-blue-500 rounded-md dark:bg-gray-800 bg-blue-100/60">{{ link.label }}</a>
                        </div>

                        <div v-else-if="link.active == false && link.label !== pageP && link.label !== pageN">
                            <a href="#" @click="user_store.showPage(link.url)" class="px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">{{ link.label }}</a>
                        </div>
                    </div>

                    <div v-if="link.label === pageN && link.url !== null">
                        <a href="#" @click="user_store.showPage(link.url)" class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
                            <span>
                                Вперёд
                            </span>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
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