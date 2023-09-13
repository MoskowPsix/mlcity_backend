import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';


export const useEventsStore = defineStore('EventsStore', {
    state: () => ({
        toast: useToastStore(),
        events: [],
        event: '',
        new_types: '',
        new_status: '',
        links: [],
        first_page_url: '',
        loader: true,
        ModalEvent: false,
        ModalUpdate: false,
        ModalStatuses: false,
        ModalHistoryStatus: false,
        ModalLikedFavorites: {
            status: false,
            window: '',
            result: [],
            loader: true,
        },
        Map: true,
        ModalComents: false,
        count_moder: '',
        status: [],
        new_status: '',
        status_id: '',
        types: [],
        event_search: {
            name: '',
            sponsor: '',
            date: '',
            end: '',
            author: '',
            types: 'Все',
            city: '',
            address: '',
            status: 'На модерации',
            last: 'true',
        },
    }),
    actions: {
        async getEventId(id) {
            const url = 'events/'+ id;
            await axios.get(url).then(response => { this.event = response.data}).catch(error => console.log(error));
        },
        async getEvents() {
            this.loader = true;
            const url = 'events?pagination=true';
            await this.getEventUrl(url);
            this.loader = false;
        },
        async getEventUrl(url) {
            await axios.get(url + '&pagination=true').then(response => {
                this.events = response.data.events; 
                this.links = response.data.events.links;
                this.first_page_url = response.data.events.first_page_url;
                if (response.data.events.data.length === 0) {this.toast.warning('События не найдены!')}
            }).catch(error => this.toast.error(error.message));
            
        },
        async getEventSearch() {
            this.loader = true;
            var status = '';
            var types = '';
            var last = '';
            if (this.event_search.last === false) { last = ''} else { last = '&statusLast=' + this.event_search.last;}
            if (this.event_search.status === 'Все') { status = '' } else { status = '&statuses=' + this.event_search.status }
            if (this.event_search.types === 'Все') { types = '' } else  { types = '&eventTypes=' + this.event_search.types }

            const url = 
                'events?name=' + this.event_search.name + 
                '&sponsor=' + this.event_search.sponsor + 
                '&dateStart=' + this.event_search.date.replace('~', '&dateEnd=') + 
                '&user=' + this.event_search.author + 
                //'&city=' + this.event_search.city + 
                '&address=' + this.event_search.address + 
                types  + 
                status + 
                last;

            await this.getEventUrl(url);
            this.loader = false;
        }, 
        async clearSearch() {
            this.event_search.name = '';
            this.event_search.sponsor = '';
            this.event_search.date = '';
            this.event_search.end = '';
            this.event_search.author = '';
            this.event_search.types = 'Все';
            this.event_search.status = 'Все';
            this.event_search.city = '';
            this.event_search.address = '';
            this.event_search.last = 'true';
            await this.getEventSearch();
        },
        async updateEvent() {
            this.loader = true;
            if (this.event.price === null) {this.event.price = ''}
            await axios.put(
                'updateEvent/' + this.event.id + 
                '?name=' + this.event.name + 
                '&sponsor=' + this.event.sponsor + 
                //'&city=' + this.event.city + 
                '&address=' + this.event.address + 
                '&description=' + this.event.description + 
                '&latitude=' + this.event.latitude + 
                '&longitude=' + this.event.longitude + 
                '&price=' + this.event.price + 
                '&date_start=' + this.event.date_start + 
                '&date_end=' + this.event.date_end + 
                '&vk_post_id=' + this.event.vk_post_id + 
                '&vk_group_id=' + this.event.vk_group_id)
            .then(response => { 
                this.toast.success('Событие ' + response.data.event.name + ' изменено!');
            })
            .catch(error => console.log(error));
            if (this.new_types !== '') {
                await this.updateEventTypes();
            }
            await this.getEventId(this.event.id);
            await this.getEventUrl(this.first_page_url);
            await this.closeUpdate();
            this.loader = false;
        },
        async updateEventTypes() {
            await axios.put('events/updateTypeEvent/' + this.event.id + '/' + this.new_types)
            .then(axios.get('events/getTypesId/' + this.new_types).then(response => this.toast.success('Тип изменён на ' + response.data.types.name)).catch(error => this.toast.error('Ошибка при обновлении типа мероприятия!')))
            .catch(error => console.log(error));
            await this.getEventId(this.event.id)
            await this.getEventUrl(this.first_page_url);
            this.new_types = '';
            this.closeUpdate();

        },
        async updateEventStatus() {
            //this.ModalEvent = false;
            this.loader = true;
            await axios.post('events/addStatusEvent?event_id=' + this.event.id + '&status_id=' + this.event.statuses[0].id + '&descriptions=' + this.event.statuses[0].pivot.descriptions)
            .then(async response => {
                await axios.get('getStatusId/' + this.event.statuses[0].id)
                .then(resp => {
                    this.new_status = resp.data.statuses.name;
                })
                .catch(error => this.toast.error('Ошибка в загрузке имени статуса'));
                this.toast.success('Статус сменён на: "' + this.new_status + '"');
                if (response.data.vk_post_id && this.new_status === 'Опубликовано' ) {
                    this.toast.info('ID поста: ' + response.data.vk_post_id + ', ID источника: ' + response.data.vk_post_id);
                }
            })
            .catch(error => {
                this.toast.error('Статус не изменён ' + error);
            });
            await this.getEventId(this.event.id);
            await this.getEvents(this.first_page_url);
            //this.showUpdateEvent(this.event);
            this.closeStatuses();
            this.loader = false;
        },
        async updateCoord(lat, long) {
            this.event.latitude = lat;
            this.event.longitude = long;
        },
        async getStatus() {
            await axios.get('statuses')
            .then(response => this.status = response.data.statuses)
            .catch(error => this.toast.error('Проблема с загрузкой статусов ' + error));
        },
        async getTypes() {
            await axios.get('event-types').then(response => this.types = response.data.types).catch(error => this.toast.error('Ошибка в методе getTypes'));
        },
        async getTypesId() {
            await axios.get('events/getTypesId').then(response => this.types = response.data.types).catch(error => this.toast.error('Ошибка в методе getTypesId'));
        },
        async counterEvent() {
            await axios.get('events?statusLast=true&statuses=На модерации&pagination=true&limit=1')
            .then(response =>{ this.count_moder = response.data.events.total; })
        },
        async showUpdateEvent(event) {
            this.event = event;
            console.log(event)
            this.ModalEvent = true;
        }, 
        async showUpdate() {
            this.ModalUpdate = true;
        },
        async showStatuses() {
            this.ModalStatuses = true;
        },
        async showModalHistory() {
            this.ModalHistoryStatus = true;
        },
        async showModalComents() {
            this.ModalComents = true;
        },
        async showModalLiked() {
            this.ModalLikedFavorites.loader =true;
            this.ModalLikedFavorites.status = true;
            this.ModalLikedFavorites.window = 'liked';
            await axios.get('events/' + this.event.id + '/liked-users')
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
            await axios.get('events/' + this.event.id + '/favorites-users')
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
        async showMap() {
            this.Map = true;
        },
        async closeUpdateEvent() {
            this.event = '';
            this.ModalEvent = false;
        },
        async closeUpdate() {
            this.ModalUpdate = false;
        },
        async closeStatuses() {
            this.ModalStatuses = false;
        },
        async closeModalHistory() {
            this.ModalHistoryStatus = false;
        },
        async closeModalComents() {
            this.ModalComents = false;
        },
        async closeModalLikedFavorites() {
            this.ModalLikedFavorites.status = false;
            this.ModalLikedFavorites.window = '';
            this.ModalLikedFavorites.result = '';
        },
        async closeMap() {
            this.Map = false;
        },

    }
    
})