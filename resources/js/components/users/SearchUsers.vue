<script>
import {useUsersStore} from '../../stores/usersStore'
import VueTailwindDatepicker from 'vue-tailwind-datepicker'


export default {
    name: 'searchUsers',
    setup: () => {
        const user_store =useUsersStore();
        return {user_store}
    },
    components: {
        VueTailwindDatepicker
    }  
}
</script>

<template>
    <div class="border p-6 grid grid-cols-1 gap-6 border-gray-300 dark:bg-gray-700 shadow-lg rounded-lg dark:border-gray-800">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="grid grid-cols-2 gap-2 rounded">
                <input type="text" v-model="user_store.user_search.name" placeholder="Имя пользователя"
                    class="rounded px-5 bg-gray-200 dark:bg-gray-800 text-gray-200  dark:text-gray-200 max-w-full focus:outline-none text-gray-700"/>
                <input type="text" v-model="user_store.user_search.email" placeholder="Почта пользователя"
                    class="rounded px-5 bg-gray-200 dark:bg-gray-800 dark:text-gray-200 text-gray-200 max-w-full focus:outline-none text-gray-700"/>
                <input type="text" v-model="user_store.user_search.city" placeholder="Город пользователя"
                    class="rounded px-5 bg-gray-200 dark:bg-gray-800 dark:text-gray-200 text-gray-200 max-w-full focus:outline-none text-gray-700"/>
                <input type="text" v-model="user_store.user_search.region" placeholder="Регион пользователя"
                    class="rounded px-5 bg-gray-200 dark:bg-gray-800 dark:text-gray-200 text-gray-200 max-w-full focus:outline-none text-gray-700"/>
            </div>
            <VueTailwindDatepicker v-model="user_store.user_search.time" placeholder="Когда был зарегистрирован" />
        </div>
        <div class="flex justify-center text-gray-200"><button v-on:click="user_store.getUsers();" class="p-2 w-1/4 rounded-md bg-blue-800 text-blue-100">Найти</button></div>
        <div class=" text-gray-400 dark:text-gray-200">
            <button v-on:click="user_store.clearSearch()" class="px-1 rounded-md dark:bg-green-800 text-green-100 dark:hover:bg-green-700 bg-green-600 hover:bg-green-500">
                Сбросить фильтры
            </button>
        </div>
    </div>
</template>