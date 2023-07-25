import axios from 'axios';
import { defineStore } from 'pinia';
import { useRouter } from 'vue-router';
import { useToastStore } from '../stores/toastStore';
import { useBarStore } from '../stores/barStore';


export const useLoginStore = defineStore('useLogin', {
    actions: {
        async login() {
            // получаем токен
            await axios.post('login', {
              email: this.email,
              password: this.password,
            }).then(response =>  {
                localStorage.setItem('token', response.data.access_token);
                axios.defaults.headers = {'Authorization': `Bearer ${localStorage.token}`}
                axios.get('users/' + response.data.user.id)
                .then(response => {
                localStorage.setItem('role', response.data.user.roles[0].name);
                this.router.push({ name: 'users' });
              })
              .catch(error => 'Ошибка при получении роли: ' + error.message);
            }).catch(error => {
              useToastStore().warning('Ошибка авторизации: ' + error.message);
            });
            // Отправляем на стартовую страницу
            // console.log('Перед отправкой в дом');
          },
        logout() {
            localStorage.clear();
            this.bar_store.closeBar();
            this.router.push({ name: 'login' });
        },
    },
    state: () => ({
        bar_store: useBarStore(),
        router: useRouter(),
        email: '',
        password: ''
    })
})