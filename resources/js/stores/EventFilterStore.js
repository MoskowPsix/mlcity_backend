import { defineStore } from 'pinia'
import { BehaviorSubject } from 'rxjs';


export const useEventFilterStore = defineStore('useEventFilter', {
    state: () => ({
        eventName: new BehaviorSubject(localStorage.getItem('eventNameFilter') || ''),
        eventDate: new BehaviorSubject(localStorage.getItem('eventDateFilter') || '~'),
        eventSponsor: new BehaviorSubject(localStorage.getItem('eventSponsorFilter') || ''),
        eventSearchText: new BehaviorSubject(localStorage.getItem('eventTextFilter') || ''),
        eventStatuses: new BehaviorSubject(localStorage.getItem('eventStatusesFilter') || ''),
        eventStatusLast: new BehaviorSubject(localStorage.getItem('eventStatusLastFilter') || 'true'),
        eventUser: new BehaviorSubject(localStorage.getItem('eventUserFilter') || ''),
    }),
    actions: {
        setEventName(name) {
            localStorage.setItem('eventNameFilter', name)
            this.eventName = name
        },
        getEventName() {
            return localStorage.getItem('eventNameFilter')
        },
        setEventDate(date) {
            localStorage.setItem('eventDateFilter', date)
            this.eventDate = date
        },
        getEventDate() {
            return localStorage.getItem('eventDateFilter') || this.eventDate.getValue()
        },
        setEventSponsor(sponsor) {
            localStorage.setItem('eventSponsorFilter', sponsor)
            this.eventSponsor = sponsor
        },
        getEventSponsor() {
            return localStorage.getItem('eventSponsorFilter')

        },
        setEventText(text) {
            localStorage.setItem('eventTextFilter', text)
            this.eventSearchText = text
        },
        getEventText() {
            return localStorage.getItem('eventTextFilter')
        },
        setEventStatuses(status) {
            localStorage.setItem('eventStatusesFilter', status)
            this.eventStatuses = status
        },
        getEventStatuses() {
            return localStorage.getItem('eventStatusesFilter')
        },
        setEventStatusLast(status) {
            localStorage.setItem('eventStatusLastFilter', status)
            this.eventStatusLast = status
        },
        getEventStatusLast() {
            return localStorage.getItem('eventStatusLastFilter')
        },
        setEventUser(user) {
            localStorage.setItem('eventUserFilter', user)
            this.eventUser = user
        },
        getEventUser() {
            return localStorage.getItem('eventUserFilter')
        }
    },
})