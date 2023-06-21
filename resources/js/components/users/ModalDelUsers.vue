<script>
import {useUsersStore} from '../../stores/usersStore'
import { defineComponent } from 'vue';


//const store = useUsersStore().closeModal();
 export default defineComponent({
   setup() {
    const store = useUsersStore();
    return {store};
    },
   methods: {
    closeModal() {
      this.store.closeModalDel()
    },
    DelUser() {
      this.store.delUsers();
    },
   }  
  })
</script>

<template>
<div class="main-modal fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster"
		style="background: rgba(0,0,0,.2);">
		<div
			class="border border-red-400 shadow-lg modal-container bg-gray-100 dark:bg-gray-800 w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
			<div class="modal-content py-6 text-left px-6 ">
				<!--Title-->
				<div class="flex justify-between items-center pb-3">
					<p class="text-2xl font-bold text-gray-700 dark:text-gray-300">Удалить пользователя?</p>
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
                <slot></slot>
                <p class="text-1xl font-bold text-gray-500 dark:text-gray-300"> Имя: {{ store.user_del.name }} <br> ID: {{store.user_del.id}} <br> Email: {{ store.user_del.email }}</p>
                <!--Footer-->
				<div class="flex justify-end pt-2">
					<button @click="closeModal()" class="px-4 bg-gray-400 p-3 rounded-lg text-black hover:bg-gray-300">Отмена</button>
					<button @click="DelUser()" class="focus:outline-none px-4 bg-red-500 p-3 ml-3 rounded-lg text-grey-300 hover:bg-red-400">Удалить</button>
				</div>
			</div>
		</div>
	</div>
  </template>

<style>
.animated {
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
}

.animated.faster {
  -webkit-animation-duration: 500ms;
  animation-duration: 500ms;
}

.fadeIn {
  -webkit-animation-name: fadeIn;
  animation-name: fadeIn;
}

.fadeOut {
  -webkit-animation-name: fadeOut;
  animation-name: fadeOut;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

@keyframes fadeOut {
  from {
    opacity: 1;
  }

  to {
    opacity: 0;
  }
}
</style>