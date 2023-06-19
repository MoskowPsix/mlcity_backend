import { defineStore } from 'pinia';
import axios from 'axios';
import { useToastStore } from './toastStore';


const toast = useToastStore();

export const useTypesStore = defineStore('typesStore', {
    state: () => ({
        types: [],
    }),
    actions: {
        async getTypes() {
            await axios.get('http://localhost:8000/api/event-types').then(response => this.types = response.data.types).catch(error => toast.error('Ошибка в методе getTypes'));
        },
        getTypesId() {
            axios.get('http://localhost:8000/api/getTypesId').then(response => this.types = response.data.types).catch(error => toast.error('Ошибка в методе getTypesId'));
        }
    }
})