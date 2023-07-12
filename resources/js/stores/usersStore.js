import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';


export const useUsersStore = defineStore('usersStore', {
    actions: {
        // Получить всех юзеров по фильтрам
        async getUsers(limit = 10) {
            this.loader = true;
            var city = '';
            var region = '';
            var name = '';
            var email = '';
            if (this.user_search.city) { city = '&city=' + this.user_search.city }
            if (this.user_search.region) { region = '&region=' + this.user_search.region}
            if (this.user_search.name) { name = '&name=' + this.user_search.name }
            if (this.user_search.city) { email = '&email=' + this.user_search.email}
            await this.getPage(
                'listUsers?limit=' + limit + 
                name + 
                email + 
                '&createdDateStart=' + this.user_search.time.replace('~', '&cteatedDateEnd=') + 
                city +
                region
            );
            if (this.users.users.total === 0) {
                this.toast.warning('Пользователи не были найдены');
            }
        },
        async clearSearch() {
            this.user_search.name = '';
            this.user_search.email = '';
            this.user_search.city = '';
            this.user_search.region = '';
            this.user_search.time = '';
            await this.getUsers();
        },
        // Имя по ИД юзера
        getUserId(id) {
            axios.get('users/' + id).then(response => id = response.data.user.name).catch(error => this.toast.error('Метод загрузки пользователя по ID не работает!'));
            return id;
        },

        // Получить страницу с юзерами
        async getPage(url) {
            this.loader = true;
            await axios.get(url)
            .then(response => {(this.users = response.data); this.links = response.data.users.links})
            .catch(error => this.toast.error('Ошибка, страница не получена!'));
            this.loader = false;
        },

        // Создание юзера
        async createUser (name, email, password, role_id) {
            this.loader = true;
            await axios.post('register/', {
                name: name,
                email: email,
                password: password,
                password_confirmation: password,
            })
            .then(async response => {
                if (response.status === 200) {
                    if (role_id){
                        await axios.put('updateRoleUser/' + response.data.user.id + '/' + role_id)
                            .then(async response => {
                                await axios.get('getRole/' + response.data.update_role)
                                    .then(resp => this.toast.success('Пользователю ' + name + ', назначена роль ' +resp.data.role.name))
                                    .catch(error => console.log(error));
                        }).catch(error => this.toast.warning('Ошибка при создании пользователя!'));
                    }
                    this.toast.success('Ползователь: ' + name + ' с почтой: ' + email + ' успешно создан!');
                    this.closeModal();   
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
            const url = 'deleteUsers/' + this.user_del.id;
            await axios.delete(url).then(this.toast.success('Пользователь удалён!')).catch(error => this.toast.warning(error.message));
            await this.getPage(this.users.users.first_page_url);
            this.closeModalDel();
            this.loader = false;
        },
        // Обновить юзера
        async updateUser() {
            this.loader = true;
            console.log(this.user_upd_id);
            //Меняем инфу об юзере
            await axios.put(
                'updateUsers/' + this.user_upd_id.id + 
                '?name=' + this.user_upd_id.name + 
                '&email=' + this.user_upd_id.email + 
                '&city=' + this.user_upd_id.city + 
                '&region=' + this.user_upd_id.region
            )
            .then(
                response => this.toast.success(
                    'Обновлено: имя - ' + response.data.user.name + 
                    ', почта - '+ response.data.user.email +
                    ', город - ' + response.data.user.city +
                    ', регион - ' + response.data.user.region
                )
            )
            .catch(error => this.toast.error('Name update: ' + error.message));

            //Меняем роль
            await axios.put('updateRoleUser/' + this.user_upd_id.id + '/' + this.user_upd_id.roles[0].id)
            .then(async response => {
                await axios.get('getRole/' + response.data.update_role)
                .then(resp => response.data.update_role = resp.data.role.name)
                .catch(error => this.toast.warning('Ошибка получения получения имени роли!' + error.message))
                await axios.get('users/' + this.user_upd_id.id)
                .then(resp => response.data.user = resp.data.user.name)
                .catch(error => this.toast.warning('Ошибка получения имени пользователя! ' + error.message))
                this.toast.success('Пользователь: ' + response.data.user + ', роль: ' + response.data.update_role);
            }).catch(error => this.toast.error('Ошибка при обновлении роли пользователя: ' + error.message));
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
        async showModalUpd(user) {
            this.user_upd_id = user;
            this.user_upd = true;
        },
        async closeModalUpd() {
            this.user_upd_id = '';
            this.user_upd = false;
        },
        async showModalDetailed(id) {
            this.detailed_modal_users = true;
            this.user_id = id;
            await this.showLikeEvent(id);
        },
        async closeModalDetailed(){
            this.detailed_modal_users = false;
            this.user_id = '';
        },
        async showLikeEvent() {
            this.menu_user_loader = true;
            this.link_menu_user = 'LikeEvent';
            await axios.get('users/' + this.user_id + '/liked-events/')
            .then(response => this.events_sights_user = response.data.result)
            .catch(error => console.log(error)); 
            this.menu_user_loader = false;
        }, 
        async showFavoritesEvent() {
            this.menu_user_loader = true;
            this.link_menu_user = 'FavoritesEvent';
            await axios.get('users/' + this.user_id + '/favorite-events')
            .then(response => this.events_sights_user = response.data.result)
            .catch(error => console.log(error));
            this.menu_user_loader = false;
        }, 
        async showLikeSight() {
            this.menu_user_loader = true;
            this.link_menu_user = 'LikeSight';
            await axios.get('users/' + this.user_id + '/liked-sights')
            .then(response => this.events_sights_user = response.data.result)
            .catch(error => console.log(error));
            this.menu_user_loader = false;
        }, 
        async showFavoritesSight() {
            this.menu_user_loader = true;
            this.link_menu_user = 'FavoritesSight';
            await axios.get('users/' + this.user_id + '/favorite-sights' )
            .then(response => this.events_sights_user = response.data.result)
            .catch(error => console.log(error));
            this.menu_user_loader = false;
        },
        async showPage(url) {
            await axios.get(url)
            .then(response => this.events_sights_user = response.data.result)
            .catch(error => this.toast.warning(error.message));
        }

    },
    
    state: () => ({
        users: [],
        loader: true,
        user_id: '',
        user_search: {
            name: '',
            email: '',
            city: '',
            region: '',
            time: '',
        },
        show_modal_users: false,
        del_modal_users: false,
        detailed_modal_users: false,
        user_del: '',
        user_upd: '',
        user_upd_id: '',
        links: [],
        types: [],
        toast: useToastStore(),
        link_menu_user: '',
        menu_user_loader: false,
        events_sights_user: [],
        modal_links: [],
    }),
    getters: {
        
    }
})
