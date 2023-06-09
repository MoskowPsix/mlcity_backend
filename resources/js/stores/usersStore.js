import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';


const toast = useToastStore();

export const useUsersStore = defineStore('usersStore', {
    actions: {
        // Получить всех юзеров по фильтрам
        async getUsers(name = '', email = '', time = ['', ''], limit = 10) {
            this.loader = true;
            const url = 'http://localhost:8000/api/listUsers?limit=' + limit + '&name=' + name + '&email=' + email + '&createdDateStart=' + time[0] + '&cteatedDateEnd=' + time[1];
            await this.getPage(url);
            if (this.users.users.total === 0) {
                toast.warning('Пользователи не были найдены');
            }
        },

        // Получить страницу с юзерами
        async getPage(url) {
            this.loader = true;
            await axios.get(url)
            .then(response => {(this.users = response.data), this.links = response.data.users.links})
            .catch(error => toast.error('Ошибка:' + error));
            console.log(this.users);
            console.log(this.links);
            this.loader = false;
        },

        // Создание юзера
        async createUser (name, email, password) {
            this.loader = true;
            const errors = [];
            console.log(name + ' ' + email + ' ' + password);
            const res = '';
            await axios.post('http://localhost:8000/api/register/', {
                name: name,
                email: email,
                password: password,
                password_confirmation: password,
            })
            .then(response => {
                if (response.status === 200) {
                    toast.success('Ползователь: ' + name + ' с почтой: ' + email + ' успешно создан!');
                    this.closeModal();
                    this.loader = false;
                }
            })
            .catch(error => {
                if (error.response.status === 422) {
                    toast.warning(JSON.stringify(error.response.data.message));
                    this.loader = false;
                }
            })
            
            if (res.response.status === 200){
                toast.success('Ползователь: ' + name + ' с почтой: ' + email + ' успешно создан!')
            } else if (res.response.status === 422) {
                console.log(res.response.data.errors)
                toast.warning(res.response.data.errors.name[0]);
                toast.warning(res.response.data.errors.email[0]);
                toast.warning(res.response.data.errors.password[0]);
            }
            //console.log(name + ' ' + email + ' ' + password + ' ' + response);
            this.closeModal();
            this.loader = false;
        },
        // Удалить юзера
        async delUsers() {
            this.loader = true;
            const url = 'http://localhost:8000/api/deleteUsers/' + this.user_del.id;
            await axios.delete(url).catch(error => console.log(error));
            await this.getPage(this.users.users.first_page_url);
            toast.success('Пользователь удалён!')
            await this.closeModalDel();
            this.loader = false;
        },
        // Обновить юзера
        async updateUser(name, email, role_id) {
            this.loader = true;
            const url = 'http://localhost:8000/api/updateUsers/' + this.user_upd_id.id + '?name=' + name + '&email=' + email;
            await axios.put(url).then(response => toast.success('Обновлено: имя - ' + response.data.user.name + ', почта - ' + response.data.user.email)).catch(error => console.log(error));
            await this.getPage(this.users.users.first_page_url);
            this.closeModalUpd();
            this.loader = false;
        },

        //Модальное окно добавления
        async showModal() {
            this.show_modal_users = await true;
          },

        async closeModal() {
            this.show_modal_users = await false;
            this.loader = false;
          },
        // Млдальное окно удаления
        async closeModalDel() {
            this.user_del = '';
            this.del_modal_users = await false;
          },
        async showModalDel(id, name, email) {
            this.user_del = {id: id, name: name, email: email};
            console.log(this.user_del);
            this.del_modal_users = await true;
          },
          // Млдальное окно редактирования
        async showModalUpd(id, name, email) {
            this.user_upd_id = {id: id, name: name, email: email};
            console.log(this.user_upd_id);
            this.user_upd = true;
        },
        async closeModalUpd() {
            this.user_upd_id = '';
            this.user_upd = false;
        }

    },
    
    state: () => ({
        users: [],
        loader: false,
        user_id: '',
        show_modal_users: false,
        del_modal_users: false,
        user_del: '',
        user_upd: '',
        user_upd_id: '',
        links: [],
    

        
    }),
    getters: {
        
    }
})
