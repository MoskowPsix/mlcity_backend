import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';
import { useRoleStore } from './roleStore';


const toast = useToastStore();
const stole_role = useRoleStore();

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
            this.loader = false;
        },

        // Создание юзера
        async createUser (name, email, password, role_id) {
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
            .then(async response => {
                if (response.status === 200) {
                    if (role_id){
                        await axios.put('http://localhost:8000/api/updateRoleUser/' + response.data.user.id + '/' + role_id)
                            .then(async response => {
                                await axios.get('http://localhost:8000/api/getRole/' + response.data.update_role)
                                    .then(resp => toast.success('Пользователю ' + name + ', назначена роль ' +resp.data.role.name))
                                    .catch(error => console.log(error));
                                console.log(response);
                        }).catch(error => console.log(error));
                    }
                    toast.success('Ползователь: ' + name + ' с почтой: ' + email + ' успешно создан!');
                    this.closeModal();
                    console.log(response)   
                    this.loader = false;
                }
            })
            .catch(error => {
                if (error.response.status === 422) {
                    toast.warning(JSON.stringify(error.response.data.message));
                    this.loader = false;
                }
            })
            this.closeModal();
            await this.getPage(this.users.users.first_page_url);
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
            //Меняем инфу об юзере
            if (name != '') {
                await axios.put('http://localhost:8000/api/updateUsers/' + this.user_upd_id.id + '?name=' + name).then(response => toast.success('Обновлено: имя - ' + response.data.user.name )).catch(error => console.log(error));
            }
            if (email != '') {
                await axios.put('http://localhost:8000/api/updateUsers/' + this.user_upd_id.id + '?email=' + email).then(response => toast.success('Обновлено: почта - ' + response.data.user.email)).catch(error => console.log(error));
            }
            //Меняем роль
            if (role_id) {
                await axios.put('http://localhost:8000/api/updateRoleUser/' + this.user_upd_id.id + '/' + role_id)
                .then(async response => {
                    await axios.get('http://localhost:8000/api/getRole/' + response.data.update_role)
                    .then(resp => response.data.update_role = resp.data.role.name);
                    await axios.get('http://localhost:8000/api/users/' + this.user_upd_id.id)
                    .then(resp => response.data.user = resp.data.user.name);
                    toast.success('Пользователь: ' + response.data.user + ', теперь имеет роль ' + response.data.update_role);
                });
            }

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
            this.del_modal_users = await true;
          },
          // Млдальное окно редактирования
        async showModalUpd(id, name, email) {
            this.user_upd_id = {id: id, name: name, email: email};
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
