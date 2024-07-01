import { defineStore } from 'pinia'
import { useSightFilterStore } from './SightFilterStore'

export const useSightQueryBuilderStore = defineStore('useSightQueryBuilder', {
    actions: {
        queryBuilder(func) {
            this.updateParams()

            switch (func) {
                case 'sightsForPageSights':
                    this.sightsForPageSights()
                    break
            }

            return this.queryParams
        },
        updateParams() {
            ;(this.name = useSightFilterStore().getSightName()),
                (this.sponsor = useSightFilterStore().getSightSponsor()),
                (this.searchText = useSightFilterStore().getSightText()),
                (this.statuses = useSightFilterStore().getSightStatuses()),
                (this.statusLast = useSightFilterStore().getSightStatusLast()),
                (this.user = useSightFilterStore().getSightUser()),
                (this.locationId = useSightFilterStore().getSightLocation())
        },
        sightsForPageSights() {
            this.queryParams = {
                name: this.name,
                sponsor: this.sponsor,
                searchText: this.searchText,
                statuses: this.statuses,
                statusesLast: true,
                user: this.user,
                page: this.pageSightsForPageSights,
                locationId: this.locationId,
                order: 'created_at,updated_at',
            }
        },
        setPageSightsForPageSights(page) {
            this.pageSightsForPageSights = page
        },
    },
    state: () => ({
        queryParams: [],
        name: null,
        sponsor: null,
        searchText: null,
        statuses: null,
        statusLast: null,
        user: null,
        locationId: null,
        pageSightsForPageSights: null,
    }),
})
