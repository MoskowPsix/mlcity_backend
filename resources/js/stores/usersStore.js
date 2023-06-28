import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';


const config = {
    headers: { Authorization: `Bearer ${localStorage.token}` }
};

export const useUsersStore = defineStore('usersStore', {
    actions: {
        // Получить всех юзеров по фильтрам
        async getUsers(name = '', email = '', time = '', limit = 10) {
            this.loader = true;
            await this.getPage('http://localhost:8000/api/listUsers?limit=' + limit + '&name=' + name + '&email=' + email + '&createdDateStart=' + time.replace('~', '&cteatedDateEnd='), config);
            if (this.users.users.total === 0) {
                this.toast.warning('Пользователи не были найдены');
            }
        },
        // Имя по ИД юзера
        getUserId(id) {
            axios.get('http://localhost:8000/api/users/' + id).then(response => id = response.data.user.name).catch(error => this.toast.error('Метод загрузки пользователя по ID не работает!'), this.bodyParameters, config);
            console.log(id);
            return id;
        },

        // Получить страницу с юзерами
        async getPage(url) {
            this.loader = true;
            await axios.get(url, config)
            .then(response => {(this.users = response.data), this.links = response.data.users.links})
            .catch(error => this.toast.error('Ошибка, страница не получена!'));
            this.loader = false;
        },

        // Создание юзера
        async createUser (name, email, password, role_id) {
            this.loader = true;
            console.log(name + ' ' + email + ' ' + password);
            await axios.post('http://localhost:8000/api/register/', {
                name: name,
                email: email,
                password: password,
                password_confirmation: password,
            }, this.bodyParameters, config)
            .then(async response => {
                if (response.status === 200) {
                    if (role_id){
                        await axios.put('http://localhost:8000/api/updateRoleUser/' + response.data.user.id + '/' + role_id, this.bodyParameters, config)
                            .then(async response => {
                                await axios.get('http://localhost:8000/api/getRole/' + response.data.update_role, this.bodyParameters, config)
                                    .then(resp => this.toast.success('Пользователю ' + name + ', назначена роль ' +resp.data.role.name))
                                    .catch(error => console.log(error));
                        }).catch(error => this.toast.warning('Ошибка при создании пользователя!'));
                    }
                    this.toast.success('Ползователь: ' + name + ' с почтой: ' + email + ' успешно создан!');
                    this.closeModal();
                    console.log(response)   
                    this.loader = false;
                }
            })
            .catch(error => {
                if (error.response.status === 422) {
                    this.toast.warning(JSON.stringify(error.response.data.message));
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
            await axios.delete(url, config).then(this.toast.success('Пользователь удалён!')).catch(error => this.toast.warning(error.message));
            await this.getPage(this.users.users.first_page_url);
            this.closeModalDel();
            this.loader = false;
        },
        // Обновить юзера
        async updateUser(name, email, role_id) {
            this.loader = true;
            //Меняем инфу об юзере
            if (name) {
                await axios.put('http://localhost:8000/api/updateUsers/' + this.user_upd_id.id + '?name=' + name, config)
                .then(response => this.toast.success('Обновлено: имя - ' + response.data.user.name ))
                .catch(error => this.toast.error('Name update: ' + error.message));
            }
            if (email) {
                await axios.put('http://localhost:8000/api/updateUsers/' + this.user_upd_id.id + '?email=' + email, config)
                .then(response => this.toast.success('Обновлено: почта - ' + response.data.user.email))
                .catch(error => this.toast.error('Email update: ' + error.message));
            }
            //Меняем роль
            if (role_id) {
                await axios.put('http://localhost:8000/api/updateRoleUser/' + this.user_upd_id.id + '/' + role_id, config)
                .then(async response => {
                    await axios.get('http://localhost:8000/api/getRole/' + response.data.update_role, config)
                    .then(resp => response.data.update_role = resp.data.role.name)
                    .catch(error => this.toast.warning('Ошибка получения получения имени роли!' + error.message))
                    await axios.get('http://localhost:8000/api/users/' + this.user_upd_id.id, config)
                    .then(resp => response.data.user = resp.data.user.name)
                    .catch(error => this.toast.warning('Ошибка получения имени пользователя! ' + error.message))
                    this.toast.success('Пользователь: ' + response.data.user + ', теперь имеет роль ' + response.data.update_role);
                }).catch(error => this.toast.error('Role update: ' + error.message));
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
        types: [],
        bodyParameters: {
            key: "value"
        },
        toast: useToastStore(),
    }),
    getters: {
        
    }
})
