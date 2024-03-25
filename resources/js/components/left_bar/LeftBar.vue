<template>

<button data-drawer-toggle="logo-sidebar" data-drawer-target="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-5 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
      <!-- <a :href="urlFront">
         <button type="button" class="w-full flex items-center justify-center m-2 mx-auto px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-gray-900 dark:bg-gray-800 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700/50">
            <svg class="w-5 h-5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
            </svg>
            <span class="self-center text-lg font-semibold whitespace-nowrap dark:text-gary-300">Назад на mlcity.ru</span>
         </button>
      </a> -->
      <a href="/" class="flex items-center pl-2.5 mb-5">
         <img src="../../../assets/favicon.png" class="h-6 mr-3 sm:h-7" alt="Flowbite Logo" />
         <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">MyLittleCity<p class="text-xs items-center">{{role}}Panel</p></span>
         <div class="flex flex-row justify-between toggle mx-10">
        <label class="flex items-center cursor-pointer">
            <div class="relative">
            <input  @click="toggleDark()" type="checkbox" class="checkbox hidden">
            <div class="block border-[1px] dark:border-white border-gray-900 w-8 h-5 rounded-full"></div>
            <div class="dot absolute left-1 top-1 dark:bg-white bg-gray-800 w-3 h-3 rounded-full transition"></div>
            </div>
        </label>
        </div>
      </a>
      <ul class="space-y-2 font-medium">
         <li>
            <RouterLink :to="{name: 'my-events'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white enable-background:new 0 0 24 24" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path d="M16.53 11.06L15.47 10l-4.88 4.88-2.12-2.12-1.06 1.06L10.59 17l5.94-5.94zM19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Мои события</span>
            </RouterLink>
         </li>
         <li>
            <RouterLink :to="{name: 'my-sights'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 500 600" xmlns="http://www.w3.org/2000/svg">
                  <path d="M 501.62 92.11 L 267.24 2.04 a 31.958 31.958 0 0 0 -22.47 0 L 10.38 92.11 A 16.001 16.001 0 0 0 0 107.09 V 144 c 0 8.84 7.16 16 16 16 h 480 c 8.84 0 16 -7.16 16 -16 v -36.91 c 0 -6.67 -4.14 -12.64 -10.38 -14.98 Z M 64 192 v 160 H 48 c -8.84 0 -16 7.16 -16 16 v 48 h 448 v -48 c 0 -8.84 -7.16 -16 -16 -16 h -16 V 192 h -64 v 160 h -96 V 192 h -64 v 160 h -96 V 192 H 64 Z m 432 256 H 16 c -8.84 0 -16 7.16 -16 16 v 32 c 0 8.84 7.16 16 16 16 h 480 c 8.84 0 16 -7.16 16 -16 v -32 c 0 -8.84 -7.16 -16 -16 -16 Z"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Мои Места</span>
            </RouterLink>
         </li>
         <li v-if="role == 'Moderator' || role == 'Admin' || role == 'root'">
            <RouterLink :to="{name: 'events'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-green-400 group-hover:text-gray-900 dark:group-hover:text-white enable-background:new 0 0 24 24" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path d="M16.53 11.06L15.47 10l-4.88 4.88-2.12-2.12-1.06 1.06L10.59 17l5.94-5.94zM19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Все События</span>
               <span class="inline-flex items-center justify-center w-3 h-3 p-3 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">1</span>
            </RouterLink>
         </li>
         <li v-if="role == 'Moderator' || role == 'Admin' || role == 'root'">
            <RouterLink :to="{name: 'sights'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-green-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 500 600" xmlns="http://www.w3.org/2000/svg">
                  <path d="M 501.62 92.11 L 267.24 2.04 a 31.958 31.958 0 0 0 -22.47 0 L 10.38 92.11 A 16.001 16.001 0 0 0 0 107.09 V 144 c 0 8.84 7.16 16 16 16 h 480 c 8.84 0 16 -7.16 16 -16 v -36.91 c 0 -6.67 -4.14 -12.64 -10.38 -14.98 Z M 64 192 v 160 H 48 c -8.84 0 -16 7.16 -16 16 v 48 h 448 v -48 c 0 -8.84 -7.16 -16 -16 -16 h -16 V 192 h -64 v 160 h -96 V 192 h -64 v 160 h -96 V 192 H 64 Z m 432 256 H 16 c -8.84 0 -16 7.16 -16 16 v 32 c 0 8.84 7.16 16 16 16 h 480 c 8.84 0 16 -7.16 16 -16 v -32 c 0 -8.84 -7.16 -16 -16 -16 Z"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Все Места</span>
               <span class="inline-flex items-center justify-center w-3 h-3 p-3 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">1</span>
            </RouterLink>
         </li>
         <li v-if="role == 'Moderator' || role == 'Admin' || role == 'root'">
            <RouterLink :to="{name: 'edits'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-orange-400 group-hover:text-gray-900 dark:group-hover:text-white" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                  <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
               </svg>
               <span class="flex-1 ml-3 whitespace-nowrap">На редактуре</span>
               <span class="inline-flex items-center justify-center w-3 h-3 p-3 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">1</span>
            </RouterLink>
         </li>
         <!-- <li>
            <RouterLink :to="{name: 'role'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                  <path d="M15.83 9.81C14.7 9.7 13.69 10.38 13.46 11.5C13.46 11.84 14.81 12.29 16.05 12.29C17.29 12.29 18.41 11.5 18.41 11.28C18.41 11.05 17.63 9.93 15.83 9.81M8.18 9.81C6.38 9.93 5.59 10.94 5.59 11.27C5.59 11.5 6.82 12.29 7.95 12.29S10.54 11.84 10.54 11.5C10.31 10.38 9.19 9.7 8.18 9.81M16.95 16C15.04 16 13.8 13.75 12 13.75S8.85 16 7.05 16C4.69 16 3 13.86 3 10.04C3 7.68 3.68 7 6.71 7S10.54 8.24 12 8.24 14.36 7 17.29 7 21 7.79 21 10.04C21 13.86 19.31 16 16.95 16Z"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Роли</span>
         </RouterLink>
         </li> -->
         <li v-if="role == 'Admin' || role == 'root'">
            <RouterLink :to="{name: 'types'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg fill="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-blue-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                  viewBox="0 0 400 400" xml:space="preserve">
                  <g>
                     <path d="M69.897,265.767h260.206c1.822,0,3.509-0.958,4.442-2.522c0.934-1.564,0.976-3.505,0.111-5.107l-63.683-118.042
                        c-0.903-1.673-2.651-2.718-4.553-2.718H133.58c-1.902,0-3.65,1.045-4.553,2.718L65.344,258.138
                        c-0.865,1.603-0.823,3.543,0.111,5.107C66.388,264.809,68.075,265.767,69.897,265.767z M255.924,160.141l6.833,12.667
                        l-70.198,70.196h-21.822l82.863-82.863H255.924z M221.407,160.141l-82.863,82.863h-18.036l82.863-82.863H221.407z M171.178,160.141
                        l-58.853,58.854l31.75-58.854H171.178z M224.752,243.004l49.286-49.287l6.494,12.039l-37.25,37.248H224.752z M275.474,243.004
                        l16.339-16.339l8.815,16.339H275.474z"/>
                     <path d="M148.43,120.198h103.14c1.822,0,3.509-0.958,4.442-2.521c0.933-1.564,0.975-3.505,0.11-5.109l-51.57-95.588
                        c-0.902-1.674-2.651-2.719-4.553-2.719c-1.901,0-3.65,1.045-4.553,2.719l-51.57,95.588c-0.865,1.604-0.823,3.545,0.11,5.109
                        C144.921,119.24,146.608,120.198,148.43,120.198z"/>
                     <path d="M399.379,378.108l-49.872-92.444c-0.903-1.673-2.651-2.716-4.553-2.716H55.046c-1.901,0-3.65,1.043-4.553,2.716
                        L0.621,378.108c-0.865,1.604-0.823,3.544,0.11,5.108c0.933,1.564,2.621,2.522,4.442,2.522h389.653c1.822,0,3.509-0.958,4.442-2.522
                        C400.203,381.652,400.245,379.713,399.379,378.108z M333.223,307.78l28.662,53.126H38.116l28.662-53.126H333.223z"/>
                  </g>
               </svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Типы (Ещё нету)</span>
         </RouterLink>
         </li>
         <li v-if="role == 'Admin' || role == 'root'">
            <RouterLink :to="{name: 'users'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900"  class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-yellow-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Пользователи</span>
            </RouterLink>
         </li>
         <li v-if="role == 'root'">
            <RouterLink :to="{name: 'logs'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900"  class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-red-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg">
                  <g fill="evenodd" fill-rule="evenodd" transform="translate(2 1)">
                     <path fill-rule="nonzero" d="M3 4C3 3.44772 3.44772 3 4 3 4.55228 3 5 3.44772 5 4 5 4.55228 4.55228 5 4 5 3.44772 5 3 4.55228 3 4zM7 3C6.44772 3 6 3.44772 6 4 6 4.55228 6.44771 5 7 5L8 5C8.5523 5 9 4.55228 9 4 9 3.44772 8.5523 3 8 3L7 3zM6 7C6 6.44772 6.44772 6 7 6L8 6C8.5523 6 9 6.44772 9 7 9 7.55228 8.5523 8 8 8L7 8C6.44771 8 6 7.55228 6 7zM3 7C3 6.44772 3.44772 6 4 6 4.55228 6 5 6.44772 5 7 5 7.55228 4.55228 8 4 8 3.44772 8 3 7.55228 3 7zM6 10C6 9.4477 6.44772 9 7 9L8 9C8.5523 9 9 9.4477 9 10 9 10.5523 8.5523 11 8 11L7 11C6.44771 11 6 10.5523 6 10zM3 10C3 9.4477 3.44772 9 4 9 4.55228 9 5 9.4477 5 10 5 10.5523 4.55228 11 4 11 3.44772 11 3 10.5523 3 10z"/>
                     <path d="M0,1 C0,0.44772 0.44772,0 1,0 L11,0 C11.5523,0 12,0.44772 12,1 L12,13 C12,13.5523 11.5523,14 11,14 L1,14 C0.44772,14 0,13.5523 0,13 L0,1 Z M2,12 L2,2 L10,2 L10,12 L2,12 Z"/>
                  </g>
               </svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Telescope</span>
            </RouterLink>
         </li>
         <li>
            <a @click.prevent="logoutSubmit()" href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Выход в mlcity</span>
            </a>
         </li>
      </ul>
   </div>
</aside>
</template>

<script>
import { useDark, useToggle } from '@vueuse/core'
import { initFlowbite } from 'flowbite'
import { useAuthStore } from '../../stores/AuthStore'
import { mapActions, mapState } from 'pinia'
import { useLocalStorageStore } from '../../stores/LocalStorageStore'
import { useLoaderStore } from '../../stores/LoaderStore'
import { MessageAuth } from '../../enums/auth_messages'
import { useToast } from "vue-toastification"
import { onMounted } from 'vue'


export default {
   name: 'LeftBar',
   components: {Drawer},
   data()  {
      return {
         toast: useToast(),
         urlFront: import.meta.env.VITE_FRONT_APP_URL,
      }
   },
   setup() {
      onMounted(() => {
         initFlowbite();
      })
      const isDark = useDark();
      const toggleDark = useToggle(isDark);
      return {
         isDark,
         toggleDark
      }
   },
   computed: {
      ...mapState(useLocalStorageStore, ['role'])
   },
   methods: {
      ...mapActions(useAuthStore, ['logout']),
      ...mapActions(useLocalStorageStore, ['localStorageInit']),
      ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
      logTo() {
         window.location.href = 'http://localhost:8000/telescope'
      },
      logoutSubmit() {
        this.openLoaderFullPage()
        localStorage.clear()
        this.localStorageInit()
        this.toast.success(MessageAuth.success_logout)
        window.location.href = import.meta.env.VITE_FRONT_APP_URL
        // this.$router.push({name: 'login'})
      },
   },
}
  </script>

<style>
/* input:checked ~ .dot {
transform: translateX(100%);
} */
</style>

