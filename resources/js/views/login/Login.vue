<script>
import { useAuthStore } from '../../stores/AuthStore'
import { mapActions } from 'pinia'
import { useLocalStorageStore } from '../../stores/LocalStorageStore'
import { useLoaderStore } from '../../stores/LoaderStore'
import { MessageAuth } from '../../enums/auth_messages'
import { useToastStore } from '../../stores/ToastStore'
import { catchError, tap, map, retry, delay, takeUntil} from 'rxjs/operators'
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
          router.push({name: 'users'})
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
        this.setToken(this.$route.params.token)
        this.setTimeZone(Intl.DateTimeFormat().resolvedOptions().timeZone)
        router.push({name: 'my-events'})
      }
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
                  <button @click.prevent="loginSubmit()" class="w-full dark:text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Войти</button></form>
          </div>
      </div>
  </div>
</section>
</template>