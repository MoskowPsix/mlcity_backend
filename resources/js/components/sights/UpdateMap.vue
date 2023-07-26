<script>
    import { useSightsStore } from '../../stores/SightsStore'; 
    import { defineComponent } from 'vue';
    import { YandexMap, YandexMarker} from 'vue-yandex-maps'

    export default defineComponent({
        setup: () => {
            const sights_store = useSightsStore();
            const settings = {
                apiKey: '226cca4a-d7de-46b5-9bc8-889f70ebfe64', // Индивидуальный ключ API
                lang: 'ru_RU', // Используемый язык
                coordorder: 'latlong', // Порядок задания географических координат
                debug: true, // Режим отладки
                version: '2.1' // Версия Я.Карт
            };
            const onMarker = (e) => {
                useSightsStore().updateCoord(e.originalEvent.target.geometry._coordinates[0], e.originalEvent.target.geometry._coordinates[1])
            };
            return{
                sights_store,
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
        :coordinates="[sights_store.sight.latitude, sights_store.sight.longitude]">
        <YandexMarker 
        
        :coordinates="[sights_store.sight.latitude, sights_store.sight.longitude]" 
        :marker-id="sights_store.sight.id"               
        :options="{
            preset: 'islands#violetDotIcon',
            draggable:'true',
        }"
        :events="['dragend']"
        @dragend="onMarker">
        </YandexMarker>
    </YandexMap>
</template>