<template lang="">
    <ContentTable :contents="contents"/>
    <div class="flex justify-center ">
            <PaginateBar :nextPage="nextPage" :backPage="backPage" @onBackPage="viewBackPage()" @onNextPage="viewNextPage()" class="w-[70%]"/>
    </div>
</template>
<script>
import { mapActions, mapState } from 'pinia';
import { useLoaderStore } from '../../stores/LoaderStore';
import { useContentStore } from '../../stores/ContentStore';
import { useContentsQueryBuilderStore } from '../../stores/ContentQueryBuilderStore';
import { catchError, tap, map, retry, delay, takeUntil} from 'rxjs/operators';
import { of, EMPTY, Subject } from 'rxjs';
import { useToastStore } from '../../stores/ToastStore'
import { MessageContents } from '../../enums/content_messages'

import PaginateBar from '../../components/paginate_bar/PaginateBar.vue';
import ContentTable from '../../components/tables/content_table/ContentTable.vue';



export default {
    name: 'Edit',
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        }
    },
    data() {
        return {
            contents: null,
            total: 0,
            nextPage: null,
            backPage: null
        }
    },
    components: {
        PaginateBar,
        ContentTable
    },
    computed: {

    },
    methods: {
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useContentStore, ['getContents']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useContentsQueryBuilderStore, ['queryBuilder', 'setPageContentsForPageContents']),
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
            this.getContents(this.queryBuilder('contentsForPageContents')).pipe(
                retry(3),
                delay(100),
                tap(()=> {this.closeLoaderFullPage()}),
                map(response => {
                    console.log(response.data)
                    if(response.data.historyContents.data.length) {
                        this.contents = response.data.historyContents.data
                        this.nextPage = response.data.historyContents.next_cursor
                        this.backPage = response.data.historyContents.prev_cursor
                    } else {
                        this.contents = null
                        this.showToast(MessageContents.info_content, 'info')
                    }
                }),
                catchError(err => {
                    console.log(err)
                    399 < err.response.status && err.response.status < 500 ? this.showToast(MessageContents.warning_content + ': ' + err.message, 'warning') : null
                    499 < err.response.status && err.response.status < 600 ? this.showToast(MessageContents.error_content + ': ' + err.message, 'error') : null
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$)
            ).subscribe()
        },
        toNextPage() {

        },
        toBackPage() {

        }
    },
    mounted() {
        this.getAllContents()
    },
}
</script>
<style lang="">
    
</style>