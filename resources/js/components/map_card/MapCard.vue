<template lang="">
    <!-- {{center}}
    {{[marker.latitude, marker.longitude]}}
    {{zoom}} -->
    <div class="w-full h-full">
        <YandexMap
        class="w-full h-full"
        :settings="settings"
        :zoom="zoom"
        :behaviors="[]"
        :controls="[]"
        :coordinates="center">
            <YandexMarker 
            :coordinates="[marker.latitude, marker.longitude]" 
            :marker-id="marker.id"               
            :events="[]">
            </YandexMarker>
    </YandexMap>
    </div>
</template>
<script>
import { YandexMap, YandexMarker } from 'vue-yandex-maps';


export default {
    name: 'MapCard',
    // props: {
    //     markers: Array,
    //     marker: Object,
    //     center: Array,
    //     zoom: Number = 16,
    // },
    props: [ 
        'markers',
        'marker',
        'center',
        'zoom',
    ],
    components: {
        YandexMap,
        YandexMarker
    },
    setup() {
        const settings = {
            apiKey: '226cca4a-d7de-46b5-9bc8-889f70ebfe64', // Индивидуальный ключ API
            lang: 'ru_RU', // Используемый язык
            coordorder: 'latlong', // Порядок задания географических координат
            debug: true, // Режим отладки
            version: '2.1' // Версия Я.Карт
        }
        return {settings}
    }
}
</script>
<style lang="">
    
</style>