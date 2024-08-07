import { defineStore } from 'pinia'
import { BehaviorSubject } from 'rxjs'

export const useEventFilterStore = defineStore('useEventFilter', {
    state: () => ({
        filterEventChange: new BehaviorSubject(false),
        eventName: new BehaviorSubject(
            localStorage.getItem('eventNameFilter') || '',
        ),
        eventDate: new BehaviorSubject(
            localStorage.getItem('eventDateFilter') || '~',
        ),
        eventSponsor: new BehaviorSubject(
            localStorage.getItem('eventSponsorFilter') || '',
        ),
        eventSearchText: new BehaviorSubject(
            localStorage.getItem('eventTextFilter') || '',
        ),
        eventStatuses: new BehaviorSubject(
            localStorage.getItem('eventStatusesFilter') || '',
        ),
        eventStatusLast: new BehaviorSubject(
            localStorage.getItem('eventStatusLastFilter') || 'true',
        ),
        eventUser: new BehaviorSubject(
            localStorage.getItem('eventUserFilter') || '',
        ),
        eventLocation: new BehaviorSubject(
            localStorage.getItem('eventLocationFilter') || '',
        ),
    }),
    actions: {
        clearFilters() {
            localStorage.setItem('eventDateFilter', '~')
            localStorage.removeItem('eventNameFilter')
            localStorage.removeItem('eventSponsorFilter')
            localStorage.removeItem('eventTextFilter')
            localStorage.removeItem('eventStatusesFilter')
            localStorage.removeItem('eventStatusLastFilter')
            localStorage.removeItem('eventUserFilter')
            localStorage.removeItem('eventLocationFilter')
            this.eventDate.next('~')
            this.eventName.next('')
            this.eventSponsor.next('')
            this.eventSearchText.next('')
            this.eventStatuses.next('')
            this.eventStatusLast.next('')
            this.eventUser.next('')
            this.eventLocation.next('')
            this.filterEventChange.next(true)
        },
        setEventLocation(location) {
            localStorage.setItem('eventLocationFilter', location)
            this.eventLocation = location
        },
        getEventLocation() {
            return localStorage.getItem('eventLocationFilter')
        },
        setEventName(name) {
            localStorage.setItem('eventNameFilter', name)
            this.eventName.next(name)
        },
        getEventName() {
            return localStorage.getItem('eventNameFilter')
        },
        setEventDate(date) {
            localStorage.setItem('eventDateFilter', date)
            this.eventDate.next(date)
        },
        getEventDate() {
            return (
                localStorage.getItem('eventDateFilter') ||
                this.eventDate.getValue()
            )
        },
        setEventSponsor(sponsor) {
            localStorage.setItem('eventSponsorFilter', sponsor)
            this.eventSponsor.next(sponsor)
        },
        getEventSponsor() {
            return localStorage.getItem('eventSponsorFilter')
        },
        setEventText(text) {
            localStorage.setItem('eventTextFilter', text)
            this.eventSearchText.next(text)
        },
        getEventText() {
            return localStorage.getItem('eventTextFilter')
        },
        setEventStatuses(status) {
            localStorage.setItem('eventStatusesFilter', status)
            this.eventStatuses.next(status)
        },
        getEventStatuses() {
            return localStorage.getItem('eventStatusesFilter')
        },
        setEventStatusLast(status) {
            localStorage.setItem('eventStatusLastFilter', status)
            this.eventStatusLast.next(status)
        },
        getEventStatusLast() {
            return localStorage.getItem('eventStatusLastFilter')
        },
        setEventUser(user) {
            localStorage.setItem('eventUserFilter', user)
            this.eventUser.next(user)
        },
        getEventUser() {
            return localStorage.getItem('eventUserFilter')
        },
    },
})
