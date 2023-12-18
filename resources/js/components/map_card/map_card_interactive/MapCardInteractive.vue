<template lang="">
    <!-- {{center}}
    {{[marker.latitude, marker.longitude]}}
    {{zoom}} -->

    <div class="w-full h-full" id="map">
        <YandexMap
        class="w-full h-full"
        :settings="settings"
        :zoom="16"
        :behaviors="['scrollZoom', 'drag']"
        :controls="controls"
        :coordinates="[marker.latitude, marker.longitude]"
        @click="onClick"
        >
            <YandexMarker 
            :coordinates="[marker.latitude, marker.longitude]" 
            :marker-id="marker.id"               
            :events="[]">
            </YandexMarker>
    </YandexMap>
    </div>

</template>
<script>
import { YandexMap, YandexMarker, loadYmap  } from 'vue-yandex-maps';

export default {
    name: 'MapCardInteractive',
    props: {
        marker: Object,
    },
    components: {
        YandexMap,
        YandexMarker
    },
    data() {
        return {
            center: []
        }
    },
    setup() {
        const settings = {
            apiKey: import.meta.env.VITE_YANDEX_APP_KEY, // Индивидуальный ключ API
            lang: 'ru_RU', // Используемый язык
            coordorder: 'latlong', // Порядок задания географических координат
            debug: true, // Режим отладки
            version: '2.1' // Версия Я.Карт
        }
        // let center = [] 
        const controls =['fullscreenControl', 'rulerControl', 'typeSelector', 'searchControl']
        return {
            settings,
            controls,
            // center
        }
    },
    methods: {
        onClick(e) {
            ymaps.geocode(e.get('coords')).then(res =>  {
                var firstGeoObject = res.geoObjects.get(0);
                console.log('ok')
                this.$emit('onCoords', e.get('coords'))
                this.$emit('onAddress', firstGeoObject.properties._data.text)
            })
        },
    },
    async mounted() {
        await loadYmap({ ...this.settings, debug: true })
        // this.center = [this.marker.latitude, this.marker.longitude]
        console.log(this.center)
        // setInterval(async () => {
        //     await ymaps.Map("map", {state: [{center: [marker.latitude, marker.longitude], zoom: 16}]})
        //  }, 3000)
    },
    wath: {
        marker(marker) {
            let map = ymaps.Map("map", {state: [{center: [marker.latitude, marker.longitude], zoom: 16}]})
            console.log(map)
        }
    },
}
</script>
<style lang="">
    
</style>