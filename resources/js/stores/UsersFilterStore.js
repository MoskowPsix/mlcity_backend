import { defineStore } from 'pinia'
import { useAuthStore } from './AuthStore'
import { BehaviorSubject } from 'rxjs';


// const authStore = useAuthStore()
export const useUsersFilterStore = defineStore('useUsersFilter', {
    state: () => ({
        name: new BehaviorSubject(localStorage.getItem('userNameFilter') || ''),
        email: new BehaviorSubject(localStorage.getItem('userEmailFilter') || ''),
        createdDateStart: new BehaviorSubject(localStorage.getItem('userCreatedDateStart') || new Date().toISOString().slice(0, 10)),
        updatedDateStart: new BehaviorSubject(localStorage.getItem('userUpdatedDateStart') ||new Date().toISOString().slice(0, 10)),
        locationId: new BehaviorSubject(localStorage.getItem('userLocationIdFilter') ||'')
    }),
    actions: {
        setName(name) {
            localStorage.setItem('userNameFilter', name)
            this.name = name
        },
        getName() {
            return localStorage.getItem('userNameFilter')
        }, 
        setEmail(email) {
            localStorage.setItem('userEmailFilter', email)
            this.email = email
        }, 
        getEmail() {
            return localStorage.getItem('userEmailFilter')
        }, 
        setCreatedDateStart(date) {
            localStorage.setItem('userCreatedDateStartFilter', email)
            this.createdDateStart = new Date(date).toISOString().slice(0, 10)
        }, 
        getCreatedDateStart() {
            return localStorage.getItem('userCreatedDateStartFilter')
        }, 
        setUpdatedDateStart(date) {
            localStorage.setItem('userUpdatedDateStartFilter', new Date(date).toISOString().slice(0, 10))
            this.updatedDateStart = new Date(date).toISOString().slice(0, 10)
        }, 
        getUpdatedDateStart() {
            return localStorage.getItem('userUpdatedDateStartFilter') 
        },
        setLocation(locationId) {
            localStorage.setItem('userLocationIdFilter', locationId)
            this.locationId = locationId
        },
        getLocation() {
            return localStorage.getItem('userLocationIdFilter')
        }
    },
})