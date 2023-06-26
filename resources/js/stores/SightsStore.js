import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';


export const useSightsStore = defineStore('SightsStore', {
    state: () => ({
        config: {
            headers: { Authorization: `Bearer ${localStorage.token}` }
        },
        bodyParameters: {
            key: "value"
         },
        toast: useToastStore(),
        loader: true,
        sights: [],
        sight: [],
        links: [],
        count_sights: '',
        status: '',
        types: [],
        ModalSight: false,
    }),
    actions: {
        async getAllSights() {
            this.loader = true;
            const url = 'http://localhost:8000/api/sights?';
            await this.getUrlSights(url);
            this.loader = false;
        },
        async getUrlSights(url) {
            this.loader = true;
            await axios.get(url + '&pagination=true')
            .then(response => {
                this.sights = response.data.sights.data;
                this.links = response.data.sights.links;
            })
            .catch(error => this.toast.error('При получении url: "' + url + '" произошла ошибка: ' + error.message));
            this.loader = false;
        },
        async getStatusesSights() {
            await axios.get('http://localhost:8000/api/statuses', this.bodyParameters, this.config)
            .then(response => this.status = response.data.statuses)
            .catch(error => this.toast.error('Проблема с загрузкой статусов ' + error));
        },
        async getSightsSearch(name = '', sponsor = '', user = '', city = '', address = '', status = '', types = '', text ='') {
            this.loader = true;
            if (status === 'Все') { status = '' }
            if (types === 'Все') { types = '' }
            const url = 'http://localhost:8000/api/sights?name=' + name + '&sponsor=' + sponsor +  '&user=' + user +'&city=' + city + '&address=' + address + '&statuses=' + status + '&types=' + types + '&searchText=' + text; 
            await this.getUrlSights(url);
            this.loader = false;
        },
        async getTypesSights() {
            await axios.get('http://localhost:8000/api/sight-types')
            .then(response => this.types = response.data.types)
            .catch(error => this.toast.error('Ошибка при загрузке типов: ' + error.message))
        },
        async counterEvent() {
            await axios.get('http://localhost:8000/api/sights?statuses=На%модерации&pagination=true', this.bodyParameters, this.config)
            .then(response =>{ this.count_sights = response.data.sights.total; })
        },
        async showUpdateSight(id) {
            this.sight = id;
            this.ModalSight = true;
        },
        async closeUpdateSight() {
            this.sight = '';
            this.ModalSight = false;
        }
    },
})