import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';


export const useEventsStore = defineStore('EventsStore', {
    state: () => ({
        config: {
            headers: { Authorization: `Bearer ${localStorage.token}` }
        },
        bodyParameters: {
            key: "value"
         },
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
    }),
    actions: {
        async getEventId(id) {
            const url = 'http://localhost:8000/api/events/'+ id;
            await axios.get(url, this.bodyParameters, this.config).then(response => { this.event = response.data}).catch(error => console.log(error));
        },
        async getEvents() {
            this.loader = true;
            const url = 'http://localhost:8000/api/events?pagination=true';
            await this.getEventUrl(url, this.bodyParameters, this.config);
            this.loader = false;
        },
        async getEventUrl(url) {
            await axios.get(url + '&pagination=true', this.bodyParameters, this.config).then(response => {
                this.events = response.data.events; 
                this.links = response.data.events.links;
            }).catch(error => this.toast.error(error.message));
            
        },
        async getEventSearch(name = '', sponsor = '', date = ['', ''], user_name = '', user_email = '', city = '', address = '', status = '') {
            this.loader = true;
            if (status === 'Все') { status = '' }
            const url = 'http://localhost:8000/api/events?name=' + name + '&sponsor=' + sponsor + '&dateStart=' + date.replace('~', '&dateEnd=') + '&user_name=' + user_name +'&user_email=' + user_email +'&city=' + city + '&address=' + address + '&statuses=' + status; 
            await this.getEventUrl(url);
            this.loader = false;
        }, 
        async updateEvent() {
            this.loader = true;
            await axios.put('http://localhost:8000/api/updateEvent/' + this.event.id + '?name=' + this.event.name + '&sponsor=' + this.event.sponsor + '&city=' + this.event.city + '&address=' + this.event.address + '&description=' + this.event.description + '&latitude=' + this.event.latitude + '&longitude=' + this.event.longitude + '&price=' + this.event.price + '&date_start=' + this.event.date_start + '&date_end=' + this.event.date_end + '&vk_post_id=' + this.event.vk_post_id + '&vk_group_id=' + this.event.vk_group_id, this.bodyParameters, this.config)
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
            await axios.put('http://localhost:8000/api/updateTypeEvent/' + this.event.id + '/' + this.new_types, this.bodyParameters, this.config)
            .then(axios.get('http://localhost:8000/api/getTypesId/' + this.new_types, this.bodyParameters, this.config).then(response => this.toast.success('Тип изменён на ' + response.data.types.name)).catch(error => this.toast.error('Ошибка при обновлении типа мероприятия!')))
            .catch(error => console.log(error));
            await this.getEventId(this.event.id)
            await this.getEvents;
            this.new_types = '';
            this.closeUpdate();

        },
        async updateEventStatus() {
            await axios.put('http://localhost:8000/api/updateStatusEvent?event_id=' + this.event.id + '&status_id=' + this.event.statuses[0].id + '&descriptions=' + this.event.statuses[0].pivot.descriptions, this.bodyParameters, this.config)
            .then(async response => {
                await axios.get('http://localhost:8000/api/getStatusId/' + this.event.statuses[0].id, this.bodyParameters, this.config)
                .then(resp => this.new_status = resp.data.statuses.name)
                .catch(error => this.toast.error('Ошибка в загрузке имени статуса'));
                this.toast.success('Статус сменён на ' + this.new_status);
            })
            .catch(error => this.toast.error('Статус не изменён ' + error));
            await this.getEvents;
            await this.getEventId(this.event.id)
            this.closeStatuses();
        },
        async counterEvent() {
            await axios.get('http://localhost:8000/api/events?statuses=На%модерации&pagination=true', this.bodyParameters, this.config)
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