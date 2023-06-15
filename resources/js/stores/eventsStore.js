import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';

const toast = useToastStore();

export const useEventsStore = defineStore('EventsStore', {
    state: () => ({
        events: [],
        event: '',
        links: [],
        loader: true,
        ModalUpdate: false,
    }),
    actions: {
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
        async showUpdateEvent(id) {
            this.event = id;
            this.ModalUpdate = true;
            console.log(id);
        }, 
        async closeUpdateEvent() {
            this.event = '';
            this.ModalUpdate = false;
        },
    }
    
})