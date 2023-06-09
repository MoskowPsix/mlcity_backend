import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';

const toast = useToastStore();

export const useEventsStore = defineStore('EventsStore', {
    state: () => ({
        events: [],
    }),
    actions: {
        async getEvents() {
            const url = 'http://localhost:8000/api/events?';
            await axios(url).then(response => this.events = response).catch(error => console.log(error));

        }
    }
    
})