import axios from 'axios';
import { defineStore } from 'pinia';
import { from } from 'rxjs';



export const useUsersStore = defineStore('useUsers', {
    actions: {
        getUsers(params)  {
            return from(axios.get('admin/users/', {params}))
        },
        updateUser(user) {
            const id = user.id
            const params = {
                name: user.name,
                email: user.email,
            }
            return from(axios.put('admin/users/'+ id + '/', params))
        },
        deleteUser(id) {
            return from(axios.delete(`admin/users/${id}`))
        },
        getRoles() {
            return from(axios.get('roles'))
        },
        updateRoleUser(user_id, role_id) {
            return from(axios.put(`users/role/${user_id}/${role_id}`))
        },
        deleteRoleUser(user_id, role_id) {
            return from(axios.delete(`users/role/${user_id}/${role_id}`))
        }
    },
})