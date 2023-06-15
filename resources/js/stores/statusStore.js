import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';


const toast = useToastStore();

export const useStatusStore = defineStore('StatusStore', {
    state: () => ({
        status: []
    }),
    actions: {
        getStatus() {
            axios.get('http://localhost:8000/api/statuses').then(response => this.status = response.data.statuses).catch(error => toast.error('Проблема с загрузкой статусов ' + error));
        }
    }
})