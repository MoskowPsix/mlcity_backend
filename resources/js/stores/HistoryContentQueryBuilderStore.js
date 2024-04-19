import { defineStore } from 'pinia'
import { useHistoryContentsFilterStore } from './HistoryContentFilterStore'

// const authStore = useAuthStore()
export const useHistoryContentsQueryBuilderStore = defineStore(
    'useHistoryContentsQueryBuilder',
    {
        actions: {
            queryBuilder(func) {
                this.updateParams()

                switch (func) {
                    case 'contentsForPageContents':
                        this.contentsForPageContents()
                        break
                    case 'contentsForPageHistoryByIdsEvent':
                        this.contentsForPageHistoryByIdsEvent()
                        break
                }

                return this.queryParams
            },
            updateParams() {
                ;(this.name = useHistoryContentsFilterStore().getContentName()),
                    (this.date =
                        useHistoryContentsFilterStore().getContentDate()),
                    (this.sponsor =
                        useHistoryContentsFilterStore().getContentSponsor()),
                    (this.searchText =
                        useHistoryContentsFilterStore().getContentText()),
                    (this.statuses =
                        useHistoryContentsFilterStore().getContentStatuses()),
                    (this.statusLast =
                        useHistoryContentsFilterStore().getContentStatusLast()),
                    (this.user =
                        useHistoryContentsFilterStore().getContentUser())
            },
            // Страница контента установка параметров
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
                    statusesLast: this.statusLast,
                    user: this.user,
                    page: this.pageContentsForPageContent,
                }
            },
            // Страница истории изменения контента установка параметров
            contentsForPageHistoryByIdsEvent() {
                this.queryParams = {
                    page: this.pageContentsForPageHistoryByIdsEvent,
                    limit: this.limitContentsForPageHistoryByIdsEvent,
                }
            },
            // Страница истории изменения контента установка страницы
            setPageContentsForPageHistoryByIdsEvent(page) {
                this.pageContentsForPageHistoryByIdsEven = page
            },
            // Страница истории изменения контента установка лимита
            setLimitContentsForPageHistoryByIdsEvent(limit) {
                this.limitContentsForPageHistoryByIdsEven = limit
            },
            // Страница контента установка страницы
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
            statusLast: null,
            user: null,
            pageContentsForPageContent: null,
            pageContentsForPageHistoryByIdsEvent: null,
            limitContentsForPageHistoryByIdsEvent: null,
        }),
    },
)
