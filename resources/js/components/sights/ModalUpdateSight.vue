<script>
import { useSightsStore } from '../../stores/SightsStore';  
import { defineComponent } from 'vue';
import UpdateMap from './UpdateMap.vue';



export default defineComponent({
    setup: () => {
        const sights_store = useSightsStore();
        const close = async () => {
            await useSightsStore().getSightId(sights_store.sight.id);
            await useSightsStore().closeUpdateSight(); 
        };
        const update = async () => {
            await useSightsStore().updateSights(); 
            await useSightsStore().updateSightsTypes(); 
            await useSightsStore().getSightId(sights_store.sight.id)
            await useSightsStore().closeUpdateSight(); 
        };
        return { 
            sights_store,
            close,
            update
        }
    },
    components: { UpdateMap},
    
})

useSightsStore().getTypesSights();

</script>


<template>
    <section class="max-h-full text-gray-400 bg-gray-100 dark:bg-gray-800 body-font relative rounded-lg overflow-y-scroll" id="journal-scroll">
        <div class="flex justify-between items-center pb-3 py-5 px-5">
            <label for="name" class="leading-7 px-5 text-sm text-gray-600 dark:text-gray-200">Имя</label>
                    <input type="text" v-model="sights_store.sight.name" placeholder="Имя"
                        class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                        <label for="email" class="leading-7 px-5 text-sm text-gray-600 dark:text-gray-200">Спонсор</label>
                    <input type="text" v-model="sights_store.sight.sponsor" placeholder="Спонсор"
                        class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
        </div>
        <div class="container px-5 py-5 mx-auto flex sm:flex-nowrap flex-wrap">
            <div class="lg:w-2/3 md:w-1/2 bg-gray-500 dark:bg-gray-800 rounded-lg overflow-hidden sm:mr-10 p-10 flex items-end justify-start relative">
                <UpdateMap/> 
            <div class="h-1/2 bg-gray-200/50 hover:bg-gray-200/80 dark:bg-gray-900/30 hover:dark:bg-gray-900/50 relative flex flex-wrap py-6 rounded shadow-md overflow-y-scroll" id="journal-scroll">
                <div class="lg:w-1/2 px-6">
                    <label for="email" class="leading-7 text-sm text-gray-700 dark:text-gray-200">Город</label>
                            <input type="text" v-model="sights_store.sight.city" placeholder="Город"
                                class="w-full bg-gray-400/30 dark:bg-gray-900/30 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                    <label for="email" class="leading-7 text-sm text-gray-700 dark:text-gray-200">Адресс</label>
                        <input type="text" v-model="sights_store.sight.address" placeholder="Адрес"
                            class="w-full bg-gray-400/30 dark:bg-gray-900/30 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                </div>
                <div class="lg:w-1/2 px-6 mt-4 lg:mt-0">
                    <label for="email" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Цена</label>
                    <input type="text" v-model="sights_store.sight.price" placeholder="Имя"
                        class="w-full bg-gray-400/30 dark:bg-gray-900/30 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                </div>
            </div>
            </div>
            <div class="lg:w-1/3 md:w-1/2 flex flex-col md:ml-auto w-full  mt-8 md:mt-0">
                <label for="name" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Имя</label>
                    <p type="text" class="w-full bg-gray-400 text-gray-600 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">{{ sights_store.sight.author.name }}</p>
                <label for="Email" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Email</label>
                    <p type="text" class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">{{ sights_store.sight.author.email }}</p>
                <div class="relative mb-4">
                    <label for="Post_id" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Post_id</label>
                    <input type="text" v-model="sights_store.sight.vk_post_id" placeholder="Post id"
                        class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                    
                </div>
                <div class="relative mb-4">
                    <label for="Source_id" class="leading-7 text-sm text-gray-600 dark:text-gray-400">Source_id</label>
                    <input type="text" v-model="sights_store.sight.vk_group_id" placeholder="Source id"
                        class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"/>
                </div>
                <div class="relative mb-4">
                    <label for="types" class="leading-7 text-sm text-gray-600 dark:text-gray-400" v-if="sights_store.sight.types.length !== 0" >Текущий тип мероприятия: {{ sights_store.sight.types[0].name }}</label>
                    <label for="types" class="leading-7 text-sm text-red-400" v-if="sights_store.sight.types.length === 0">Текущий тип не определён</label>
                    <div class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">
                        Изменить на:
                        <select v-model="sights_store.new_types" class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">
                            <option v-for="types in sights_store.types" :value="types.id">
                                {{ types.name }}
                            </option>
                            <option> </option>
                        </select>
                    </div>
                    <label for="email" class="leading-7 text-ml text-gray-600 dark:text-gray-400">Статус</label>
                    <div v-if="sights_store.sight.statuses.length !== 0" class="w-full bg-gray-400 dark:bg-gray-800 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-600 dark:text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <p class="leading-relaxed" v-for="status of sights_store.sight.statuses">
                            <div v-if="status.pivot.last === true">
                                    {{ status.name }}     
                            </div>
                        </p>
                    </div>
                    <div v-if="sights_store.sight.statuses.length === 0" class="w-full bg-red-900 rounded border border-red-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <p class="leading-relaxed">
                            Статус не определён
                        </p>
                    </div>
                </div>
                <div class="relative mb-4">
                    <label for="message" class="leading-7 text-sm text-gray-400">Описание</label>
                        <textarea v-model="sights_store.sight.description"  class="w-full bg-gray-400 dark:bg-gray-900 rounded border border-gray-500 dark:border-gray-700 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-600 dark:text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                </div>
                <div class="flex justify-center">
                    <div class="px-6">
                        <button v-on:click="close" class="text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded text-lg">Отмена</button>
                    </div>
                    <div class="px-6">
                        <button v-on:click="update" class="text-white bg-green-500 border-0 py-2 px-6 focus:outline-none hover:bg-green-600 rounded text-lg">Применить</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>