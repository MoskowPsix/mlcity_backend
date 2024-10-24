import { defineStore } from 'pinia'
import { BehaviorSubject } from 'rxjs'

export const useUsersFilterStore = defineStore('useUsersFilter', {
    state: () => ({
        name: new BehaviorSubject(localStorage.getItem('userNameFilter') || ''),
        email: new BehaviorSubject(
            localStorage.getItem('userEmailFilter') || '',
        ),
        createdDate: new BehaviorSubject(
            localStorage.getItem('userCreatedDateStart') || '~',
        ),
        updatedDate: new BehaviorSubject(
            localStorage.getItem('userUpdatedDateStart') || '~',
        ),
        locationId: new BehaviorSubject(
            localStorage.getItem('userLocationIdFilter') || '',
        ),
    }),
    actions: {
        setName(name) {
            localStorage.setItem('userNameFilter', name)
            this.name.next(name)
        },
        getName() {
            return localStorage.getItem('userNameFilter')
        },
        setEmail(email) {
            localStorage.setItem('userEmailFilter', email)
            this.email.next(email)
        },
        getEmail() {
            return localStorage.getItem('userEmailFilter')
        },
        setCreatedDate(date) {
            localStorage.setItem('userCreatedDateFilter', date)
            this.createdDate.next(date)
        },
        getCreatedDate() {
            let date =
                localStorage.getItem('userCreatedDateFilter') ||
                this.createdDate.getValue()
            return date
        },
        setUpdatedDate(date) {
            localStorage.setItem('userUpdatedDateFilter', date)
            this.updatedDate.next(date)
        },
        getUpdatedDate() {
            let date =
                localStorage.getItem('userUpdatedDateFilter') ||
                this.updatedDate.getValue()
            return date
        },
        setLocation(locationId) {
            localStorage.setItem('userLocationIdFilter', locationId)
            this.locationId.next(locationId)
        },
        getLocation() {
            return localStorage.getItem('userLocationIdFilter')
        },
    },
})
