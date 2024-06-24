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

            <!-- <div
                class="mb-[0.125rem] block min-h-[1.5rem] pl-7 border rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            >
                <input
                    id="checkboxDefault"
                    v-model="sightStatusLast"
                    :true-value="1"
                    :false-value="0"
                    class="relative float-left -ml-[0.5rem] mr-[6px] mt-[0.5rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                    type="checkbox"
                />
                <label
                    class="inline-block pl-[0.15rem] mt-[0.4rem] hover:cursor-pointer"
                    for="checkboxDefault"
                >
                    Последний статус
                </label>
            </div> -->
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
                } else if (value.length) {
                    this.setSightLocation('')
                    this.modalSearchLocation = false
                    this.locations = []
                }
            },
            sightName(name) {
                if (name.length > 3) {
                    this.setSightName(name)
                } else if (name == 0) {
                    this.setSightName(name)
                }
            },
            sightSponsor(sponsor) {
                if (sponsor.length > 3) {
                    this.setSightSponsor(sponsor)
                } else if (sponsor == 0) {
                    this.setSightSponsor(sponsor)
                }
            },
            sightText(text) {
                if (text.length > 3) {
                    this.setSightText(text)
                } else if (text == 0) {
                    this.setSightText(text)
                }
            },
            sightStatuses(status) {
                this.setSightStatuses(status)
            },
            sightStatusLast(status) {
                this.setSightStatusLast(status)
            },
            sightUser(user) {
                if (user.length > 3) {
                    this.setSightUser(user)
                } else if (user == 0) {
                    this.setSightUser(user)
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
                this.$refs.name.value = ''
                this.$refs.sponsor.value = ''
                this.$refs.text.value = ''
                this.$refs.user.value = ''
                this.$refs.location.value = ''
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
