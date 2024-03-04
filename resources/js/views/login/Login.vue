<script>
import { useAuthStore } from '../../stores/AuthStore'
import { mapActions } from 'pinia'
import { useLocalStorageStore } from '../../stores/LocalStorageStore'
import { useLoaderStore } from '../../stores/LoaderStore'
import { MessageAuth } from '../../enums/auth_messages'
import { useToastStore } from '../../stores/ToastStore'
import { catchError, map, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'
import router from '../../routes'


export default {
  name: 'login',
  data() {
    return {
      name: null,
      password: null,
      toastStore: useToastStore(),
    }
  },
  setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        } 
    },
  methods: {
    ...mapActions(useAuthStore, ['login', 'getUserForToken']),
    ...mapActions(useLocalStorageStore, ['setToken', 'setRole', 'setUser', 'localStorageInit', 'setTimeZone']),
    ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
    ...mapActions(useToastStore, ['showToast']),
    loginSubmit() {
      this.openLoaderFullPage()
      const params = {
        name: this.name,
        password: this.password
      }
      this.login(params).pipe(
        map(response => {
          this.setToken(response.data.access_token)
          this.setTimeZone(Intl.DateTimeFormat().resolvedOptions().timeZone)
          response.data.user.roles[0] ? this.setRole(response.data.user.roles[0].name) : null
          this.setUser(response.data.user)
          this.localStorageInit()
          this.showToast(MessageAuth.success_auth, 'success')
          this.closeLoaderFullPage()
          router.push({name: 'my-events'})
        }),
        catchError(err => {
          if (399 < err.response.status && err.response.status < 500) {
            this.showToast(MessageAuth.warning_auth, 'warning')
          } else if(499 < err.response.status && err.response.status < 600) {
            this.showToast(MessageAuth.error_auth, 'error')
          }
          this.closeLoaderFullPage()
          return of(EMPTY)
        }),
        takeUntil(this.destroy$)
      ).subscribe()
    },
    loginByToken() {
      if (this.$route.params.token.length >= 47) {
        this.openLoaderFullPage()
        this.setToken(JSON.parse(JSON.stringify(this.$route.params.token)))
        this.setTimeZone(Intl.DateTimeFormat().resolvedOptions().timeZone)
        this.localStorageInit()
        router.go({path: 'user/events' })
      }
    },
    loginVK() {
      window.location.href = `${import.meta.env.VITE_APP_URL}/api/social-auth/vkontakte`
    }
  },
  mounted() {
    this.$route.params.token ? this.loginByToken() : null
  },
};
</script>

<template>

  <section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <a href="#" class="flex items-center  items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img src="../../../assets/favicon.png" class="h-6 mr-3 sm:h-7" alt="Flowbite Logo" />
                <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">MyLittleCity<p class="text-xs items-center">AdminPanel</p></span>
            </a>
              <form class="space-y-4 md:space-y-6" @submit.prevent="submit">
                  <div>
                      <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Имя или Email</label>
                      <input type="name" name="name" id="name" v-model="name" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="">
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
                  <button @click.prevent="loginSubmit()" class="w-full dark:text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Войти</button>
                  <!-- <button @click.prevent="loginVK()" class="w-full dark:text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    <div class="grid grid-cols-2 content-stretch w-auto">
                      <p>Войти с помощью</p>
                      <svg fill="white" width="25" height="25" viewBox="-2.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" class="w-full">
                        <title>vk</title>
                        <path d="M16.563 15.75c-0.5-0.188-0.5-0.906-0.531-1.406-0.125-1.781 0.5-4.5-0.25-5.656-0.531-0.688-3.094-0.625-4.656-0.531-0.438 0.063-0.969 0.156-1.344 0.344s-0.75 0.5-0.75 0.781c0 0.406 0.938 0.344 1.281 0.875 0.375 0.563 0.375 1.781 0.375 2.781 0 1.156-0.188 2.688-0.656 2.75-0.719 0.031-1.125-0.688-1.5-1.219-0.75-1.031-1.5-2.313-2.063-3.563-0.281-0.656-0.438-1.375-0.844-1.656-0.625-0.438-1.75-0.469-2.844-0.438-1 0.031-2.438-0.094-2.719 0.5-0.219 0.656 0.25 1.281 0.5 1.813 1.281 2.781 2.656 5.219 4.344 7.531 1.563 2.156 3.031 3.875 5.906 4.781 0.813 0.25 4.375 0.969 5.094 0 0.25-0.375 0.188-1.219 0.313-1.844s0.281-1.25 0.875-1.281c0.5-0.031 0.781 0.406 1.094 0.719 0.344 0.344 0.625 0.625 0.875 0.938 0.594 0.594 1.219 1.406 1.969 1.719 1.031 0.438 2.625 0.313 4.125 0.25 1.219-0.031 2.094-0.281 2.188-1 0.063-0.563-0.563-1.375-0.938-1.844-0.938-1.156-1.375-1.5-2.438-2.563-0.469-0.469-1.063-0.969-1.063-1.531-0.031-0.344 0.25-0.656 0.5-1 1.094-1.625 2.188-2.781 3.188-4.469 0.281-0.5 0.938-1.656 0.688-2.219-0.281-0.625-1.844-0.438-2.813-0.438-1.25 0-2.875-0.094-3.188 0.156-0.594 0.406-0.844 1.063-1.125 1.688-0.625 1.438-1.469 2.906-2.344 4-0.313 0.375-0.906 1.156-1.25 1.031z"></path>
                      </svg>
                    </div>
                  </button> -->
              </form>
          </div>
      </div>
  </div>
</section>
</template>