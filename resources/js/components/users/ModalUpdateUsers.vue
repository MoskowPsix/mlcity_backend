<script>
import {useUsersStore} from '../../stores/usersStore'
import { defineComponent } from 'vue';
import {useRoleStore} from '../../stores/roleStore'


 export default defineComponent({
   setup() {
    const store = useUsersStore();
    const store_role = useRoleStore();
    const user_email = '';
    const user_name = '';
    const user_role = '';
    return {store, store_role, user_email, user_name, user_role};
    }, 
  })
</script>

<template>

<div class="main-modal fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster"
		style="background: rgba(0,0,0,.2);">
		<div
			class="border border-blue-400 shadow-lg modal-container bg-gray-100 dark:bg-gray-800 w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
			<div class="modal-content py-6 text-left px-6 ">
				<!--Title-->
				<div class="flex justify-between items-center pb-3">
                    <p class="text-1xl font-bold text-gray-600 dark:text-gray-300">Редактировать пользователя <br> Имя: {{ store.user_upd_id.name }} <br> ID: {{store.user_upd_id.id}} <br> Email: {{ store.user_upd_id.email }}</p>
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
				<div class="border border-gray-300 dark:border-gray-800 p-6 grid grid-cols-1 gap-6 dark:bg-gray-700 shadow-lg rounded-lg">
					<div class="flex justify-center">
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 items-center p-2 ">
              <input type="text" v-model="user_name" placeholder="Имя пользователя..."
              class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none"/>
            </div>
          </div>
          <div class="flex justify-center text-gray-200">
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 items-center p-2 ">
              <input type="text" v-model="user_email" placeholder="Email пользователя..."
              class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none "/>
            </div>
          </div>
          <div class="flex justify-center text-gray-200">
            <div class="flex rounded bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200 items-center p-2 ">
                <select v-model="user_role" class="bg-gray-300 dark:bg-gray-800 text-gray-600 dark:text-gray-200  max-w-full focus:outline-none ">
                  <option v-for="role in store_role.role.data" :value="role.id">
                    {{ role.name }}
                  </option>
                </select>
            </div>
          </div>
				</div>
				<!--Footer-->
				<div class="flex justify-end pt-2">
					<button @click="store.closeModal()" class="px-4 bg-gray-400 p-3 rounded-lg text-black hover:bg-gray-300">Отмена</button>
					<button @click="store.updateUser(user_name, user_email, user_role)" class="focus:outline-none px-4 bg-blue-500 p-3 ml-3 rounded-lg text-white hover:bg-blue-400">Редактировать</button>
				</div>
			</div>
		</div>
	</div>
  </template>