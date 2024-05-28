<template lang="">
    <div>
        <div
            class="border dark:bg-gray-800 bg-gray-200 border-gray-300 dark:border-gray-700 shadow rounded grid 2xl:grid-cols-4 xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 xs:grid-cols-1 gap-6 p-6 dark:text-gray-300"
        >
            <input
                id="name"
                v-model.lazy="eventName"
                type="text"
                name="name"
                placeholder="Название"
                class="rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            />
            <input
                id="sponsor"
                v-model.lazy="eventSponsor"
                type="text"
                name="sponsor"
                placeholder="Спонсор мероприятия"
                class="rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            />
            <input
                id="text"
                v-model.lazy="eventText"
                type="text"
                name="text"
                placeholder="Поиск по тексту"
                class="rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            />
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
                    </select>
                    <label data-te-select-label-ref>статусы</label>
                </div>
            </div>
            <div
                class="mb-[0.125rem] block min-h-[1.5rem] pl-7 border rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            >
                <input
                    id="checkboxDefault"
                    v-model="eventStatusLast"
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
            </div>
            <input
                id="user"
                v-model.lazy="eventUser"
                type="text"
                name="user"
                placeholder="Имя или почта автора"
                class="rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"
            />
            <VueDatePicker
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
    import { mapActions } from 'pinia'
    import { catchError, map, takeUntil } from 'rxjs/operators'
    import { of, EMPTY, Subject } from 'rxjs'
    import { Select, initTE } from 'tw-elements'
    import VueDatePicker from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'
    import { useDark } from '@vueuse/core'

    export default {
        name: 'EventFilter',
        components: {
            VueDatePicker,
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
//
        watch: {
            locationText(value) {
                if (value.length >= 3) {
                    this.getLocation(value)
                } else if (value.length) {
                    this.setEventLocation('')
                    this.modalSearchLocation = false
                    this.locations = []
                }
            },
            eventName(name) {
                if (name.length > 3) {
                    this.setEventName(name)
                } else if (name == 0) {
                    this.setEventName(name)
                }
            },
            eventSponsor(sponsor) {
                if (sponsor.length > 3) {
                    this.setEventSponsor(sponsor)
                } else if (sponsor == 0) {
                    this.setEventSponsor(sponsor)
                }
            },
            eventDate(date) {
                if (date) {
                    this.setEventDate([date[0] + '~' + date[1]])
                } else {
                    this.setEventDate(['~'])
                }
            },
            eventText(text) {
                if (text.length > 3) {
                    this.setEventText(text)
                } else if (text == 0) {
                    this.setEventText(text)
                }
            },
            eventStatuses(status) {
                this.setEventStatuses(status)
            },
            eventStatusLast(status) {
                this.setEventStatusLast(status)
            },
            eventUser(user) {
                if (user.length > 3) {
                    this.setEventUser(user)
                } else if (user == 0) {
                    this.setEventUser(user)
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
