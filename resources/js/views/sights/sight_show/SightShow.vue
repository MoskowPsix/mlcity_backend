<template lang="">
    <div>
        <h1>SightShow</h1>
        <h1>{{sight.id}}</h1>
        <h1>{{sight.name}}</h1>
    </div>
</template>
<script>
import { mapActions} from 'pinia'
import { useToastStore } from '../../../stores/ToastStore'
import { useSightStore } from '../../../stores/SightStore'
import { useLoaderStore } from '../../../stores/LoaderStore'
import router from '../../../routes'
import { catchError, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'


export default {
    name: 'SightShow',
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        } 
    },
    data() {
        return {
            sight: [],
        }
    },
    methods: {
        ...mapActions(useSightStore, ['getSightForIds']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        getSight() {
            this.openLoaderFullPage()
            this.getSightForIds(this.$route.params.id).pipe(
                retry(3),
                delay(100),
                map(response => {
                    this.sight = response.data
                    console.log(response)
                    this.closeLoaderFullPage()
                }),
                catchError(err => {
                    console.log(err)
                    router.go(-1)
                    this.closeLoaderFullPage()
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$),
            ).subscribe()
        },
        backButton(){
            router.go(-1)
        }
    },
    mounted() {
        this.openLoaderFullPage()
        this.getSight()
    },
}
</script>
<style lang="">
    
</style>