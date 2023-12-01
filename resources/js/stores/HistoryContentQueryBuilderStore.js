import { defineStore } from 'pinia'
import { useContentsFilterStore } from './HistoryContentFilterStore';


// const authStore = useAuthStore()
export const useHistoryContentsQueryBuilderStore = defineStore('useHistoryContentsQueryBuilder', {
    
    actions: {
        queryBuilder(func) {
        this.updateParams()

        switch(func) {
            case 'contentsForPageContents':
                this.contentsForPageContents()
            break;
        }

        return this.queryParams
        },
        updateParams() {
            this.name = useContentsFilterStore().getContentName(),
            this.date = useContentsFilterStore().getContentDate(),
            this.sponsor = useContentsFilterStore().getContentSponsor(),
            this.searchText = useContentsFilterStore().getContentText(),
            this.statuses = useContentsFilterStore().getContentStatuses(),
            this.statusesLast = useContentsFilterStore().getContentStatusLast(),
            this.user = useContentsFilterStore().getContentUser()
        },
        contentsForPageContents() {
            let date = ['', '']
            if (this.date) {
                date = this.date.split('~')
            }
            
            this.queryParams = {
                name: this.name,
                dateStart: date[0],
                dateEnd: date[1],
                sponsor: this.sponsor,
                searchText: this.searchText,
                statuses: this.statuses,
                statusesLast: this.statusesLast,
                user: this.user,
                page: this.pageContentsForPageContent
            }
        },
        setPageContentsForPageContents(page) {
            this.pageContentsForPageContent = page
        },
    },
    state: () => ({
        queryParams: [],
        name: null,
        date: null,
        sponsor: null,
        searchText: null,
        statuses: null,
        statusesLast: null,
        user: null,
        pageContentsForPageContent: null
        
    }),
})