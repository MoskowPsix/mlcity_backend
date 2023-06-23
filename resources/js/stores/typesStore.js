import { defineStore } from 'pinia';
import axios from 'axios';
import { useToastStore } from './toastStore';


const toast = useToastStore();
const config = {
    headers: { Authorization: `Bearer ${localStorage.token}` }
};

export const useTypesStore = defineStore('typesStore', {
    state: () => ({
        types: [],
        config: {
            headers: { Authorization: `Bearer ${localStorage.token}` }
        },
        bodyParameters: {
            key: "value"
        },
        toast: useToastStore(),
    }),
    actions: {
        async getTypes() {
            await axios.get('http://localhost:8000/api/event-types', this.bodyParameters, this.config).then(response => this.types = response.data.types).catch(error => this.toast.error('Ошибка в методе getTypes'));
        },
        getTypesId() {
            axios.get('http://localhost:8000/api/getTypesId', this.bodyParameters, this.config).then(response => this.types = response.data.types).catch(error => this.toast.error('Ошибка в методе getTypesId'));
        }
    }
})