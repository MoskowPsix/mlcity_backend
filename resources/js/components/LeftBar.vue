<template>
   <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 dark:bg-gray-900" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
      <a href="/" class="flex items-center pl-2.5 mb-5">
         <!-- <img src="" class="h-6 mr-3 sm:h-7" alt="Flowbite Logo" /> -->
         <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">AdminPanel</span>
         <div class="flex flex-row justify-between toggle px-20">
            <label class="flex items-center cursor-pointer">
                  <div class="relative">
                  <input @click="toggleDark()" type="checkbox" class="checkbox hidden">
                  <div class="block border-[1px] dark:border-white border-gray-900 w-8 h-5 rounded-full"></div>
                  <div class="dot absolute left-1 top-1 dark:bg-white bg-gray-800 w-3 h-3 rounded-full transition"></div>
                  </div>
            </label>
         </div>
      </a>
      <ul class="space-y-2 font-medium">
         <!-- <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
               <span class="ml-3">Dashboard</span>
            </a>
         </li> -->
         <li>
            <RouterLink :to="{name: 'events'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">События</span>
               <span class="inline-flex items-center justify-center w-3 h-3 p-3 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">{{event_store.count_moder}}</span>
               <!-- <span class="inline-flex items-center justify-center px-2 ml-3 text-sm font-medium text-gray-800 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-300">Pro</span> -->
            </RouterLink>
         </li>
         <li>
            <RouterLink :to="{name: 'sights'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 500 600" xmlns="http://www.w3.org/2000/svg">
                  <path d="M 501.62 92.11 L 267.24 2.04 a 31.958 31.958 0 0 0 -22.47 0 L 10.38 92.11 A 16.001 16.001 0 0 0 0 107.09 V 144 c 0 8.84 7.16 16 16 16 h 480 c 8.84 0 16 -7.16 16 -16 v -36.91 c 0 -6.67 -4.14 -12.64 -10.38 -14.98 Z M 64 192 v 160 H 48 c -8.84 0 -16 7.16 -16 16 v 48 h 448 v -48 c 0 -8.84 -7.16 -16 -16 -16 h -16 V 192 h -64 v 160 h -96 V 192 h -64 v 160 h -96 V 192 H 64 Z m 432 256 H 16 c -8.84 0 -16 7.16 -16 16 v 32 c 0 8.84 7.16 16 16 16 h 480 c 8.84 0 16 -7.16 16 -16 v -32 c 0 -8.84 -7.16 -16 -16 -16 Z"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Места интереса</span>
               <span class="inline-flex items-center justify-center w-3 h-3 p-3 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">{{sights_store.count_sights}}</span>
               <!-- <span class="inline-flex items-center justify-center px-2 ml-3 text-sm font-medium text-gray-800 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-300">Pro</span> -->
            </RouterLink>
         </li>
         <li>
            <RouterLink :to="{name: 'role'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                  <path d="M15.83 9.81C14.7 9.7 13.69 10.38 13.46 11.5C13.46 11.84 14.81 12.29 16.05 12.29C17.29 12.29 18.41 11.5 18.41 11.28C18.41 11.05 17.63 9.93 15.83 9.81M8.18 9.81C6.38 9.93 5.59 10.94 5.59 11.27C5.59 11.5 6.82 12.29 7.95 12.29S10.54 11.84 10.54 11.5C10.31 10.38 9.19 9.7 8.18 9.81M16.95 16C15.04 16 13.8 13.75 12 13.75S8.85 16 7.05 16C4.69 16 3 13.86 3 10.04C3 7.68 3.68 7 6.71 7S10.54 8.24 12 8.24 14.36 7 17.29 7 21 7.79 21 10.04C21 13.86 19.31 16 16.95 16Z"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Роли</span>
         </RouterLink>
         </li>
         <li>
            <RouterLink :to="{name: 'users'}" exact-active-class="bg-gray-400 hover:bg-gray-400 dark:bg-gray-900 dark:hover:bg-gray-900"  class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Users</span>
            </RouterLink>
         </li>
         <li>
            <a @click="Logout" href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Выход</span>
            </a>
         </li>
      </ul>
   </div>
</aside>
</template>

<script>
   import { useRouter } from 'vue-router';
   import { useDark, useToggle } from '@vueuse/core';
   import { useEventsStore } from '../stores/eventsStore';
   import { useSightsStore } from '../stores/SightsStore';
   

export default {
   name: 'LeftBar',

   setup() {
      const event_store = useEventsStore();
      const sights_store = useSightsStore();
      const isDark = useDark();
      const toggleDark = useToggle(isDark);
      const router = useRouter();
      const Logout = () => {
         router.push({ path: '/login' });
         localStorage.clear();
      };
      let inter = '';
      let inter_sight ='';

      return { Logout, isDark, toggleDark, event_store,  inter, inter_sight , sights_store};
      },
      mounted:function () {
         this.event_store.counterEvent(),
         this.sights_store.counterSight(),
         this.inter = setInterval(() => {
            this.event_store.counterEvent();
            this.sights_store.counterSight();
         }, 10000)
      },
   }
  </script>
<style>

.active-link {
  color: red;
}

.exact-active-link {
  color: blue;
}

input:checked ~ .dot {
        transform: translateX(100%);
        /* background-color: #132b50; */
        }

</style>

