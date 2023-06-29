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
        loader: true,
        ModalEvent: false,
        ModalUpdate: false,
        ModalStatuses: false,
        count_moder: '',
        status: [],
        new_status: '',
        status_id: '',
        types: [],
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
            }).catch(error => this.toast.error(error.message));
            
        },
        async getEventSearch(name = '', sponsor = '', date = ['', ''], user = '', city = '', address = '', status = '', types = '') {
            this.loader = true;
            if (status === 'Все') { status = '' }
            if (types === 'Все') { types = '' }
            const url = 'events?name=' + name + '&sponsor=' + sponsor + '&dateStart=' + date.replace('~', '&dateEnd=') + '&user=' + user + '&city=' + city + '&address=' + address + '&eventTypes=' + types + '&statuses=' + status ; 
            await this.getEventUrl(url);
            this.loader = false;
        }, 
        async updateEvent() {
            this.loader = true;
            await axios.put('updateEvent/' + this.event.id + '?name=' + this.event.name + '&sponsor=' + this.event.sponsor + '&city=' + this.event.city + '&address=' + this.event.address + '&description=' + this.event.description + '&latitude=' + this.event.latitude + '&longitude=' + this.event.longitude + '&price=' + this.event.price + '&date_start=' + this.event.date_start + '&date_end=' + this.event.date_end + '&vk_post_id=' + this.event.vk_post_id + '&vk_group_id=' + this.event.vk_group_id)
            .then(response => { 
                this.toast.success('Событие ' + response.data.event.name + ' изменено!');
            })
            .catch(error => console.log(error));
            if (this.new_types !== '') {
                await this.updateEventTypes()
            }
            await this.getEventId(this.event.id)
            this.loader = false;
        },
        async updateEventTypes() {
            await axios.put('events/updateTypeEvent/' + this.event.id + '/' + this.new_types)
            .then(axios.get('events/getTypesId/' + this.new_types).then(response => this.toast.success('Тип изменён на ' + response.data.types.name)).catch(error => this.toast.error('Ошибка при обновлении типа мероприятия!')))
            .catch(error => console.log(error));
            await this.getEventId(this.event.id)
            await this.getEvents;
            this.new_types = '';
            this.closeUpdate();

        },
        async updateEventStatus() {
            await axios.put('events/updateStatusEvent?event_id=' + this.event.id + '&status_id=' + this.event.statuses[0].id + '&descriptions=' + this.event.statuses[0].pivot.descriptions)
            .then(async response => {
                await axios.get('getStatusId/' + this.event.statuses[0].id)
                .then(resp => this.new_status = resp.data.statuses.name)
                .catch(error => this.toast.error('Ошибка в загрузке имени статуса'));
                this.toast.success('Статус сменён на: "' + this.new_status + '"');
            })
            .catch(error => this.toast.error('Статус не изменён ' + error));
            await this.getEvents;
            await this.getEventId(this.event.id)
            this.closeStatuses();
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
            await axios.get('events?statuses=На%модерации&pagination=true')
            .then(response =>{ this.count_moder = response.data.events.total; })
        },
        async showUpdateEvent(id) {
            this.event = id;
            this.ModalEvent = true;
        }, 
        async showUpdate() {
            this.ModalUpdate = true;
        },
        async showStatuses() {
            this.ModalStatuses = true;
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
    }
    
})