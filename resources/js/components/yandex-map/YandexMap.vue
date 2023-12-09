<template lang="">
    {{center}}
    <div>
        <YandexMap 
        class="absolute insert-0"
        :settings="settings"
        :zoom="zoom"
        :behaviors="[]"
        :controls="[]"
        :coordinates="center"
        >
        <YandexMarker v-if="marker"  :coordinates="[marker.latitbe, marker.longitube]" :marker-id="marker.id" />
        <div v-if="markers" v-for="marker in markers">
            <YandexMarker :coordinates="[marker.latitbe, marker.longitube]" :marker-id="marker.id"/>
        </div>
        </YandexMap>
    </div>
</template>
<script>
import { YandexMap, YandexMarker } from 'vue-yandex-maps';


export default {
    name: 'YandexMap',
    props: {
        markers: Array,
        marker: Object,
        center: Array,
        zoom: Number = 16,
    },
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