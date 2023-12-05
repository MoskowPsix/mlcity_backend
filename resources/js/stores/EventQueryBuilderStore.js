import { defineStore } from 'pinia'
import { useEventFilterStore } from './EventFilterStore';


export const useEventQueryBuilderStore = defineStore('useEventQueryBuilder', {
    
    actions: {
        queryBuilder(func) {
        this.updateParams()

        switch(func) {
            case 'eventsForPageEvents':
                this.eventsForPageEvents()
            break;
        }

        return this.queryParams
        },
        updateParams() {
            this.name = useEventFilterStore().getEventName(),
            this.date = useEventFilterStore().getEventDate(),
            this.sponsor = useEventFilterStore().getEventSponsor(),
            this.searchText = useEventFilterStore().getEventText(),
            this.statuses = useEventFilterStore().getEventStatuses(),
            this.statusLast = useEventFilterStore().getEventStatusLast(),
            this.user = useEventFilterStore().getEventUser()
        },
        eventsForPageEvents() {
            let date = [new Date().toISOString, '']
            if (this.date) {
                date = this.date.split('~')
            }
            
            this.queryParams = {
                name: this.name,
                dateStart: date[0],
                dateEnd: date[1],
                sponsor: this.sponsor,
                searchText: this.searchText,
                statuses: this.statuses,
                statusesLast: this.statusLast,
                user: this.user,
                page: this.pageEventsForPageEvent
            }
        },
        setPageEventForPageEvents(page) {
            this.pageEventsForPageEvent = page
        },
    },
    state: () => ({
        queryParams: [],
        name: null,
        date: null,
        sponsor: null,
        searchText: null,
        statuses: null,
        statusLast: null,
        user: null,
        pageEventsForPageEvent: null
        
    }),
})