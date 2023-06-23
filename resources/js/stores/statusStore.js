import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';


export const useStatusStore = defineStore('StatusStore', {
    state: () => ({
        config: {
            headers: { Authorization: `Bearer ${localStorage.token}` }
        },
        bodyParameters: {
            key: "value"
        },
        toast: useToastStore(),
        status: [],
        new_status: '',
        status_id: '',
    }),
    actions: {
        getStatus() {
            axios.get('http://localhost:8000/api/statuses', this.bodyParameters, this.config).then(response => this.status = response.data.statuses).catch(error => this.toast.error('Проблема с загрузкой статусов ' + error));
        }
    }
})