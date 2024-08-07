// import axios from 'axios';
import { defineStore } from 'pinia'

export const useLocalStorageStore = defineStore('LocalStorage', {
    state: () => ({
        token: null,
        role: null,
        user: {},
        auth: false,
        timeZone: '',
    }),
    actions: {
        localStorageInit() {
            if (localStorage.getItem('token')) {
                this.token = localStorage.getItem('token')
                this.role = localStorage.getItem('role')
                this.user = JSON.parse(localStorage.getItem('user'))
                this.auth = true
            } else {
                this.token = ''
                this.role = ''
                this.user = ''
                this.timeZone = ''
                this.auth = false
                localStorage.clear()
            }
        },
        setToken(token) {
            localStorage.setItem('token', token)
            this.token = token
            // axios.defaults.headers = {'Authorization': `Bearer ${token}`}
        },
        setRole(role) {
            localStorage.setItem('role', role)
            this.role = role
        },
        setUser(user) {
            this.user = user
            localStorage.setItem('user', JSON.stringify(user))
        },
        setTimeZone(timeZone) {
            localStorage.setItem('timezone', JSON.stringify(timeZone))
            this.timeZone = timeZone
        },
        getUserLocalStorage() {
            return JSON.parse(localStorage.getItem('user'))
        },
    },
    getters: {
        getToken() {
            return this.token
        },
        getUser() {
            return this.user
        },
        getRole() {
            return this.role
        },
        getAuth() {
            return this.auth
        },
        getTimeZone() {
            return this.timeZone
        },
    },
})
