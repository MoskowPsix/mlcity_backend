import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';

const toast = useToastStore();

export const useEventsStore = defineStore('EventsStore', {
    state: () => ({
        events: [],
        event: '',
        new_types: '',
        new_status: '',
        links: [],
        loader: true,
        ModalEvent: false,
        ModalUpdate: false,
        ModalStatuses: false,
    }),
    actions: {
        async getEventId(id) {
            const url = 'http://localhost:8000/api/events/'+ id;
            await axios.get(url).then(response => { this.event = response.data}).catch(error => console.log(error));
        },
        async getEvents() {
            this.loader = true;
            const url = 'http://localhost:8000/api/events?pagination=true';
            await this.getEventUrl(url);
            this.loader = false;
        },
        async getEventUrl(url) {
            await axios.get(url + '&pagination=true').then(response => {
                this.events = response.data.events; 
                this.links = response.data.events.links;
            }).catch(error => console.log(error));
        },
        async getEventSearch(name = '', sponsor = '', date = ['', ''], user_name = '', user_email = '', city = '', address = '', status = '') {
            this.loader = true;
            const url = 'http://localhost:8000/api/events?name=' + name + '&sponsor=' + sponsor + '&date_start=' + date[0] + '&date_end=' + date[1] + '&user_name=' + user_name +'&user_email=' + user_email +'&city=' + city + '&address=' + address + '&statuses=' + status; 
            await this.getEventUrl(url);
            this.loader = false;
        }, 
        async updateEvent() {
            this.loader = true;
            axios.put('http://localhost:8000/api/updateEvent/' + this.event.id + '?name=' + this.event.name + '&sponsor=' + this.event.sponsor + '&city=' + this.event.city + '&address=' + this.event.address + '&description=' + this.event.description + '&latitude=' + this.event.latitude + '&longitude=' + this.event.longitude + '&price=' + this.event.price + '&date_start=' + this.event.date_start + '&date_end=' + this.event.date_end + '&vk_post_id=' + this.event.vk_post_id + '&vk_group_id=' + this.event.vk_group_id)
            .then(response => { 
                toast.success('Событие' + response.data.event.name + ' изменено!');
                
                this.closeUpdate();
            })
            .catch(error => console.log(error));
            this.loader = false;
        },
        async updateEventTypes() {
            await axios.put('http://localhost:8000/api/updateTypeEvent/' + this.event.id + '/' + this.new_types)
            .then(async response => {
                await axios.get('http://localhost:8000/api/getTypesId/' + types_id).then(response => types_id = response.data.types).catch(error => toast.error('Ошибка в методе getTypesId'));
                toast.success('Статус изменён на ' + types_id.name);
            })
            .catch(error => console.log(error));
            this.getEvents;
            console.log(this.links);
            this.new_types = '';
            this.ModalUpdate = false;

        },
        async updateEventStatus() {
            await axios.put('http://localhost:8000/api/updateStatusEvent/' + this.event.id + '/' + this.new_status)
            .then(async response => {
                await axios.get('http://localhost:8000/api/getStatusId/' + this.new_status)
                .then(resp => this.new_status = resp.data.statuses.name)
                .catch(error => toast.error('Ошибка в загрузке имени статуса'));
                toast.success('Статус сменён на ' + this.new_status);
            })
            .catch(error => toast.error('Статус не изменён ' + error));
            this.new_status = ''; 
            this.closeStatuses();
        },
        async showUpdateEvent(id) {
            this.event = id;
            this.ModalEvent = true;
            console.log(id);
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