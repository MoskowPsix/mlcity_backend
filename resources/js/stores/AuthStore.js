import axios from 'axios';
import { defineStore } from 'pinia';


export const useAuthStore = defineStore('useAuth', {
    actions: {
        login(params) {
            return axios.post('login', params)
          },
        logout() {
            return axios.post('logout')
        },
        getUserForToken() {
            return axios.get('users')
        },
    },
    state: () => ({

    }),
    getters: {
      
    }
})