// import axios from 'axios';
import { defineStore } from 'pinia';
import { MessageAuth } from '../enums/auth_messages';
import { useToast } from "vue-toastification";


const toast = useToast()
export const useLocalStorageStore = defineStore('LocalStorage', {
    state: () =>({
        token: null,
        role: null,
        user: [],
        auth: false,
        timeZone: ''
    }),
    actions: {
        localStorageInit() {
            if(localStorage.getItem('token')) {
                this.token = localStorage.getItem('token')
                this.role = localStorage.getItem('role')
                this.user = localStorage.getItem('user')
                this.auth = true
            } else {
                this.token = ''
                this.role = ''
                this.user = ''
                this.timeZone= ''
                this.auth = false
            }
        },
        setToken(token) {
            localStorage.setItem('token', token)
        },
        setRole(role) {
            localStorage.setItem('role', role)
        },
        setUser(user) {
            localStorage.setItem('user', JSON.stringify(user))
        },
        setTimeZone(timeZone) {
            localStorage.setItem('timezone', JSON.stringify(timeZone))
        }

    },
    getters: {
        getToken() {
            return this.token
        },
        getRole() {
            return this.role
        },
        getAuth() {
            return this.auth
        },
        getTimeZone() {
            return this.timeZone
        }
    }
})