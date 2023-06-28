<script>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useToastStore } from '../stores/toastStore';
import CryptoJS from "crypto-js";



export default {
  name: 'login',
  setup() {
    const email = ref('');
    const password = ref('');
    const router = useRouter();
    const config = {
        headers: { Authorization: `Bearer ${localStorage.token}` }
    };

    const submit = async () => {
      // получаем токен
      const response = await axios.post('http://localhost:8000/api/login', {
        email: email.value,
        password: password.value,
      }).catch(error => console.log(useToastStore().warning(error.response.data.message)));
      //useToastStore().error(error.response.data.message)
      localStorage.setItem('token', response.data.access_token);
      // Получаеи роль
      const url = 'http://localhost:8000/api/listUsers?id=' + response.data.user.id;
      const data = await axios(url, config)
      .catch(error => console.log(error));
      console.log(data)
      //localStorage.setItem('role', CryptoJS.AES.encrypt(data.data.users.data[0].roles[0].pivot.role_id, data.data.users.data[0].roles[0].name));
      localStorage.setItem('role', data.data.users.data[0].roles[0].name);
      // Отправляем на стартовую страницу
      await router.push({ path: '/' });
    };

    return {
      email,
      password,
      submit,
    };
  },
};
</script>

<template>

  <section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
          <!-- <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo"> -->
              
      </a>
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                  Форма входа
              </h1>
              <form class="space-y-4 md:space-y-6" @submit.prevent="submit">
                  <div>
                      <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Еmail</label>
                      <input type="email" name="email" id="email" v-model="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="">
                  </div>
                  <div>
                      <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Пароль</label>
                      <input type="password" name="password" id="password" v-model="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                  </div>
                  <div class="flex items-center justify-between">
                      <div class="flex items-start">
                          
                          <div class="ml-3 text-sm">
                          </div>
                      </div>
                  </div>
                  <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Войти</button>              </form>
          </div>
      </div>
  </div>
</section>
</template>