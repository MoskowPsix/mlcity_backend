<template lang="">
    <div>
        <!-- <input id="search-map" placeholder="Поиск по адресу" type="text" name="search_address" class="m-1 w-[96%] border rounded-lg flex items-center dark:bg-gray-700/20 dark:border-gray-600/50"> -->
        <YandexMap
        :id="'map'"
        class="w-full h-full"
        :settings="settings"
        :zoom="16"
        :behaviors="['scrollZoom', 'drag']"
        :controls="controls"
        :coordinates="marker"
        @click="onClick"
        @created="onMapInitialized"
        >
            <YandexMarker 
            :coordinates="marker" 
            :marker-id="1"               
            :events="[]">
            </YandexMarker>
    </YandexMap>
    </div>

</template>
<script>
import { YandexMap, YandexMarker, YaGeocoderService  } from 'vue-yandex-maps';
import { unref } from 'vue'

export default {
    name: 'MapCardInteractive',
    props: {
        marker: Array, // Приходят координаты
    },
    components: {
        YandexMap,
        YandexMarker,
        YaGeocoderService
    },
    setup() {
        // Настройки карты
        const settings = {
            apiKey: import.meta.env.VITE_YANDEX_APP_KEY+ '&' + `suggest_apikey=${import.meta.env.VITE_YANDEX_APP_KEY_SUBGEKT}`, // Индивидуальный ключ API
            lang: 'ru_RU', // Используемый язык
            coordorder: 'latlong', // Порядок задания географических координат
            debug: true, // Режим отладки
            version: '2.1' // Версия Я.Карт
        }
        // Элементы управления
        const controls =['fullscreenControl', 'rulerControl', 'typeSelector']
        // const controls =['fullscreenControl', 'rulerControl', 'typeSelector', 'searchControl']
        // Переменная для инстанса карты
        let map = {}
        return {
            map,
            settings,
            controls,
        }
    },
    methods: {
        // При инициализации достаём инстанс карты
        async onMapInitialized(map) {
            this.map = map
            // Устанавливаем центр карты по внутреннему объекту на всякий
            setTimeout(() => {
                map.setBounds(map.geoObjects.getBounds()).then(() => {
                    // Устанавливаем зум
                    if (map.getZoom() > 16) map.setZoom(16);
                });
            }, 300);

            // const search = new ymaps.SuggestView();  
            // search.events.add('select', () => {     
            //         this.ForwardGeocoder()
            // })
        },
        // Достаём событие клика по карте
        onClick(e) {
            // Получаем адрес из пришедших координат
            ymaps.geocode(e.get('coords')).then(res =>  {
                var firstGeoObject = res.geoObjects.get(0);
                // Отправляем координаты
                this.$emit('onCoords', e.get('coords'))
                //Отправляем адрес
                this.$emit('onAddress', firstGeoObject.properties._data.text)
            })
        },
    },
    watch: {
        marker(marker) {
            // После изменения входных координат принудительно устанавливаем центр карты, иначе улетает по нулевым координатам
            setTimeout(() => {
                this.map.setCenter(this.marker)
            },1000)
        }
    },
}
</script>
<style lang="">
    
</style>