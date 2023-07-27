<script>
import Loader from '../Loader.vue';
import SearchSights from './SearchSights.vue';
import ModalSight from './ModalSight.vue'
import { defineComponent } from 'vue';
import { useSightsStore } from '../../stores/SightsStore'


export default defineComponent({
    setup: () => {
        const sights_store = useSightsStore();
        const pageN = "Вперёд &raquo;";
        const pageP = "&laquo; Назад";

        return { 
            sights_store,
            pageN, 
            pageP,

        }
    },
    components: { Loader, SearchSights, ModalSight },
    
})


useSightsStore().getSightsSearch();
useSightsStore().getTypesSights();
useSightsStore().getStatusesSights();

</script>

<template>
<section class="container  mx-auto"> 
    <SearchSights class="m-1"/>
    <ModalSight v-if="sights_store.ModalSight === true"/>
    <Loader v-if="sights_store.loader === true"/>
    <div v-if="sights_store.loader === false" class="flex flex-col">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8" id="journal-scroll">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden border m-1 border-gray-200 dark:border-gray-700 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center gap-x-3">
                                            <span>ID</span>
                                    </div>
                                </th>

                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <div>Название</div>
                                    <div>(post_id / source_id)</div>
                                </th>

                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Спонсор
                                </th>

                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Адрес
                                </th>

                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    ID / Автор
                                    <p>(Почта)</p>
                                </th>
                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Статус
                                </th>
                                <th scope="col" class="px-20 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    Тип
                                </th>
                
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900" v-for="sight of sights_store.sights" :key="sight.id" >
                            <tr @click="sights_store.showSight(sight)" class="capitalize transition-colors duration-200 rounded-md gap-x-2 hover:bg-gray-200 dark:bg-gray-900  dark:hover:bg-gray-800">
                                <td class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    <div class="inline-flex items-center gap-x-3">
                                        <span>{{ sight.id }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 w-2/12">
                                    <h2 class="text-sm font-medium text-gray-800 dark:text-white ">{{ sight.name }}</h2>
                                    <p class="text-xs font-normal text-gray-600 dark:text-gray-400">
                                        
                                        <button class=" text-blue-500 transition-colors duration-200 dark:hover:text-indigo-500 hover:text-indigo-500 focus:outline-none">
                                            {{ sight.vk_post_id }}
                                        </button>
                                        
                                        /
                                        <button class="px-1 text-blue-500 transition-colors duration-200 hover:text-indigo-500 focus:outline-none">
                                            {{ sight.vk_group_id }}
                                        </button>
                                        
                                    </p>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 w-2/12 whitespace-nowrap">{{ sight.sponsor }}</td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 w-2/12">
                                    <div class="flex items-center gap-x-2">
                                        <div>
                                            <h2 class="text-sm font-medium text-gray-800 dark:text-white ">{{ sight.city }}</h2>
                                            <p class="text-xs font-normal text-gray-600 dark:text-gray-400">{{ sight.address }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 w-2/12">
                                    <h2 class="text-sm font-medium text-gray-800 dark:text-white ">{{ sight.user_id }} / {{ sight.author.name }}</h2>
                                            <p class="text-xs font-normal text-gray-600 dark:text-gray-400">{{ sight.author.email }}</p>
                                </td>
                                <td  v-if="sight.statuses.length !== 0" class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 w-2/12">
                                    <div v-for="status of sight.statuses">
                                        <div v-if="status.pivot.last === true">
                                            {{ status.name }}
                                        </div>
                                    </div>
                                </td>
                                <td  v-if="sight.statuses.length === 0" class="px-4 py-4 text-sm text-red-800 dark:text-red-500 w-2/12">
                                    <div>Не определено</div>
                                </td>
                                <td class="px-4 py-4 text-sm whitespace-nowrap">
                                    <div class="flex items-center gap-x-6">
                                            <div v-if="sight.types.length !== 0" class="w-full  focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-500 dark:text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <p class="leading-relaxed" v-for="types of sight.types">
                                                    <p>{{ types.name }}</p>
                                                </p>
                                            </div>
                                            <div v-if="sight.types.length === 0" class="w-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-red-500 dark:text-red-400 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <p class="leading-relaxed">
                                                    <p>Тип не определён</p>
                                                </p>
                                            </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Конец списка -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between mx-1 mt-6">
        <div v-for="link in sights_store.links">
            <div v-if=" link.label  === pageP && link.url !== null">
                <a href="#/sights" @click="sights_store.getUrlSights(link.url)" class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
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
                    <a href="#/sights" @click="sights_store.getUrlSights(link.url)" class="px-2 py-1 text-sm text-blue-500 rounded-md dark:bg-gray-800 bg-blue-100/60">{{ link.label }}</a>
                </div>

                <div v-else-if="link.active == false && link.label !== pageP && link.label !== pageN">
                    <a href="#/sights" @click="sights_store.getUrlSights(link.url)" class="px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">{{ link.label }}</a>
                </div>
            </div>

            <div v-if="link.label === pageN && link.url !== null">
                <a href="#/sights" @click="sights_store.getUrlSights(link.url)" class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
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
</section> 
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