<script>
import { useEventsStore } from '../../stores/eventsStore';  
import { useStatusStore } from '../../stores/statusStore';
import { defineComponent } from 'vue';



export default defineComponent({
    setup: () => {
        const event_store = useEventsStore();
        const status_store = useStatusStore();
        return { event_store, status_store }
    },
    
})

useStatusStore().getStatus();
</script>

<template>
<div class="main-modal fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster" style="background: rgba(0,0,0,.5);">
    <section class="text-gray-400 bg-gray-800 body-font relative rounded-lg">
        <label for="status" class="leading-7 px-5 text-sm text-gray-400" v-if="event_store.event.statuses.length !== 0" v-for="status of event_store.event.statuses">Текущий тип мероприятия: {{ status.name }}</label>
        <label for="status" class="leading-7 px-5 text-red-400" v-if="event_store.event.statuses.length === 0">Текущий тип не определён</label>
            <div class="flex justify-between items-center pb-3 py-5 px-5">
                    <div class="w-full bg-gray-800 rounded border border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        Cменить на:
                        <select v-model="event_store.new_status" class="border border-gray-700 rounded bg-gray-900 text-gray-200  max-w-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 text-base outline-none text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out ">
                            <option v-for="status in status_store.status" :value="status.id">
                                {{ status.name }}
                            </option>
                            <option> </option>
                        </select>
                    </div>
                <div class="flex justify-center">
                    <div class="px-6">
                        <button v-on:click="event_store.closeStatuses()" class="text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded text-lg">Отмена</button>
                    </div>
                    <div class="px-6">
                        <button v-on:click="event_store.updateEventStatus()" class="text-white bg-green-500 border-0 py-2 px-6 focus:outline-none hover:bg-green-600 rounded text-lg">Применить</button>
                    </div>
                </div>
            </div>
    </section>
</div>
</template>