import { defineStore } from 'pinia'
import { useAuthStore } from './AuthStore'
import { BehaviorSubject } from 'rxjs';


export const useContentsFilterStore = defineStore('useContentsFilter', {
    state: () => ({
        contentName: new BehaviorSubject(localStorage.getItem('contentNameFilter') || ''),
        contentDate: new BehaviorSubject(localStorage.getItem('contentDateFilter') || ''),
        contentSponsor: new BehaviorSubject(localStorage.getItem('contentSponsorFilter') || ''),
        contentSearchText: new BehaviorSubject(localStorage.getItem('contentTextFilter') || '')
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
        }
    },
})