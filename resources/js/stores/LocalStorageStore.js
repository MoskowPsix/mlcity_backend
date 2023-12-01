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
        auth: false
    }),
    actions: {
        localStorageInit() {
            if(localStorage.getItem('token')) {
                this.token = localStorage.getItem('token')
                if ((localStorage.getItem('role') === 'Admin') || (localStorage.getItem('role') === 'Moderator') || (localStorage.getItem('role') === 'root')) {
                    this.role = localStorage.getItem('role')
                    this.user = localStorage.getItem('user')
                    this.auth = true
                } else {
                    toast.warning(MessageAuth.warning_role)
                    localStorage.clear()
                    this.token = ''
                    this.role = ''
                    this.user = ''
                    this.auth = false
                }
            } else {
                localStorage.clear()
                this.token = ''
                this.role = ''
                this.user = ''
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
            console.log(user)
            localStorage.setItem('user', JSON.stringify(user))
        },

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
        }
    }
})