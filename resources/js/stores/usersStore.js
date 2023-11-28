import axios from 'axios';
import { defineStore } from 'pinia';
import { ajax } from 'rxjs/ajax'
import { from } from 'rxjs';



export const useUsersStore = defineStore('useUsers', {
    actions: {
        getUsers(params)  {
            return from(axios.get('admin/users/', {params}))
          },
    },
})