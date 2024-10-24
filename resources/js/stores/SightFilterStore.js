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
        filterChangeTrue() {
            this.filterChange.next(true)
        },
        clearFilters() {
            localStorage.removeItem('sightNameFilter')
            localStorage.removeItem('sightSponsorFilter')
            localStorage.removeItem('sightTextFilter')
            localStorage.removeItem('sightStatusesFilter')
            localStorage.removeItem('sightStatusLastFilter')
            localStorage.removeItem('sightUserFilter')
            localStorage.removeItem('sightLocationFilter')
            this.sightName.next('')
            this.sightSponsor.next('')
            this.sightSearchText.next('')
            this.sightStatuses.next('')
            this.sightStatusLast.next('')
            this.sightUser.next('')
            this.sightLocation.next('')
            this.filterChange.next(true)
        },
        setSightLocation(location) {
            localStorage.setItem('sightLocationFilter', location)
            this.sightLocation.next(location)
        },
        getSightLocation() {
            return localStorage.getItem('sightLocationFilter')
        },
        setSightName(name) {
            localStorage.setItem('sightNameFilter', name)
            this.sightName.next(name)
        },
        getSightName() {
            return localStorage.getItem('sightNameFilter')
        },
        setSightSponsor(sponsor) {
            localStorage.setItem('sightSponsorFilter', sponsor)
            this.sightSponsor.next(sponsor)
        },
        getSightSponsor() {
            return localStorage.getItem('sightSponsorFilter')
        },
        setSightText(text) {
            localStorage.setItem('sightTextFilter', text)
            this.sightSearchText.next(text)
        },
        getSightText() {
            return localStorage.getItem('sightTextFilter')
        },
        setSightStatuses(status) {
            localStorage.setItem('sightStatusesFilter', status)
            this.sightStatuses.next(status)
        },
        getSightStatuses() {
            return localStorage.getItem('sightStatusesFilter')
        },
        setSightStatusLast(status) {
            localStorage.setItem('sightStatusLastFilter', status)
            this.sightStatusLast.next(status)
        },
        getSightStatusLast() {
            return localStorage.getItem('sightStatusLastFilter')
        },
        setSightUser(user) {
            localStorage.setItem('sightUserFilter', user)
            this.sightUser.next(user)
        },
        getSightUser() {
            return (
                localStorage.getItem('sightUserFilter') || this.sightUser.value
            )
        },
    },
})
