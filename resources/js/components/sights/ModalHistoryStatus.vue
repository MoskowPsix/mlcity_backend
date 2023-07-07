<script>
import { defineComponent } from 'vue';
import { useSightsStore } from '../../stores/SightsStore';


export default defineComponent({
    setup: () => {
        const sights_store = useSightsStore();
        return { sights_store }
    },
    components: { },
    
})
</script>

<template>
    <div class="main-modal fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster" style="background: rgba(0,0,0,.5);">
        <div class="flex h-screen w-full flex-col items-center justify-center gap-y-2">
            <div class="w-[700px] rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-800 bg-white py-4 px-2 shadow-md shadow-gray-600">
                <div class="flex items-center justify-between px-2 text-base font-medium text-gray-700 dark:text-gray-300">
                        <div class="rounded px-2 py-1 bg-green-300 dark:bg-green-700">История</div>
                    <div>
                        <button v-on:click="sights_store.closeModalHistory()" class="flex h-5 w-5 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-800 dark:hover:bg-red-800 text-black">
                <svg class="h-5 w-5" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                </button>
                    </div>
                </div>
                <div class="flex max-h-[400px] w-full flex-col overflow-y-scroll" id="journal-scroll">
                        <slot>
                        </slot>
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