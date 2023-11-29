import axios from 'axios';
import { defineStore } from 'pinia';
import { from } from 'rxjs';



export const useUsersStore = defineStore('useUsers', {
    actions: {
        getUsers(params)  {
            return from(axios.get('admin/users/', {params}))
        },
        getRoles() {
            return from(axios.get('roles'))
        },
        updateUser(user) {
            const id = user.id
            const params = {
                name: user.name,
                email: user.email,
            }
            return from(axios.put('admin/users/'+ id + '/', params))
        }
    },
})