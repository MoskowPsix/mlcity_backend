import { defineStore } from 'pinia'
import { useContentsFilterStore } from './ContentFilterStore';


// const authStore = useAuthStore()
export const useContentsQueryBuilderStore = defineStore('useContentsQueryBuilder', {
    
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
            this.searchText = useContentsFilterStore().getContentText()
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
                page: this.pageUsersForPageUsers
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
        searchText: string,
        pageContentsForPageContent: null
        
    }),
})