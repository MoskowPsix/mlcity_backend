import axios from 'axios';
import { defineStore } from 'pinia';


export const useUsersStore = defineStore('useUsers', {
    actions: {
        getUsers(params) {
            console.log(params)
            return axios.get('admin/users/', {params})
          },
    },
    state: () => ({

    }),
})