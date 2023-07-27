<script>
    import { useEventsStore } from '../../stores/eventsStore'; 
    import { defineComponent } from 'vue';
    import { YandexMap, YandexMarker} from 'vue-yandex-maps'

    export default defineComponent({
        setup: () => {
            const event_store = useEventsStore();
            const settings = {
                apiKey: '226cca4a-d7de-46b5-9bc8-889f70ebfe64', // Индивидуальный ключ API
                lang: 'ru_RU', // Используемый язык
                coordorder: 'latlong', // Порядок задания географических координат
                debug: true, // Режим отладки
                version: '2.1' // Версия Я.Карт
            };
            const onMarker = (e) => {
                useEventsStore().updateCoord(e.originalEvent.target.geometry._coordinates[0], e.originalEvent.target.geometry._coordinates[1])
            };
            return{
                event_store,
                settings,
                onMarker
                
            }
        },
        components: {YandexMap, YandexMarker},
        
    })
</script>

<template> 
    <YandexMap
        class="absolute inset-0"
        :settings="settings"
        :zoom="16"
        :behaviors="['drag', 'scrollZoom']"
        :controls="['fullscreenControl', 'rulerControl', 'typeSelector', 'searchControl']"
        :coordinates="[event_store.event.latitude, event_store.event.longitude]">
        <YandexMarker 
        
        :coordinates="[event_store.event.latitude, event_store.event.longitude]" 
        :marker-id="event_store.event.id"               
        :options="{
            preset: 'islands#violetDotIcon',
            draggable:'true',
        }"
        :events="['dragend']"
        @dragend="onMarker">
        </YandexMarker>
    </YandexMap>
</template>