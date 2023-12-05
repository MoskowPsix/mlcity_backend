import { defineStore } from 'pinia'
import { useAuthStore } from './AuthStore'
import { BehaviorSubject } from 'rxjs';


export const useHistoryContentsFilterStore = defineStore('useHistoryContentsFilter', {
    state: () => ({
        contentName: new BehaviorSubject(localStorage.getItem('contentNameFilter') || ''),
        contentDate: new BehaviorSubject(localStorage.getItem('contentDateFilter') || ''),
        contentSponsor: new BehaviorSubject(localStorage.getItem('contentSponsorFilter') || ''),
        contentSearchText: new BehaviorSubject(localStorage.getItem('contentTextFilter') || ''),
        contentStatuses: new BehaviorSubject(localStorage.getItem('contentStatusesFilter') || '1'),
        contentStatusLast: new BehaviorSubject(localStorage.getItem('contentStatusLastFilter') || 'true'),
        contentUser: new BehaviorSubject(localStorage.getItem('contentUserFilter') || ''),
    }),
    actions: {
        setContentName(name) {
            localStorage.setItem('contentNameFilter', name)
            this.contentName = name
        },
        getContentName() {
            return localStorage.getItem('contentNameFilter')
        },
        setContentDate(date) {
            localStorage.setItem('contentDateFilter', date)
            this.contentDate = date
        },
        getContentDate() {
            return localStorage.getItem('contentDateFilter')
        },
        setContentSponsor(sponsor) {
            localStorage.setItem('contentSponsorFilter', sponsor)
            this.contentSponsor = sponsor
        },
        getContentSponsor() {
            return localStorage.getItem('contentSponsorFilter')

        },
        setContentText(text) {
            localStorage.setItem('contentTextFilter', text)
            this.contentSearchText = text
        },
        getContentText() {
            return localStorage.getItem('contentTextFilter')
        },
        setContentStatuses(status) {
            localStorage.setItem('contentStatusesFilter', status)
            this.contentStatuses = status
        },
        getContentStatuses() {
            let status = localStorage.getItem('contentStatusesFilter')
            return status
        },
        setContentStatusLast(status) {
            localStorage.setItem('contentStatusLastFilter', status)
            this.contentStatusLast = status
            console.log(this.contentStatusLast)
        },
        getContentStatusLast() {
            return localStorage.getItem('contentStatusLastFilter')
        },
        setContentUser(user) {
            localStorage.setItem('contentUserFilter', user)
            this.contentUser = user
        },
        getContentUser() {
            return localStorage.getItem('contentUserFilter')
        }
    },
})