import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';

const config = {
    headers: { Authorization: `Bearer ${localStorage.token}` }
};
const toast = useToastStore();

export const useStatusStore = defineStore('StatusStore', {
    state: () => ({
        status: [],
        new_status: '',
        status_id: '',
    }),
    actions: {
        getStatus() {
            axios.get('http://localhost:8000/api/statuses', config).then(response => this.status = response.data.statuses).catch(error => toast.error('Проблема с загрузкой статусов ' + error));
        }
    }
})