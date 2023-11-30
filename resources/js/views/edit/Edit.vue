<template lang="">
    <div class="text-gray-300 p-2 border" v-for="content in contents">
        {{content.name}}
    </div>
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
    },
    computed: {

    },
    methods: {
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useContentStore, ['getContents']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useContentsQueryBuilderStore, ['queryBuilder']),
        viewBackPage() {

        },
        viewNextPage() {

        },
        getAllContents() {
            this.getContents(this.queryBuilder).pipe(
                retry(3),
                delay(100),
                // tap(()=> {this.closeLoaderFullPage()}),
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