<template lang="">
    <div v-for="event of events">
        {{event.name}}
    </div>
    <EventTable :events="events"/>
    <div class="flex justify-center m-1" v-if="nextPage || backPage">
            <PaginateBar :nextPage="nextPage" :backPage="backPage" @onBackPage="viewBackPage()" @onNextPage="viewNextPage()" class="w-[70%]"/>
    </div>
</template>
<script>
import { mapActions, mapState } from 'pinia'
import { useToastStore } from '../../stores/ToastStore'
import { MessageEvents } from '../../enums/events_messages'
import { useEventStore } from '../../stores/EventStore'
import { useLoaderStore } from '../../stores/LoaderStore'
import { useEventQueryBuilderStore } from '../../stores/EventQueryBuilderStore'
import { catchError, tap, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'

import PaginateBar from '../../components/paginate_bar/PaginateBar.vue'
import EventTable from '../../components/tables/event_table/EventTable.vue'


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
        EventTable
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
                        console.log(response)
                        if (response.data.events.data.length) {
                        this.events = response.data.events.data
                        }else {
                            this.showToast(MessageEvents.info_events,'info')
                        }
                        this.nextPage = response.data.events.next_cursor
                        this.backPage = response.data.events.prev_cursor
                        this.closeLoaderFullPage()
                    }
                ),
                catchError(err => {
                    console.log(err)
                    399 < err.response.status && err.response.status < 500 ? this.showToast(MessageEvents.warning_events + ': ' + err.message, 'warning') : null
                    499 < err.response.status && err.response.status < 600 ? this.showToast(MessageEvents.error_events + ': ' + err.message, 'error') : null
                    this.closeLoaderFullPage()
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$),
            ).subscribe()
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

    }
    
}
</script>
<style lang="">
    
</style>