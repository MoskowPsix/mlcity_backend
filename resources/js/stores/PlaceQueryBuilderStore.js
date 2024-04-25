import { defineStore } from 'pinia'

export const usePlaceQueryBuilderStore = defineStore('usePlaceQueryBuilder', {
    actions: {
        queryBuilder(func) {
            // this.updateParams()

            switch (func) {
                case 'placeForPageEvent':
                    this.contentsForPageContents()
                    break
            }
            return this.queryParams
        },
        // updateParams() {
        // },
        // Страница ивента
        contentsForPageContents() {
            this.queryParams = {
                page: this.pagePlaceForPageEvent,
            }
        },
        // Страница ивента
        setPagePlaceForPageEvent(page) {
            this.pagePlaceForPageEvent = page
        },
    },
    state: () => ({
        queryParams: [],
        pagePlaceForPageEvent: '',
    }),
})
