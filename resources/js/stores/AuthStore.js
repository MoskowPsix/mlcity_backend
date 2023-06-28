import { defineStore } from 'pinia'
import axios from 'axios';



const config = {
    headers: { Authorization: `Bearer ${localStorage.token}` }
};

export const useAuthStore = defineStore('AuthStore', {
    state: () => ({

    }),
    actions: {
        Logout() {
            // Не работает метод со стороны сервера
            // axios.post('http://localhost:8000/api/logout', config)
            //    .then()
            //    .catch(error => console.log(error));
            router.push({ path: '/login' });
            localStorage.clear();
         },
    }
})