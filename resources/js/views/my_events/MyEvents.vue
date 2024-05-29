<template lang="">
    <div>
        <EventTable
            :events="events"
            class="m-1"
            @event="clickEvent"
        />
        <div
            v-if="nextPage || backPage"
            class="flex justify-center m-1"
        >
            <PaginateBar
                :next-page="nextPage"
                :back-page="backPage"
                class="w-[70%]"
                @on-back-page="viewBackPage()"
                @on-next-page="viewNextPage()"
            />
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState } from 'pinia'
    import { useToastStore } from '../../stores/ToastStore'
    import { MessageEvents } from '../../enums/events_messages'
    import { useEventStore } from '../../stores/EventStore'
    import { useLoaderStore } from '../../stores/LoaderStore'
    import {
        catchError,
        tap,
        map,
        retry,
        delay,
        takeUntil,
    } from 'rxjs/operators'
    import { of, EMPTY, Subject } from 'rxjs'

    import router from '../../routes'
    import PaginateBar from '../../components/paginate_bar/PaginateBar.vue'
    import EventTable from '../../components/tables/event_table/EventTable.vue'
    import EventFilter from '../../components/filters/events_filter/EventFilter.vue'

    export default {
        name: 'MyEvents',
        setup() {
            const destroy$ = new Subject()
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
        methods: {
            ...mapActions(useToastStore, ['showToast']),
            ...mapActions(useLoaderStore, [
                'openLoaderFullPage',
                'closeLoaderFullPage',
            ]),
            ...mapActions(useEventStore, ['getEventsForAuthor']),
            getAllEventForAuthor(page) {
                this.openLoaderFullPage()
                this.getEventsForAuthor({ page: page })
                    .pipe(
                        map((response) => {
                            this.closeLoaderFullPage()
                            console.log(response)
                            if (response.data.events.data.length) {
                                this.events = response.data.events.data
                            } else {
                                this.showToast(
                                    MessageEvents.info_events,
                                    'info',
                                )
                            }
                            this.nextPage = response.data.events.next_cursor
                            this.backPage = response.data.events.prev_cursor
                        }),
                        catchError((err) => {
                            this.closeLoaderFullPage()
                            console.log(err)
                            399 < err.response.status &&
                            err.response.status < 500
                                ? this.showToast(
                                      MessageEvents.warning_events +
                                          ': ' +
                                          err.message,
                                      'warning',
                                  )
                                : null
                            499 < err.response.status &&
                            err.response.status < 600
                                ? this.showToast(
                                      MessageEvents.error_events +
                                          ': ' +
                                          err.message,
                                      'error',
                                  )
                                : null
                            return of(EMPTY)
                        }),
                        takeUntil(this.destroy$),
                    )
                    .subscribe()
            },
            clickEvent(event) {
                router.push({ path: `/event/${event.id}` })
            },
            viewBackPage() {
                this.getAllEventForAuthor(this.backPage)
            },
            viewNextPage() {
                this.getAllEventForAuthor(this.nextPage)
            },
        },
        components: {
            PaginateBar,
            EventTable,
        },
        mounted() {
            console.log("I WORK")
            this.getAllEventForAuthor()
        },
    }
</script>
<style lang=""></style>
