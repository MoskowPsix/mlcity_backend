<script>
import {useUsersStore} from '../../stores/usersStore'
import VueTailwindDatepicker from 'vue-tailwind-datepicker'


export default {
    name: 'searchUsers',
    setup: () => {
        const user_store =useUsersStore();
        const user_name = '';
        const user_email = '';
        const time = '';
        return {user_name, user_email, time, user_store}
    },
    methods: {
        clear() {
        this.user_email = '';
        this.user_name = '';
        this.time = '';
        }
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
                <input type="text" v-model="user_name" placeholder="Название мероприятия..."
                    class="rounded px-5 bg-gray-200 dark:bg-gray-800 text-gray-200  dark:text-gray-200 max-w-full focus:outline-none text-gray-700"/>
                <input type="text" v-model="user_email" placeholder="Спонсор мероприятия"
                    class="rounded px-5 bg-gray-200 dark:bg-gray-800 dark:text-gray-200 text-gray-200 max-w-full focus:outline-none text-gray-700"/>
            </div>
            <VueTailwindDatepicker v-model="time" placeholder="Когда был зарегистрирован..." />
        </div>
        <div class="flex justify-center text-gray-200"><button v-on:click="user_store.getUsers(user_name, user_email, time); clear()" class="p-2 w-1/4 rounded-md bg-blue-800 text-blue-100">Найти</button></div>
    </div>
</template>