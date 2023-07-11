<script>
import {useUsersStore} from '../../stores/usersStore'
import { defineComponent } from 'vue';
import {useRoleStore} from '../../stores/roleStore'


//const store = useUsersStore().closeModal();
 export default defineComponent({
   setup() {
    const store_role = useRoleStore();
    const user_role = '';
    const user_email = '';
    const user_name = '';
    const user_password = '';
    const store = useUsersStore();
    return {store, user_email, user_name, user_password, store_role, user_role};
    },
   methods: {
    closeModal() {
      this.store.closeModal()
    },
    createUser(name, email, pass, role) {
      this.store.createUser(name, email, pass, role);
      user_name = '';
      user_email = '';
      user_password = '';
      user_role = '';
    },

   }  
  })
</script>

<template>
<div class="main-modal fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster"
		style="background: rgba(0,0,0,.7);">
		<div class="border border-green-400 shadow-lg modal-container bg-gray-100 dark:bg-gray-800 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
			<div class="modal-content py-6 text-left px-6 ">
				<!--Title-->
				<div class="flex justify-between items-center pb-3">
					<p class="text-2xl font-bold text-gray-700 dark:text-gray-300">Создать пользователя</p>
					<div @click="closeModal()" class="modal-close cursor-pointer z-50">
						<svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
							viewBox="0 0 18 18">
							<path
								d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
							</path>
						</svg>
					</div>
				</div>
				<!--Body-->
        <div class="flex justify-between rounded bg-gray-300 dark:bg-gray-800">
          <div class="border border-gray-300 dark:border-gray-800 p-6 grid grid-cols-1 gap-6 dark:bg-gray-700 shadow-lg rounded-lg">
            <input type="text" v-model="user_name" placeholder="Имя пользователя..."
              class="bg-gray-300 dark:bg-gray-800 text-gray-500 dark:text-gray-200  max-w-full focus:outline-none"/>
            <input type="text" v-model="user_email" placeholder="Email пользователя..."
              class="bg-gray-300 dark:bg-gray-800 text-gray-500 dark:text-gray-200  max-w-full focus:outline-none"/>
            <input type="text" v-model="user_password" placeholder="Пароль пользователя..."
              class="bg-gray-300 dark:bg-gray-800 text-gray-500 dark:text-gray-200  max-w-full focus:outline-none "/>
            <input type="text" v-model="user_city" placeholder="Город пользователя..."
              class="bg-gray-300 dark:bg-gray-800 text-gray-500 dark:text-gray-200  max-w-full focus:outline-none"/>
            <input type="text" v-model="user_region" placeholder="Регион пользователя..."
              class="bg-gray-300 dark:bg-gray-800 text-gray-500 dark:text-gray-200  max-w-full focus:outline-none"/>
            <div class="flex justify-center rounded bg-gray-300 dark:bg-gray-800 text-gray-500 p-2 dark:text-gray-200  grid grid-cols-1 gap-1">
            <h1 class="text-xs">Роль пльзователя: </h1>
              <select v-model="user_role" class="bg-gray-300 dark:bg-gray-800 text-gray-500 dark:text-gray-200  max-w-full focus:outline-none ">
                <option v-for="role in store_role.role.data" :value="role.id">
                  {{ role.name }}
                </option>
              </select>
            </div>
          </div>
				</div>
				<!--Footer-->
				<div class="flex justify-end pt-2">
					<button @click="closeModal()" class="px-4 bg-gray-400 p-3 rounded-lg text-black hover:bg-gray-300">Отмена</button>
					<button @click="createUser(user_name, user_email, user_password, user_role)" class="focus:outline-none px-4 bg-teal-500 p-3 ml-3 rounded-lg text-white hover:bg-teal-400">Создать</button>
				</div>
			</div>
		</div>
	</div>
  </template>