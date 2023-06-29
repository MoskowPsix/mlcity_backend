import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';


export const useSightsStore = defineStore('SightsStore', {
    state: () => ({
        toast: useToastStore(),
        loader: true,
        sights: [],
        sight: [],
        links: [],
        first_page_url: [],
        count_sights: '',
        status: '',
        new_status: '',
        types: [],
        new_types: '',
        ModalSight: false,
        ModalUpdateSight: false,
        ModalStatusSight: false,
    }),
    actions: {
        async getAllSights() {
            this.loader = true;
            const url = 'sights?pagination=true';
            await this.getUrlSights(url);
            this.loader = false;
        },
        async getUrlSights(url) {
            this.loader = true;
            await axios.get(url)
            .then(response => {
                this.sights = response.data.sights.data;
                this.links = response.data.sights.links;
                this.first_page_url = response.data.sights.first_page_url;
            })
            .catch(error => this.toast.error('При получении url: "' + url + '" произошла ошибка: ' + error.message));
            this.loader = false;
        },
        async getStatusesSights() {
            await axios.get('statuses')
            .then(response => this.status = response.data.statuses)
            .catch(error => this.toast.error('Проблема с загрузкой статусов ' + error));
        },
        async getSightsSearch(name = '', sponsor = '', user = '', city = '', address = '', status = '', types = '', text ='') {
            this.loader = true;
            if (status === 'Все') { status = '' }
            if (types === 'Все') { types = '' }
            const url = 'sights?name=' + name + '&sponsor=' + sponsor +  '&user=' + user +'&city=' + city + '&address=' + address + '&statuses=' + status + '&sightTypes=' + types + '&searchText=' + text + '&pagination=true'; 
            await this.getUrlSights(url);
            this.loader = false;
        },
        async getTypesSights() {
            await axios.get('sight-types')
            .then(response => this.types = response.data.types)
            .catch(error => this.toast.error('Ошибка при загрузке типов: ' + error.message))
        },
        async updateSights() {
            this.loader = true;
            await axios.put('sights/updateSight/' + this.sight.id + '?name=' + this.sight.name + '&sponsor=' + this.sight.sponsor + '&city=' + this.sight.city + '&address=' + this.sight.address + '&description=' + this.sight.description + '&latitude=' + this.sight.latitude + '&longitude=' + this.sight.longitude + '&price=' + this.sight.price + '&vk_post_id=' + this.sight.vk_post_id + '&vk_group_id=' + this.sight.vk_group_id)
            .then(response => this.toast.success('Событие ' + response.data.sight.name + ' изменено!'))
            .catch(error => this.toast.error('Достопримечательность не обновлена: ' + error.message));
            await this.getSightId(this.sight.id);
            this.getUrlSights(this.first_page_url);
            this.loader = false;
        },
        async updateSightsTypes() {
            if (this.new_types) {
                await axios.put('sights/updateTypeSight/' + this.sight.id + '/' + this.new_types)
                .then(axios.get('sights/getTypesId/' + this.new_types).then(response => this.toast.success('Тип изменён на ' + response.data.types.name)).catch(error => this.toast.error('Ошибка при обновлении типа мероприятия:' + error.message)))
                .catch(error => this.toast.error('Ошибка загрузки имени типа: ' + error.message));
                this.new_types = '';
                await this.getUrlSights(this.first_page_url);
            }
        },
        async updateSightStatus() {
            await axios.put('sights/updateStatusSight?sight_id=' + this.sight.id + '&status_id=' + this.sight.statuses[0].id + '&descriptions=' + this.sight.statuses[0].pivot.descriptions)
            .then(async response => {
                await axios.get('getStatusId/' + this.sight.statuses[0].id)
                .then(resp => this.new_status = resp.data.statuses.name)
                .catch(error => this.toast.error('Ошибка в загрузке имени статуса'));
                this.toast.success('Статус сменён на: "' + this.new_status + '"');
            })
            .catch(error => this.toast.error('Статус не изменён ' + error));
            this.getUrlSights(this.first_page_url);
        },
        async getSightId(id) {
            await axios.get('sights/'+ id).then(response => this.sight = response.data).catch(error => this.toast.error(error.message));
        },
        async counterSight() {
            await axios.get('sights?statuses=На%модерации&pagination=true')
            .then(response =>{ this.count_sights = response.data.sights.total; })
        },
        async showSight(id) {
            this.sight = id;
            this.ModalSight = true;
        },
        async showUpdateSight() {
            this.ModalUpdateSight =true;
        },
        async showStatusesSight() {
            this.ModalStatusSight = true;
        },
        async closeSight() {
            this.sight = '';
            this.ModalSight = false;
        },
        async closeUpdateSight() {
            this.ModalUpdateSight =false;
        },
        async closeStatusesSight() {
            this.ModalStatusSight = false;
        },
    },
})