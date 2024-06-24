import { defineStore } from 'pinia'
import { BehaviorSubject } from 'rxjs'

export const useSightFilterStore = defineStore('useSightFilter', {
    state: () => ({
        filterChange: new BehaviorSubject(false),
        sightLocation: new BehaviorSubject(
            localStorage.getItem('sightLocationFilter') || '',
        ),
        sightName: new BehaviorSubject(
            localStorage.getItem('sightNameFilter') || '',
        ),
        sightSponsor: new BehaviorSubject(
            localStorage.getItem('sightSponsorFilter') || '',
        ),
        sightSearchText: new BehaviorSubject(
            localStorage.getItem('sightTextFilter') || '',
        ),
        sightStatuses: new BehaviorSubject(
            localStorage.getItem('sightStatusesFilter') || '',
        ),
        sightStatusLast: new BehaviorSubject(
            localStorage.getItem('sightStatusLastFilter') || 'true',
        ),
        sightUser: new BehaviorSubject(
            localStorage.getItem('sightUserFilter') || '',
        ),
    }),
    actions: {
        clearFilters() {
            console.log('clearing filters')
            localStorage.removeItem('sightNameFilter')
            localStorage.removeItem('sightDateFilter')
            localStorage.removeItem('sightSponsorFilter')
            localStorage.removeItem('sightTextFilter')
            localStorage.removeItem('sightStatusesFilter')
            localStorage.removeItem('sightStatusLastFilter')
            localStorage.removeItem('sightUserFilter')
            localStorage.removeItem('sightLocationFilter')
            this.sightName = ''
            this.sightDate = ''
            this.sightSponsor = ''
            this.sightSearchText = ''
            this.sightStatuses = ''
            this.sightStatusLast = ''
            this.sightUser = ''
            this.sightLocation = ''
            this.filterChange.next(true)
        },
        setSightLocation(location) {
            localStorage.setItem('sightLocationFilter', location)
            this.sightLocation = location
        },
        getSightLocation() {
            return localStorage.getItem('sightLocationFilter')
        },
        setSightName(name) {
            localStorage.setItem('sightNameFilter', name)
            this.sightName = name
        },
        getSightName() {
            return localStorage.getItem('sightNameFilter')
        },
        setSightSponsor(sponsor) {
            localStorage.setItem('sightSponsorFilter', sponsor)
            this.sightSponsor = sponsor
        },
        getSightSponsor() {
            return localStorage.getItem('sightSponsorFilter')
        },
        setSightText(text) {
            localStorage.setItem('sightTextFilter', text)
            this.sightSearchText = text
        },
        getSightText() {
            return localStorage.getItem('sightTextFilter')
        },
        setSightStatuses(status) {
            localStorage.setItem('sightStatusesFilter', status)
            this.sightStatuses = status
        },
        getSightStatuses() {
            let status = localStorage.getItem('sightStatusesFilter')
            return status
        },
        setSightStatusLast(status) {
            localStorage.setItem('sightStatusLastFilter', status)
            this.sightStatusLast = status
        },
        getSightStatusLast() {
            return localStorage.getItem('sightStatusLastFilter')
        },
        setSightUser(user) {
            localStorage.setItem('sightUserFilter', user)
            this.sightUser = user
        },
        getSightUser() {
            return localStorage.getItem('sightUserFilter')
        },
    },
})
