import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';

const toast = useToastStore();

export const useRoleStore = defineStore('RoleStore', {
    state: () => ({
        role: [],
    }),
    actions: {
        async getRole() {
            await axios.get('http://localhost:8000/api/getRole').then(response => this.role = response).catch(error => toast.error(error));
            console.log(this.role);
        }
    }
    
})