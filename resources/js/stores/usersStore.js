import { defineStore } from 'pinia'
import axios from 'axios';

export const useUsersStore = defineStore('usersStore', {
    actions: {

        async getUsers(name = '', email = '', time = ['', ''], limit = 10) {
            this.loader = true;
            const url = 'http://localhost:8000/api/listUsers?limit=' + limit + '&name=' + name + '&email=' + email + '&createdDateStart=' + time[0] + '&cteatedDateEnd=' + time[1];
            const res = await fetch(url);
            const data = await res.json();
            this.users = data;
            console.log(data)
            this.loader = false;
            if (data.users.data === 0) {
                this.users = "";
            }
        },

        async getPage(url) {
            this.loader = true;
            const res = await fetch(url);
            const data = await res.json();
            this.users = '';
            this.users = data;
            this.loader = false;
        },
        async createUser (name, email, password) {
            this.loader = true;
            console.log(name + ' ' + email + ' ' + password);
            const response = await axios.post('http://localhost:8000/api/register', {
                name: name.value,
                email: email,
                password: password,
                password: password,
            });
            console.log(name + ' ' + email + ' ' + password + ' ' + response);
            this.loader = false;
        },
        async delUsers(id) {
            this.loader = true;
            const res =await fetch('http://localhost:8000/api/deleteUsers/' + id);
            console.log(id);
            await this.getPage(this.users.users.first_page_url);
            this.loader = false;
        },
        async showModal() {
            this.showModalUsers = await true;
            console.log(this.showModalUsers);
          },
        async closeModal() {
            this.showModalUsers = await false;
            console.log(this.showModalUsers);
            
          }

    },
    
    state: () => ({
        users: '',
        loader: false,
        user_id: '',
        showModalUsers: false,

        
    }),
    getters: {
        
    }
})
