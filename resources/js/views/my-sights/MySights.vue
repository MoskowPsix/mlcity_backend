<template lang="">
    <div>
        <SightTable
            :sights="sights"
            class="m-1"
            @sight="clickSight"
        />
        <div
            v-if="nextPage || backPage"
            class="flex justify-center"
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
    import { useLoaderStore } from '../../stores/LoaderStore'
    import { useSightStore } from '../../stores/SightStore'
    import {
        catchError,
        tap,
        map,
        retry,
        delay,
        takeUntil,
    } from 'rxjs/operators'
    import { of, EMPTY, Subject } from 'rxjs'

    import PaginateBar from '../../components/paginate_bar/PaginateBar.vue'
    import SightTable from '../../components/tables/sight_table/SightTable.vue'
    import router from '../../routes'

    export default {
        name: 'MySight',
        components: {
            PaginateBar,
            SightTable,
        },
        setup() {
            const destroy$ = new Subject()
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
        methods: {
            ...mapActions(useToastStore, ['showToast']),
            ...mapActions(useLoaderStore, [
                'openLoaderFullPage',
                'closeLoaderFullPage',
            ]),
            ...mapActions(useSightStore, ['getSightsForAuthor']),
            getAllSightsForAuthor(page) {
                this.openLoaderFullPage()
                this.getSightsForAuthor({ page: page })
                    .pipe(
                        map((response) => {
                            this.closeLoaderFullPage()
                            if (response.data.sights.data.length) {
                                this.sights = response.data.sights.data
                            } else {
                                this.sights = []
                            }
                            this.nextPage = response.data.sights.next_cursor
                            this.backPage = response.data.sights.prev_cursor
                        }),
                        catchError((err) => {
                            this.closeLoaderFullPage()
                            console.log(err)
                            return of(EMPTY)
                        }),
                        takeUntil(this.destroy$),
                    )
                    .subscribe()
            },
            viewBackPage() {
                this.getAllSightsForAuthor(this.backPage)
            },
            viewNextPage() {
                this.getAllSightsForAuthor(this.nextPage)
            },
            clickSight(sight) {
                router.push({ path: `/sight/${sight.id}` })
            },
        },
        mounted() {
            this.getAllSightsForAuthor()
        },
    }
</script>
<style lang=""></style>
