import axios from 'axios';
import { defineStore } from 'pinia';
import { from } from 'rxjs';



export const useAuthStore = defineStore('useAuth', {
    actions: {
        login(params) {
            return from(axios.post('login', params))
          },
        logout() {
            return axios.post('logout')
        },
        getUserForToken() {
            return from(axios.get('users'))
        },
    },
    state: () => ({

    }),
    getters: {
      
    }
})