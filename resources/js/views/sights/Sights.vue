<template lang="">
    <SightFilter class="m-1"/>
    <SightTable :sights="sights" @sight="clickSight" class="m-1"/>
    <div class="flex justify-center " v-if="nextPage || backPage">
        <PaginateBar :nextPage="nextPage" :backPage="backPage" @onBackPage="viewBackPage()" @onNextPage="viewNextPage()" class="w-[70%]"/>
    </div>
</template>
<script>
import { mapActions, mapState } from 'pinia'
import { useToastStore } from '../../stores/ToastStore'
import { useLoaderStore } from '../../stores/LoaderStore'
import { useSightStore } from '../../stores/SightStore'
import { useSightQueryBuilderStore } from '../../stores/SightQueryBuilderStore'
import { useSightFilterStore } from '../../stores/SightFilterStore'
import { catchError, tap, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'


import PaginateBar from '../../components/paginate_bar/PaginateBar.vue'
import SightTable from '../../components/tables/sight_table/SightTable.vue'
import SightFilter from '../../components/filters/sights_filter/SightFilter.vue'
import router from '../../routes'


export default {
    name: 'Sights',
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        } 
    },
    data() {
        return {
           sights: [],
           nextPage: null,
           backPage: null,
        }
    },
    components: {
        PaginateBar,
        SightTable,
        SightFilter
    },
    computed: {
        ...mapState(useSightFilterStore, [
            'sightName',
            'sightSponsor',
            'sightSearchText',
            'sightStatuses',
            'sightStatusLast',
            'sightUser',
        ]),
    },
    methods: {
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useSightStore, ['getSights']),
        ...mapActions(useSightQueryBuilderStore, ['queryBuilder', 'setPageSightsForPageSights']),
        getAllSights() {
            this.openLoaderFullPage()
            this.getSights(this.queryBuilder('sightsForPageSights')).pipe(
                map(response => {
                    this.closeLoaderFullPage()
                    if(response.data.sights.data.length){
                        this.sights = response.data.sights.data
                    } else {
                        this.sights = []
                    }
                    this.nextPage = response.data.sights.next_cursor
                    this.backPage = response.data.sights.prev_cursor
                }),
                catchError(err => {
                    this.closeLoaderFullPage()
                    console.log(err)
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$)
            ).subscribe()
        },
        viewBackPage() {
            this.setPageSightsForPageSights(this.backPage)
            this.getAllSights()
        },
        viewNextPage() {
            this.setPageSightsForPageSights(this.nextPage)
            this.getAllSights()
        },
        clickSight(sight) {
            router.push({ path: `/sight/${sight.id}`})
        }
    },
    watch: {
        sightName() {
            this.getAllSights()
        },
        sightSponsor() {
            this.getAllSights()
        },
        sightSearchText() {
            this.getAllSights()
        },
        sightStatuses() {
            this.getAllSights()
        },
        sightStatusLast() {
            this.getAllSights()
        },
        sightUser() {
            this.getAllSights()
        }
    },
    mounted() {
        this.getAllSights()
    },
}
</script>
<style lang="">
    
</style>