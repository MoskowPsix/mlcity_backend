<template lang="">
    <div>
        <YandexMap
        class="w-full h-full"
        :settings="settings"
        :zoom="16"
        :behaviors="['scrollZoom', 'drag']"
        :controls="controls"
        :coordinates="[marker.latitude, marker.longitude]"
        >
            <YandexMarker v-if="marker"
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
    name: 'MapCardOnlyRead',
    props: {
        marker: Object,
        markers: Array
    },
    components: {
        YandexMap,
        YandexMarker
    },
    setup() {
        const settings = {
            apiKey: import.meta.env.VITE_YANDEX_APP_KEY+ '&' + `suggest_apikey=${import.meta.env.VITE_YANDEX_APP_KEY_SUBGEKT}`, // Индивидуальный ключ API
            lang: 'ru_RU', // Используемый язык
            coordorder: 'latlong', // Порядок задания географических координат
            debug: true, // Режим отладки
            version: '2.1' // Версия Я.Карт
        }
        const controls =['fullscreenControl', 'rulerControl']
        return {
            settings,
            controls
        }
    },
    methods: {
       
    },
    async mounted() {
        // await loadYmap({ ...this.settings, debug: true })
    },
}
</script>
<style lang="">
    
</style>