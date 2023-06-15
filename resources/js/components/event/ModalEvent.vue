<script>
import { useEventsStore } from '../../stores/eventsStore';  
import { defineComponent } from 'vue';


export default defineComponent({
    setup: () => {
        const event_store = useEventsStore();
        return { event_store }
    }
})
</script>

<template>

<div class="main-modal fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster" style="background: rgba(0,0,0,.5);">
       
</div>
</template>