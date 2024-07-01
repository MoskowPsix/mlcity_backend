<template lang="">
    <div>
        <div
            class="border dark:bg-gray-800 bg-gray-200 border-gray-300 dark:border-gray-700 shadow rounded grid 2xl:grid-cols-4 xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 xs:grid-cols-1 gap-6 p-6 dark:text-gray-300"
        >
            <input
                id="name"
                ref="name"
                v-model.lazy="eventName"
                type="text"
                name="name"
                placeholder="Название"
                class="rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            />
            <input
                id="sponsor"
                ref="sponsor"
                v-model.lazy="eventSponsor"
                type="text"
                name="sponsor"
                placeholder="Спонсор мероприятия"
                class="rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            />
            <input
                id="text"
                ref="text"
                v-model.lazy="eventText"
                type="text"
                name="text"
                placeholder="Поиск по тексту"
                class="rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            />
            <input
                id="user"
                ref="user"
                v-model.lazy="eventUser"
                type="user"
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
                        v-model="eventStatuses"
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
            <VueDatePicker
                ref="date"
                v-model="eventDate"
                range
                model-type="yyyy-MM-dd HH:mm:ss"
                :class="
                    themeState
                        ? 'w-full h-full mt-1 dp_theme_dark'
                        : 'w-full h-full mt-1 dp_theme_light'
                "
                placeholder="Дата и время события"
            />
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
    import { useEventFilterStore } from '../../../stores/EventFilterStore'
    import { useStatusStore } from '../../../stores/StatusStore'
    import { mapActions, mapState } from 'pinia'
    import { catchError, map, takeUntil } from 'rxjs/operators'
    import { of, EMPTY, Subject } from 'rxjs'
    import { Select, initTE } from 'tw-elements'
    import ClearButton from '../../../components/clear_button/ClearButton.vue'
    import VueDatePicker from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'
    import { useDark } from '@vueuse/core'

    export default {
        name: 'EventFilter',
        components: {
            VueDatePicker,
            ClearButton,
        },
        away() {
            this.modalSearchLocation = false
        },
        setup() {
            const destroy$ = new Subject()
            const themeState = useDark()
            return {
                themeState,
                destroy$,
            }
        },
        data() {
            return {
                eventName: this.getEventName(),
                eventSponsor: this.getEventSponsor(),
                eventText: this.getEventText(),
                eventDate: [
                    this.getEventDate()
                        .split('~')[0]
                        .slice(0, 19)
                        .replace('T', ' '),
                    this.getEventDate()
                        .split('~')[1]
                        .slice(0, 19)
                        .replace('T', ' '),
                ],
                eventStatuses: this.getEventStatuses(),
                eventStatusLast: this.getEventStatusLast(),
                eventUser: this.getEventUser(),
                statuses: [],
                allStatuses: [],
                locationText: '',
                modalSearchLocation: false,
                loaderModalSearchLocation: false,
                locations: [],
            }
        },
        computed: {
            ...mapState(useEventFilterStore, ['filterEventChange']),
        },
        watch: {
            locationText(value) {
                if (value.length >= 3) {
                    this.getLocation(value)
                    this.filterEventChange.next(true)
                } else if (value.length) {
                    this.setEventLocation('')
                    this.filterEventChange.next(true)
                    this.modalSearchLocation = false
                    this.locations = []
                }
            },
            eventName(name) {
                if (name.length > 3) {
                    this.setEventName(name)
                    this.filterEventChange.next(true)
                } else if (name == 0) {
                    this.setEventName(name)
                    this.filterEventChange.next(true)
                }
            },
            eventSponsor(sponsor) {
                if (sponsor.length > 3) {
                    this.setEventSponsor(sponsor)
                    this.filterEventChange.next(true)
                } else if (sponsor == 0) {
                    this.setEventSponsor(sponsor)
                    this.filterEventChange.next(true)
                }
            },
            eventDate(date) {
                if (date) {
                    this.setEventDate([date[0] + '~' + date[1]])
                    this.filterEventChange.next(true)
                } else {
                    this.setEventDate(['~'])
                    this.filterEventChange.next(true)
                }
            },
            eventText(text) {
                if (text.length > 3) {
                    this.setEventText(text)
                    this.filterEventChange.next(true)
                } else if (text == 0) {
                    this.setEventText(text)
                    this.filterEventChange.next(true)
                }
            },
            eventStatuses(status) {
                this.setEventStatuses(status)
                this.filterEventChange.next(true)
            },
            eventStatusLast(status) {
                this.setEventStatusLast(status)
                this.filterEventChange.next(true)
            },
            eventUser(user) {
                if (user.length > 3) {
                    this.setEventUser(user)
                    this.filterEventChange.next(true)
                } else if (user == 0) {
                    this.setEventUser(user)
                    this.filterEventChange.next(true)
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
            ...mapActions(useEventFilterStore, [
                'setEventName',
                'getEventName',
                'setEventSponsor',
                'getEventSponsor',
                'getEventDate',
                'setEventDate',
                'setEventText',
                'getEventText',
                'setEventStatuses',
                'getEventStatuses',
                'setEventStatusLast',
                'getEventStatusLast',
                'setEventUser',
                'getEventUser',
                'setEventLocation',
                'getEventLocation',
            ]),
            ...mapActions(useStatusStore, ['getStatuses']),
            ...mapActions(useLocationStore, [
                'getLocationsByName',
                'getLocationId',
            ]),
            getLocationForId() {
                if (this.getEventLocation()) {
                    this.getLocationId(this.getEventLocation())
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
                this.setEventLocation(location.id)
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
<style>
    /* Светлый стиль datepicker */
    .dp_theme_light {
        --dp-background-color: #fff;
        --dp-text-color: #212121;
        --dp-hover-color: #f3f3f3;
        --dp-hover-text-color: #212121;
        --dp-hover-icon-color: #959595;
        --dp-primary-color: #1976d2;
        --dp-primary-disabled-color: #6bacea;
        --dp-primary-text-color: #f8f5f5;
        --dp-secondary-color: #c0c4cc;
        --dp-border-color: #ddd;
        --dp-menu-border-color: #ddd;
        --dp-border-color-hover: #aaaeb7;
        --dp-disabled-color: #f6f6f6;
        --dp-scroll-bar-background: #f3f3f3;
        --dp-scroll-bar-color: #959595;
        --dp-success-color: #76d275;
        --dp-success-color-disabled: #a3d9b1;
        --dp-icon-color: #959595;
        --dp-danger-color: #ff6f60;
        --dp-marker-color: #ff6f60;
        --dp-tooltip-color: #fafafa;
        --dp-disabled-color-text: #8e8e8e;
        --dp-highlight-color: rgb(25 118 210 / 10%);
        --dp-range-between-dates-background-color: var(
            --dp-hover-color,
            #f3f3f3
        );
        --dp-range-between-dates-text-color: var(
            --dp-hover-text-color,
            #212121
        );
        --dp-range-between-border-color: var(--dp-hover-color, #f3f3f3);
    }
    /* Тёмный стиль datepicker */
    .dp_theme_dark {
        --dp-background-color: #2b3444;
        --dp-text-color: #fff;
        --dp-hover-color: #484848;
        --dp-hover-text-color: #fff;
        --dp-hover-icon-color: #959595;
        --dp-primary-color: #005cb2;
        --dp-primary-disabled-color: #61a8ea;
        --dp-primary-text-color: #fff;
        --dp-secondary-color: #a9a9a9;
        --dp-border-color: #323c4c;
        --dp-menu-border-color: #2d2d2d;
        --dp-border-color-hover: #aaaeb7;
        --dp-disabled-color: #737373;
        --dp-disabled-color-text: #d0d0d0;
        --dp-scroll-bar-background: #212121;
        --dp-scroll-bar-color: #484848;
        --dp-success-color: #00701a;
        --dp-success-color-disabled: #428f59;
        --dp-icon-color: #959595;
        --dp-danger-color: #e53935;
        --dp-marker-color: #e53935;
        --dp-tooltip-color: #3e3e3e;
        --dp-highlight-color: rgb(0 92 178 / 20%);
        --dp-range-between-dates-background-color: var(
            --dp-hover-color,
            #484848
        );
        --dp-range-between-dates-text-color: var(--dp-hover-text-color, #fff);
        --dp-range-between-border-color: var(--dp-hover-color, #fff);
    }
    .slide-fade-enter-active {
        transition: all 0.3s ease-out;
    }

    .slide-fade-leave-active {
        transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
    }

    .slide-fade-enter-from,
    .slide-fade-leave-to {
        transform: translateX(20px);
        opacity: 0;
    }
</style>
