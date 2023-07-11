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
        ModalHistoryStatus: false,
        ModalLikedFavorites: {
            status: false,
            window: '',
            result: [],
            loader: true,
        },
        sight_search: {
            name: '',
            sponsor: '',
            author: '',
            city: '',
            address: '',
            status: 'На модерации',
            types: 'Все',
            text: '',
            last: true,
        },
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
        async getSightsSearch() {
            this.loader = true;
            var status = '';
            var types = '';
            var last = '';
            if (this.sight_search.last === false) { last = ''} else { last = '&statusLast=' + this.sight_search.last;}
            if (this.sight_search.status === 'Все') { status = '' } else { status = '&statuses=' + this.sight_search.status }
            if (this.sight_search.types === 'Все') { types = '' } else  { types = '&sightTypes=' + this.sight_search.types }
            const url = 
                'sights?name=' + this.sight_search.name + 
                '&sponsor=' + this.sight_search.sponsor +  
                '&user=' + this.sight_search.author + 
                '&city=' + this.sight_search.city + 
                '&address=' + this.sight_search.address + 
                status + 
                types + 
                last + 
                '&searchText=' + this.sight_search.text + 
                '&pagination=true'; 
            await this.getUrlSights(url);
            this.loader = false;
        },
        async clearSightsSearch() {
            this.sight_search.name = '';
            this.sight_search.sponsor = '';
            this.sight_search.author = '';
            this.sight_search.city = '';
            this.sight_search.address = '';
            this.sight_search.status = 'Все';
            this.sight_search.types = 'Все';
            this.sight_search.text = '';
            this.sight_search.last = true;
            await this.getSightsSearch();
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
            await this.getUrlSights(this.first_page_url);
            this.loader = false;
        },
        async updateSightsTypes() {
            if (this.new_types) {
                await axios.put('sights/updateTypeSight/' + this.sight.id + '/' + this.new_types)
                .then(axios.get('sights/getTypesId/' + this.new_types).then(response => this.toast.success('Тип изменён на ' + response.data.types.name)).catch(error => this.toast.error('Ошибка при обновлении типа мероприятия:' + error.message)))
                .catch(error => this.toast.error('Ошибка загрузки имени типа: ' + error.message));
                this.new_types = '';
                await this.getUrlSights(this.first_page_url);
                await this.getSightId(this.sight.id);
            }
        },
        async updateSightStatus() {
            await axios.post('sights/addStatusSight?sight_id=' + this.sight.id + '&status_id=' + this.sight.statuses[0].id + '&descriptions=' + this.sight.statuses[0].pivot.descriptions)
            .then(async response => {
                await axios.get('getStatusId/' + this.sight.statuses[0].id)
                .then(resp => this.new_status = resp.data.statuses.name)
                .catch(error => this.toast.error('Ошибка в загрузке имени статуса'));
                this.toast.success('Статус сменён на: "' + this.new_status + '"');
                await this.getUrlSights(this.first_page_url);
                await this.getSightId(this.sight.id);
            })
            .catch(error => this.toast.error('Статус не изменён ' + error));
            this.getUrlSights(this.first_page_url);
        },
        async getSightId(id) {
            await axios.get('sights/'+ id).then(response => this.sight = response.data).catch(error => this.toast.error(error.message));
        },
        async counterSight() {
            await axios.get('sights?statusLast=true&statuses=На модерации&pagination=true&limit=1')
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
        async showModalHistory() {
            this.ModalHistoryStatus = true;
        },
        async showModalLiked() {
            this.ModalLikedFavorites.loader = true;
            this.ModalLikedFavorites.status = true;
            this.ModalLikedFavorites.window = 'liked';
            await axios.get('sights/' + this.sight.id + '/liked-users')
            .then(response => {
                this.ModalLikedFavorites.result = response.data.result;
            })
            .catch(error => this.toast.error('При загрузке лайков произошла ошибка: ' + error.message))
            this.ModalLikedFavorites.loader =false;
        },
        async showModalFavorites() {
            this.ModalLikedFavorites.loader =true;
            this.ModalLikedFavorites.status = true;
            this.ModalLikedFavorites.window = 'favorites';
            await axios.get('sights/' + this.sight.id + '/favorites-users')
            .then(response => { 
                this.ModalLikedFavorites.result = response.data.result;

            })
            .catch(error => this.toast.error('При загрузке фаворитов произошла ошибка: ' + error.message))
            this.ModalLikedFavorites.loader = false;
        },
        async getFavoritesLikedPage(url) {
            this.ModalLikedFavorites.loader =true;
            await axios.get(url)
            .then(response => this.ModalLikedFavorites.result = response.data.result)
            .catch(error => this.toast.error('При загрузке фаворитов произошла ошибка: ' + error.message))
            this.ModalLikedFavorites.loader = false;
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
        async closeModalHistory() {
            this.ModalHistoryStatus = false;
        },
        async closeModalLikedFavorites() {
            this.ModalLikedFavorites.status = false;
            this.ModalLikedFavorites.window = '';
            this.ModalLikedFavorites.result = '';
        },
    },
})