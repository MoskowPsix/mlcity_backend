<template lang="">
    <HistoryContentFilter class="m-1" />
    <HistoryContentTable
        v-if="contents.length"
        :contents="contents"
        class="m-1"
        @on-click="toHistoryContent"
    />
    <label
        v-if="!contents.length"
        class="h-[100%] w-[100%] text-center"
    >
        <h1 class="mt-64 text-5xl text-gray-500/50">Ничего не найдено</h1>
    </label>
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
</template>
<script>
    import { mapActions, mapState } from 'pinia'
    import { useLoaderStore } from '../../stores/LoaderStore'
    import { useHistoryContentStore } from '../../stores/HistoryContentStore'
    import { useHistoryContentsQueryBuilderStore } from '../../stores/HistoryContentQueryBuilderStore'
    import { useHistoryContentsFilterStore } from '../../stores/HistoryContentFilterStore'
    import {
        catchError,
        tap,
        map,
        retry,
        delay,
        takeUntil,
    } from 'rxjs/operators'
    import { of, EMPTY, Subject } from 'rxjs'
    import { useToastStore } from '../../stores/ToastStore'
    import { MessageContents } from '../../enums/content_messages'

    import router from '../../routes'
    import PaginateBar from '../../components/paginate_bar/PaginateBar.vue'
    import HistoryContentTable from '../../components/tables/history_content_table/HistoryContentTable.vue'
    import HistoryContentFilter from '../../components/filters/history_content_filter/HistoryContentFilter.vue'

    export default {
        name: 'HistoryContent',
        components: {
            PaginateBar,
            HistoryContentTable,
            HistoryContentFilter,
        },
        setup() {
            const destroy$ = new Subject()
            return {
                destroy$,
            }
        },
        data() {
            return {
                contents: [],
                total: 0,
                nextPage: null,
                backPage: null,
            }
        },
        computed: {
            ...mapState(useHistoryContentsFilterStore, [
                'contentName',
                'contentDate',
                'contentSponsor',
                'contentSearchText',
                'contentStatuses',
                'contentStatusLast',
                'contentUser',
            ]),
        },
        watch: {
            contentName() {
                this.getAllContents()
            },
            contentDate() {
                this.getAllContents()
            },
            contentSponsor() {
                this.getAllContents()
            },
            contentSearchText() {
                this.getAllContents()
            },
            contentStatuses() {
                this.getAllContents()
            },
            contentStatusLast() {
                this.getAllContents()
            },
            contentUser() {
                this.getAllContents()
            },
        },
        mounted() {
            this.getAllContents()
        },
        methods: {
            ...mapActions(useLoaderStore, [
                'openLoaderFullPage',
                'closeLoaderFullPage',
            ]),
            ...mapActions(useHistoryContentStore, ['getContents']),
            ...mapActions(useToastStore, ['showToast']),
            ...mapActions(useHistoryContentsQueryBuilderStore, [
                'queryBuilder',
                'setPageContentsForPageContents',
            ]),
            viewBackPage() {
                this.setPageContentsForPageContents(this.backPage)
                this.getAllContents()
            },
            viewNextPage() {
                this.setPageContentsForPageContents(this.nextPage)
                this.getAllContents()
            },
            getAllContents() {
                this.openLoaderFullPage()
                this.getContents(this.queryBuilder('contentsForPageContents'))
                    .pipe(
                        retry(3),
                        delay(100),
                        tap(() => {
                            this.closeLoaderFullPage()
                        }),
                        map((response) => {
                            if (response.data.historyContents.data.length) {
                                this.contents =
                                    response.data.historyContents.data
                                this.nextPage =
                                    response.data.historyContents.next_cursor
                                this.backPage =
                                    response.data.historyContents.prev_cursor
                            } else {
                                this.contents = []
                                this.showToast(
                                    MessageContents.info_content,
                                    'info',
                                )
                            }
                        }),
                        catchError((err) => {
                            console.log(err)
                            this.closeLoaderFullPage()
                            399 < err.response.status &&
                            err.response.status < 500
                                ? this.showToast(
                                      MessageContents.warning_content +
                                          ': ' +
                                          err.message,
                                      'warning',
                                  )
                                : null
                            499 < err.response.status &&
                            err.response.status < 600
                                ? this.showToast(
                                      MessageContents.error_content +
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
            toHistoryContent(content) {
                router.push({ path: `/edit/${content.id}` })
            },
        },
    }
</script>
<style lang=""></style>
