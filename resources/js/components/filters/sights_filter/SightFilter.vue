<template lang="">
    <div>
        <div
            class="border dark:bg-gray-800 bg-gray-200 border-gray-300 dark:border-gray-700 shadow rounded grid 2xl:grid-cols-4 xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 xs:grid-cols-1 gap-6 p-6 dark:text-gray-300"
        >
            <input
                id="name"
                ref="name"
                v-model.lazy="sightName"
                type="text"
                name="name"
                placeholder="Название"
                class="rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            />
            <input
                id="sponsor"
                ref="sponsor"
                v-model.lazy="sightSponsor"
                type="text"
                name="sponsor"
                placeholder="Спонсор мероприятия"
                class="rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            />
            <input
                id="text"
                ref="text"
                v-model.lazy="sightText"
                type="text"
                name="text"
                placeholder="Поиск по тексту"
                class="rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            />
            <input
                id="user"
                ref="user"
                v-model.lazy="sightUser"
                type="text"
                name="user"
                placeholder="Имя или почта автора"
                class="rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            />
            <ClearButton @click="clearInput" />
            <div
                class="flex border p-1 rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            >
                <div>
                    <select
                        v-model="sightStatuses"
                        class="h-6"
                        data-te-select-init
                    >
                        <option
                            v-for="status in statuses"
                            :key="status.id"
                            :value="status.name"
                            >{{ status.name }}</option
                        >
                        <option :value="'Все'"> Все </option>
                    </select>
                    <label data-te-select-label-ref>статусы</label>
                </div>
            </div>
            <div class="">
                <input
                    id="location"
                    ref="location"
                    v-model="locationText"
                    type="text"
                    name="location"
                    placeholder="Поиск по городу"
                    class="w-full rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
                    @click="modalSearchLocation = true"
                />
                <transition name="slide-fade">
                    <div
                        v-if="
                            locations.length !== 0 &&
                            modalSearchLocation &&
                            locationText.length
                        "
                        class="mt-1 absolute max-w-[15%] z-50 p-2 border rounded-lg dark:border-gray-700 dark:bg-gray-900/80 dark:text-gray-300 bg-gray-300/80 border-gray-400/50"
                    >
                        <ul>
                            <li
                                v-for="location in locations"
                                :key="location.id"
                                class="line-clamp-3 hover:bg-gray-500/50 p-2 rounded-lg"
                                @click="setLocationClick(location)"
                            >
                                <label>
                                    <h2 class="text-sm">{{ location.name }}</h2>
                                    <p class="text-xs">{{
                                        location.location_parent.name
                                    }}</p>
                                </label>
                            </li>
                        </ul>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>
<script>
    import { useLocationStore } from '../../../stores/LocationStore'
    import { useSightFilterStore } from '../../../stores/SightFilterStore'
    import { useStatusStore } from '../../../stores/StatusStore'
    import { mapActions } from 'pinia'
    import { catchError, map, takeUntil } from 'rxjs/operators'
    import { of, EMPTY, Subject } from 'rxjs'
    import { Select, initTE } from 'tw-elements'
    import ClearButton from '../../../components/clear_button/ClearButton.vue'

    export default {
        name: 'SightFilter',
        components: {
            ClearButton,
        },
        setup() {
            const destroy$ = new Subject()
            return {
                destroy$,
            }
        },
        data() {
            return {
                sightName: this.getSightName(),
                sightSponsor: this.getSightSponsor(),
                sightText: this.getSightText(),
                sightStatuses: this.getSightStatuses(),
                sightStatusLast: this.getSightStatusLast(),
                sightUser: this.getSightUser(),
                statuses: [],
                allStatuses: [],
                locationText: '',
                modalSearchLocation: false,
                loaderModalSearchLocation: false,
                locations: [],
            }
        },
        watch: {
            locationText(value) {
                if (value.length >= 3) {
                    this.getLocation(value)
                    this.filterChangeTrue()
                } else if (value.length) {
                    this.setSightLocation('')
                    this.modalSearchLocation = false
                    this.locations = []
                    this.filterChangeTrue()
                }
            },
            sightName(name) {
                if (name.length > 3) {
                    this.setSightName(name)
                    this.filterChangeTrue()
                } else if (name == 0) {
                    this.setSightName(name)
                    this.filterChangeTrue()
                }
            },
            sightSponsor(sponsor) {
                if (sponsor.length > 3) {
                    this.setSightSponsor(sponsor)
                    this.filterChangeTrue()
                } else if (sponsor == 0) {
                    this.setSightSponsor(sponsor)
                    this.filterChangeTrue()
                }
            },
            sightText(text) {
                if (text.length > 3) {
                    this.setSightText(text)
                    this.filterChangeTrue()
                } else if (text == 0) {
                    this.setSightText(text)
                    this.filterChangeTrue()
                }
            },
            sightStatuses(status) {
                this.setSightStatuses(status)
                this.filterChangeTrue()
            },
            sightStatusLast(status) {
                this.setSightStatusLast(status)
                this.filterChangeTrue()
            },
            sightUser(user) {
                if (user.length > 3) {
                    this.setSightUser(user)
                    this.filterChangeTrue()
                } else if (user == 0) {
                    this.setSightUser(user)
                    this.filterChangeTrue()
                }
            },
        },
        created() {
            window.addEventListener('click', (e) => {
                if (!document.getElementById('location')?.contains(e.target)) {
                    this.modalSearchLocation = false
                }
            })
        },
        mounted() {
            initTE({ Select }, { allowReinits: true })
            this.getAllStatuses()
            this.getLocationForId()
        },
        methods: {
            ...mapActions(useSightFilterStore, [
                'getSightLocation',
                'setSightLocation',
                'setSightName',
                'getSightName',
                'setSightSponsor',
                'getSightSponsor',
                'setSightText',
                'getSightText',
                'setSightStatuses',
                'getSightStatuses',
                'setSightStatusLast',
                'getSightStatusLast',
                'setSightUser',
                'getSightUser',
                'clearFilters',
                'filterChangeTrue',
            ]),
            ...mapActions(useStatusStore, ['getStatuses']),
            ...mapActions(useLocationStore, [
                'getLocationsByName',
                'getLocationId',
            ]),
            getLocationForId() {
                if (this.getSightLocation()) {
                    this.getLocationId(this.getSightLocation())
                        .pipe(
                            catchError((err) => {
                                console.log(err)
                                return of(EMPTY)
                            }),
                        )
                        .subscribe((response) => {
                            this.locationText = response.data.location.name
                            this.modalSearchLocation = false
                        })
                }
            },
            clearInput() {
                this.sightName = ''
                this.sightSponsor = ''
                this.sightText = ''
                this.sightUser = ''
                this.locationText = ''
                this.clearFilters()
            },
            getLocation(name) {
                this.loaderModalSearchLocation = true
                this.getLocationsByName(name)
                    .pipe(
                        catchError((err) => {
                            console.log(err)
                            this.modalSearchLocation = false
                            return of(EMPTY)
                        }),
                    )
                    .subscribe((response) => {
                        if (response.data.locations.length) {
                            this.locations = response.data.locations
                            this.modalSearchLocation = true
                        } else {
                            this.locations = []
                            this.modalSearchLocation = false
                        }
                    })
            },
            setLocationClick(location) {
                this.setSightLocation(location.id)
                this.locationText = location.name
            },
            getAllStatuses() {
                this.getStatuses()
                    .pipe(
                        map((response) => {
                            if (response.data.statuses.length) {
                                this.statuses = response.data.statuses
                            } else {
                                this.statuses = []
                            }
                        }),
                        catchError((err) => {
                            console.log(err)
                            return of(EMPTY)
                        }),
                        takeUntil(this.destroy$),
                    )
                    .subscribe()
            },
        },
    }
</script>
<style lang=""></style>
