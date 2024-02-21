<template lang="">
    <EventFilter class="m-1"/>
    <EventTable :events="events" @event="clickEvent" class="m-1"/>
    <div class="flex justify-center m-1" v-if="nextPage || backPage">
            <PaginateBar :nextPage="nextPage" :backPage="backPage" @onBackPage="viewBackPage()" @onNextPage="viewNextPage()" class="w-[70%]"/>
    </div>
</template>
<script>
import { mapActions, mapState } from 'pinia'
import { useToastStore } from '../../stores/ToastStore'
import { MessageEvents } from '../../enums/events_messages'
import { useEventStore } from '../../stores/EventStore'
import { useEventFilterStore } from '../../stores/EventFilterStore'
import { useLoaderStore } from '../../stores/LoaderStore'
import { useEventQueryBuilderStore } from '../../stores/EventQueryBuilderStore'
import { catchError, tap, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'

import router from '../../routes'
import PaginateBar from '../../components/paginate_bar/PaginateBar.vue'
import EventTable from '../../components/tables/event_table/EventTable.vue'
import EventFilter from '../../components/filters/events_filter/EventFilter.vue'


export default {
    name: 'Users',
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        } 
    },
    data() {
        return {
            events: [],
            nextPage: null,
            backPage: null,
        }
    },
    components: {
        PaginateBar,
        EventTable,
        EventFilter
    },
    computed: {
        ...mapState(useEventFilterStore, [
        'eventName',
        'eventDate',
        'eventSponsor',
        'eventSearchText',
        'eventStatuses',
        'eventStatusLast',
        'eventUser',
        ]),
    },
    methods: {
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useEventQueryBuilderStore, ['queryBuilder', 'setPageEventForPageEvents']),
        ...mapActions(useEventStore, ['getEvents']),
        getAllEvent() {
            this.openLoaderFullPage()
            this.getEvents(this.queryBuilder('eventsForPageEvents')).pipe(
                map(response => {
                        this.closeLoaderFullPage()
                        if (response.data.events.data.length) {
                        this.events = response.data.events.data
                        }else {
                            this.showToast(MessageEvents.info_events,'info')
                        }
                        this.nextPage = response.data.events.next_cursor
                        this.backPage = response.data.events.prev_cursor
                     
                    }
                ),
                catchError(err => {
                    this.closeLoaderFullPage()
                    console.log(err)
                    399 < err.response.status && err.response.status < 500 ? this.showToast(MessageEvents.warning_events + ': ' + err.message, 'warning') : null
                    499 < err.response.status && err.response.status < 600 ? this.showToast(MessageEvents.error_events + ': ' + err.message, 'error') : null
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$),
            ).subscribe()
        },
        clickEvent(event) {
            router.push({ path: `/event/${event.id}`})
            console.log(this.event)
        },
        viewBackPage() {
            this.setPageEventForPageEvents(this.backPage)
            this.getAllEvent()
        },
        viewNextPage() {
            this.setPageEventForPageEvents(this.nextPage)
            this.getAllEvent()
        },

    },
    mounted() {
        this.getAllEvent()
    },
    watch: {
        eventName() {
            this.getAllEvent()
        },
        eventDate() {
            this.getAllEvent()
        },
        eventSponsor() {
            this.getAllEvent()
        },
        eventSearchText() {
            this.getAllEvent()
        },
        eventStatuses() {
            this.getAllEvent()
        },
        eventStatusLast() {
            this.getAllEvent()
        },
        eventUser() {
            this.getAllEvent()
        },
    }
    
}
</script>
<style lang="">
    
</style>