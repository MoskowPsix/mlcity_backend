<template lang="">
    <div class="flex flex-row rounded-lg h-auto border m-1">
        <h1></h1>

    </div>
    <div class="grid grid-cols-2">
        <div class="rounded-lg h-96 border m-1">

        </div>
        <div class="rounded-lg h-96 border m-1">

        </div>
        
    </div>
</template>
<script>
import { mapActions } from 'pinia'
import { useLoaderStore } from '../../../stores/LoaderStore'
import { useHistoryContentStore } from '../../../stores/HistoryContentStore'
import { useSightStore } from '../../../stores/SightStore'
import { useEventStore } from '../../../stores/EventStore'
import { catchError, tap, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'
import { useToastStore } from '../../../stores/ToastStore'
import { MessageContents } from '../../../enums/content_messages'

export default {
    name: 'ShowHistoryContent',
    props: {
        historyContent: Object
    },
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        } 
    },
    data() {
        return {
            historyContent: {},
            event: {},
            sight: {}
        }
    },
    methods: {
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useHistoryContentStore, ['getHistoryContentByIds']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useSightStore, ['getSightForIds']),
        ...mapActions(useEventStore, ['getEventForIds']),
        getHistoryContent() {
            this.getHistoryContentByIds(this.$route.params.id).pipe(
                delay(100),
                retry(2),
                map(response => {
                    console.log(response)
                    if (response.history_contentable_type == 'App\\Models\\Sight') {
                        this.historyContent = response
                        this.getSight
                    } else if (response.history_contentable_type == 'App\\Models\\Event') {
                        this.historyContent = response.historyContents
                        this.getEvent()
                    } else {
                        this.showToast(MessageContents.warning_one_history_content_type, 'warning')
                    }
                }),
                catchError(err => {
                    console.log(err)
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$)
            ).subscribe()
        },
        getEvent() {
            this.getEventForIds(this.historyContent.history_contentable_id).pipe(
                map(response => {
                    this.event = response.data
                    console.log(this.event)
                }),
                catchError(err => {
                    console.log(err)
                    router.go(-1)
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$),
            ).subscribe()
        }, 
        getSight() {
            this.getSightForIds(history_contentable_id).pipe(
                retry(3),
                delay(100),
                map(response => {
                    console.log(response)
                    this.sight = response.data
                }),
                catchError(err => {
                    console.log(err)
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$),
            ).subscribe()
        }
    },
    mounted() {
        this.getHistoryContent()
    },
}
</script>
<style lang="">
    
</style>