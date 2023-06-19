import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';
import { useEventsStore } from './eventsStore';


const event_store = useEventsStore();
const toast = useToastStore();

export const useStatusStore = defineStore('StatusStore', {
    state: () => ({
        status: [],
        new_status: '',
        status_id: '',
    }),
    actions: {
        getStatus() {
            axios.get('http://localhost:8000/api/statuses').then(response => this.status = response.data.statuses).catch(error => toast.error('Проблема с загрузкой статусов ' + error));
        }
    }
})