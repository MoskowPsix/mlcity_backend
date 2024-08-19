import { defineStore } from 'pinia'
import { BehaviorSubject } from 'rxjs'

export const useHistoryContentsFilterStore = defineStore(
    'useHistoryContentsFilter',
    {
        state: () => ({
            contentLocation: new BehaviorSubject(
                localStorage.getItem('contentLocationFilter') || '',
            ),
            contentName: new BehaviorSubject(
                localStorage.getItem('contentNameFilter') || '',
            ),
            contentDate: new BehaviorSubject(
                localStorage.getItem('contentDateFilter') || '~',
            ),
            contentSponsor: new BehaviorSubject(
                localStorage.getItem('contentSponsorFilter') || '',
            ),
            contentSearchText: new BehaviorSubject(
                localStorage.getItem('contentTextFilter') || '',
            ),
            contentStatuses: new BehaviorSubject(
                localStorage.getItem('contentStatusesFilter') || '1',
            ),
            contentStatusLast: new BehaviorSubject(
                localStorage.getItem('contentStatusLastFilter') || 'true',
            ),
            contentUser: new BehaviorSubject(
                localStorage.getItem('contentUserFilter') || '',
            ),
        }),
        actions: {
            setContentLocation(location) {
                localStorage.setItem('contentLocationFilter', location)
                this.contentLocation.next(location)
            },
            getContentLocation() {
                return localStorage.getItem('contentLocationFilter')
            },
            setContentName(name) {
                localStorage.setItem('contentNameFilter', name)
                this.contentName.next(name)
            },
            getContentName() {
                return localStorage.getItem('contentNameFilter')
            },
            setContentDate(date) {
                localStorage.setItem('contentDateFilter', date)
                this.contentDate.next(date)
            },
            getContentDate() {
                return (
                    localStorage.getItem('contentDateFilter') ||
                    this.contentDate.getValue()
                )
            },
            setContentSponsor(sponsor) {
                localStorage.setItem('contentSponsorFilter', sponsor)
                this.contentSponsor.next(sponsor)
            },
            getContentSponsor() {
                return localStorage.getItem('contentSponsorFilter')
            },
            setContentText(text) {
                localStorage.setItem('contentTextFilter', text)
                this.contentSearchText.next(text)
            },
            getContentText() {
                return localStorage.getItem('contentTextFilter')
            },
            setContentStatuses(status) {
                localStorage.setItem('contentStatusesFilter', status)
                this.contentStatuses.next(status)
            },
            getContentStatuses() {
                let status = localStorage.getItem('contentStatusesFilter')
                return status
            },
            setContentStatusLast(status) {
                localStorage.setItem('contentStatusLastFilter', status)
                this.contentStatusLast.next(status)
            },
            getContentStatusLast() {
                return localStorage.getItem('contentStatusLastFilter')
            },
            setContentUser(user) {
                localStorage.setItem('contentUserFilter', user)
                this.contentUser.next(user)
            },
            getContentUser() {
                return localStorage.getItem('contentUserFilter')
            },
        },
    },
)
