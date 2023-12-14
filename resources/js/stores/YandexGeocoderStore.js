import axios from 'axios';
import { defineStore } from 'pinia';
import { from } from 'rxjs';



export const useYandexGeocoderStore = defineStore('useYandexGeocoder', {
    actions: {
        getAddressList(address) {
            const params = {
                format: 'json',
                apikey: import.meta.env.VITE_YANDEX_APP_KEY
            }
            return from(axios.get('https://geocode-maps.yandex.ru/1.x/', {params}))
        }
    },
})