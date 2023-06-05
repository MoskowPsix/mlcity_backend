<script>
import {useUsersStore} from '../../stores/usersStore'
import {mapActions} from 'pinia'
import VueTailwindDatepicker from 'vue-tailwind-datepicker'


export default {
    name: 'searchUsers',
    methods: mapActions(useUsersStore, ['getUsers']),
     clear() {
        user_email = '';
        user_name = '';
        time = '';
    }
    
    
}

</script>

<template>

                <div class="border border-gray-800 p-6 grid grid-cols-1 gap-6 dark:bg-gray-700 shadow-lg rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="grid grid-cols-2 gap-2 rounded">
                            <div class="flex rounded bg-gray-800 items-center p-2 ">
                                <svg class="fill-current text-gray-200 mr-2 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                    <path class="heroicon-ui"
                                          d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z"/>
                                </svg>
                                <input type="text" v-model="user_name" placeholder="Имя пользователя..."
                                       class="bg-gray-800 text-gray-200  max-w-full focus:outline-none text-gray-700"/>
                            </div>
                            <div class="flex rounded bg-gray-800 items-center p-2 ">
                                <svg  class="fill-current text-gray-200 mr-2 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                    <path class="heroicon-ui"
                                          d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zM5.68 7.1A7.96 7.96 0 0 0 4.06 11H5a1 1 0 0 1 0 2h-.94a7.95 7.95 0 0 0 1.32 3.5A9.96 9.96 0 0 1 11 14.05V9a1 1 0 0 1 2 0v5.05a9.96 9.96 0 0 1 5.62 2.45 7.95 7.95 0 0 0 1.32-3.5H19a1 1 0 0 1 0-2h.94a7.96 7.96 0 0 0-1.62-3.9l-.66.66a1 1 0 1 1-1.42-1.42l.67-.66A7.96 7.96 0 0 0 13 4.06V5a1 1 0 0 1-2 0v-.94c-1.46.18-2.8.76-3.9 1.62l.66.66a1 1 0 0 1-1.42 1.42l-.66-.67zM6.71 18a7.97 7.97 0 0 0 10.58 0 7.97 7.97 0 0 0-10.58 0z"/>
                                </svg>
                                <input type="text" v-model="user_email" placeholder="Email пользователя"
                                       class="bg-gray-800 text-gray-200 max-w-full focus:outline-none text-gray-700"/>
                            </div>
                        </div>
                         
            
                            <vue-tailwind-datepicker :dateFormat="yyyy-DD-MM-HH-MM-SS" :formatter="formatter" v-model="time" placeholder="Когда был зарегистрирован..." />
                            
                        
                    </div>
                    <div class="flex justify-center text-gray-200"><button v-on:click="getUsers(user_name, user_email, time), clear()" class="p-2 w-1/4 rounded-md bg-blue-800 text-blue-100">Найти</button></div>
                </div>
</template>