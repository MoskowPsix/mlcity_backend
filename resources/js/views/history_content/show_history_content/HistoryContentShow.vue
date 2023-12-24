<template lang="">
    <div>
        
    </div>
</template>
<script>
import { mapActions } from 'pinia'
import { useLoaderStore } from '../../../stores/LoaderStore'
import { useHistoryContentStore } from '../../../stores/HistoryContentStore'
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
            
        }
    },
    methods: {
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useHistoryContentStore, 'getHistoryContentByIds'),
        getHistoryContent() {
            this.getHistoryContentByIds(this.$route.params.id).pipe(
                delay(100),
                retry(2),
                map(response => {
                    console.log(response)
                }),
                catchError(err => {
                    console.log(err)
                }),
                takeUntil(this.destroy$)
            ).subscribe()
        },
        getEvent() {

        }, 
        getSight() {
            
        }
    },
    mounted() {
        
    },
}
</script>
<style lang="">
    
</style>