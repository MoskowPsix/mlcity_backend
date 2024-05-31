<template lang="">
    <!-- Кнопка назад -->
    <div
        class="button-menu ml:[-16%] fixed w-full top-[0%] bg-[#fff] dark:bg-gray-900 z-50"
    >
        <div class="flex m-[auto] dark:bg-gray-900">
            <button
                type="button"
                class="flex m-4 items-center rounded bg-gray-200/40 dark:bg-gray-800/80 max-h-12 min-w-1/12 max-w-[5rem] mr-3 px-4 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-gray-500 dark:text-gray-300/50 transition duration-150 ease-in-out hover:bg-gray-400/30 dark:hover:bg-gray-700/60 active:bg-gray-400/60 dark:active:bg-gray-700/80"
                @click.prevent="backButton()"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-6 h-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75"
                    />
                </svg>
            </button>
        </div>
    </div>
    <ModalHistoryContnet
        :contents="history"
        @next-page="nextHistoryList"
    />
    <div class="2xl:grid 2xl:grid-cols-2 mt-20">
        <div class="rounded-lg border dark:border-gray-700/50 m-1">
            <!-- Оригинал -->
            <div class="flex justify-center">
                <h1
                    class="font-[Montserrat-Bold] text-2xl mb-2 mt-2 text-[#3eb76c]"
                    >Текущий</h1
                >
            </div>
            <EventShow
                v-if="event.id"
                class="rounded-lg"
                :event_="event"
                :connect-state="eventSettings"
            />
            <SightShow
                v-if="sight.id"
                class="rounded-lg"
                :sight_="sight"
                :connect-state="sightSettings"
            />
        </div>

        <div class="rounded-lg border m-1 dark:border-gray-700/50">
            <!-- Жалкая пародия -->
            <!-- Кринж - Weqil -->
            <div class="flex justify-center fons">
                <h1
                    class="font-[Montserrat-Bold] text-2xl mb-2 mt-2 text-[#6393FF]"
                    >Изменённый</h1
                >
            </div>

            <EventShow
                v-if="event.id"
                class="rounded-lg"
                :event_="historyContent"
                :changed-fields="changedFields"
                :changed-place-ids="changedPlaceIds"
                :changed-price-ids="changedPriceIds"
                :changed-type-ids="changedTypeIds"
                :changed-seance-ids="changedSeanceIds"
                :connect-state="eventSettings"
            />
            <SightShow
                v-if="sight.id"
                class="rounded-lg"
                :sight_="historyContent"
                :changed-fields="changedFields"
                :changed-price-ids="changedPriceIds"
                :changed-type-ids="changedTypeIds"
                :connect-state="sightSettings"
            />
        </div>
    </div>

    <div
        v-if="historyStatus"
        class="flex justify-center min-w-[100%] mt-8"
    >
        <div>
            <ChangeStatus
                :edit-button="true"
                :status="historyStatus"
                @status-changed="statusChange"
            />
        </div>
    </div>
</template>
<script>
    import { mapActions } from 'pinia'
    import { useLoaderStore } from '../../../stores/LoaderStore'
    import { useHistoryContentStore } from '../../../stores/HistoryContentStore'
    import { useSightStore } from '../../../stores/SightStore'
    import { useEventStore } from '../../../stores/EventStore'
    import { useHistoryContentsQueryBuilderStore } from '../../../stores/HistoryContentQueryBuilderStore'
    import { catchError, map, retry, delay, takeUntil } from 'rxjs/operators'
    import { of, EMPTY, Subject } from 'rxjs'
    import { useToastStore } from '../../../stores/ToastStore'
    import { MessageContents } from '../../../enums/content_messages'

    import EventShow from '../../events/event_show/EventShow.vue'
    import SightShow from '../../sights/sight_show/SightShow.vue'
    import ChangeStatus from '../../../components/change_status/ChangeStatus.vue'
    import ModalHistoryContnet from '../../../components/modal_hostory_content/ModalHistoryContnet.vue'
    import router from '../../../routes'

    export default {
        name: 'ShowHistoryContent',
        components: {
            ModalHistoryContnet,
            EventShow,
            SightShow,
            ChangeStatus,
        },
        // props: {
        //     historyContent: Object
        // },
        setup() {
            const destroy$ = new Subject()
            return {
                destroy$,
            }
        },
        data() {
            return {
                historyContent: '',
                historyStatus: '',
                historyContentId: 0,
                historyContentIdContent: 0,
                event: {},
                sight: {},
                history: '',
                mergedSight: {},
                mergedEvent: {},
                changedFields: {},
                changedPlaceIds: [],
                changedSeanceIds: [],
                changedTypeIds: [],
                changedPriceIds: [],
                type_element: '',
                eventSettings: {
                    BackButton: false,
                    NameLine: true,
                    IdLine: true,
                    Gallery: true,
                    DescriptionsCard: true,
                    PricesCard: true,
                    TypeCard: true,
                    PlaceCard: true,
                    AuthorCard: true,
                    StatusCard: false,
                    EditButton: false,
                },
                sightSettings: {
                    BackButton: false,
                    NameLine: true,
                    IdLine: true,
                    Gallery: true,
                    PricesCard: true,
                    TypeCard: true,
                    AuthorCard: true,
                    StatusCard: false,
                    EditButton: false,
                },
            }
        },
        mounted() {
            this.getHistoryContent()
        },
        methods: {
            ...mapActions(useLoaderStore, [
                'openLoaderFullPage',
                'closeLoaderFullPage',
            ]),
            ...mapActions(useHistoryContentStore, [
                'getHistoryContentByIds',
                'changeStatus',
                'getHistoryByIdsEvent',
            ]),
            ...mapActions(useToastStore, ['showToast']),
            ...mapActions(useSightStore, ['getSightForIds']),
            ...mapActions(useEventStore, ['getEventForIds']),
            ...mapActions(useHistoryContentsQueryBuilderStore, [
                'queryBuilder',
                'setPageContentsForPageHistoryByIdsEvent',
            ]),
            backButton() {
                router.go(-1)
            },
            getElement(id) {
                let element = document.getElementById(id)
                if (element) {
                    element.scrollIntoView({
                        block: 'center',
                        behavior: 'smooth',
                    })
                    element.style.background = '#00ff01'
                    setInterval(() => {
                        element.style.background = ''
                    }, 2000)
                } else {
                    this.showToast('Элемента не существует', 'info')
                }
            },
            nextHistoryList(page) {
                this.setPageContentsForPageHistoryByIdsEvent(page)
                this.getHistoryList()
            },
            getHistoryList() {
                this.getHistoryByIdsEvent(
                    this.historyContentIdContent,
                    this.type_element,
                    this.queryBuilder('contentsForPageHistoryByIdsEvent'),
                )
                    .pipe(
                        map((response) => {
                            this.history = response
                        }),
                        catchError((err) => {
                            console.log(err)
                            return of(EMPTY)
                        }),
                        takeUntil(this.destroy$),
                    )
                    .subscribe()
            },
            clickSeance(seance) {
                this.getElement(
                    this.type_element +
                        '-' +
                        this.event.id +
                        '-place-' +
                        seance.place_id +
                        '-seance-' +
                        seance.seance_id,
                )
            },
            statusChange(status) {
                this.openLoaderFullPage()
                this.changeStatus(status, this.historyContentId)
                    .pipe(
                        map(() => {
                            this.showToast(
                                MessageContents.success_upd_status_content,
                                'success',
                            )
                            this.getHistoryContent()
                            this.closeLoaderFullPage()
                        }),
                        catchError((err) => {
                            399 < err.response.status &&
                            err.response.status < 500
                                ? this.showToast(
                                      MessageContents.warning_upd_status_content +
                                          ': ' +
                                          err.message,
                                      'warning',
                                  )
                                : null
                            499 < err.response.status &&
                            err.response.status < 600
                                ? this.showToast(
                                      MessageContents.error_upd_status_content +
                                          ': ' +
                                          err.message,
                                      'error',
                                  )
                                : null
                            console.log(err)
                            this.closeLoaderFullPage()
                            return of(EMPTY)
                        }),
                        takeUntil(this.destroy$),
                    )
                    .subscribe()
            },
            getHistoryContent() {
                this.getHistoryContentByIds(this.$route.params.id)
                    .pipe(
                        delay(100),
                        retry(2),
                        map((response) => {
                            this.historyContentIdContent =
                                response.data.historyContents.history_contentable_id
                            if (
                                response.data.historyContents
                                    .history_contentable_type ==
                                'App\\Models\\Sight'
                            ) {
                                this.historyContent =
                                    response.data.historyContents
                                this.type_element = 'sight'
                                this.historyContentId =
                                    response.data.historyContents.id
                                this.getSight(
                                    response.data.historyContents
                                        .history_contentable_id,
                                )
                                this.historyStatus =
                                    response.data.historyContents.statuses[0].name
                            } else if (
                                response.data.historyContents
                                    .history_contentable_type ==
                                'App\\Models\\Event'
                            ) {
                                this.historyContent =
                                    response.data.historyContents
                                this.type_element = 'event'
                                this.historyContentId =
                                    response.data.historyContents.id
                                this.getEvent(
                                    response.data.historyContents
                                        .history_contentable_id,
                                )
                                this.historyStatus =
                                    response.data.historyContents.statuses[0].name
                            } else {
                                this.showToast(
                                    MessageContents.warning_one_history_content_type,
                                    'warning',
                                )
                            }
                            this.closeLoaderFullPage()
                            this.getHistoryList()
                            console.log(this.historyContent)
                        }),
                        catchError((err) => {
                            console.log(err)
                            this.closeLoaderFullPage()
                            return of(EMPTY)
                        }),
                        takeUntil(this.destroy$),
                    )
                    .subscribe(() => {})
            },
            getSight(id) {
                this.getSightForIds(id)
                    .pipe(
                        map((data) => {
                            this.sight = data.data
                        }),
                    )
                    .subscribe(() => {
                        this.mergeSight()
                    })
            },
            getEvent(id) {
                this.getEventForIds(id)
                    .pipe(
                        map((data) => {
                            this.event = data.data
                        }),
                    )
                    .subscribe(() => {
                        this.mergeEvent()
                    })
            },
            mergeSight() {
                let sightPriceIds = []

                let mergedPriceIds = []
                let mergedTypeIds = []
                let forDeletePriceIds = []

                let historyContentTypeIds = []

                let sightAttr = Object.keys(this.sight)
                let historyContentAttr = Object.keys(this.historyContent)

                let mergedKeys = sightAttr.filter(
                    (key) =>
                        historyContentAttr.includes(key) &&
                        this.historyContent[key] != null &&
                        ![
                            'id',
                            'created_at',
                            'updated_at',
                            'statuses',
                            'user_id',
                        ].includes(key),
                )

                let changedSight = JSON.parse(JSON.stringify(this.sight))
                let changedFields_ = {}

                // типы истории на удаление
                this.historyContent.history_sight_types.forEach((type) => {
                    if (type.pivot.on_delete == true) {
                        forDeletePriceIds.push(type.id)
                    }
                })
                // Типы которые общие и не на удаление
                historyContentTypeIds.forEach((typeId, index) => {
                    if (
                        !this.sight.types.includes(typeId) &&
                        this.historyContent.history_sight_types[index].pivot
                            .on_delete == null
                    ) {
                        mergedTypeIds.push(typeId)
                    }
                })

                // Собираем ids цен
                this.sight.prices.forEach((price) => {
                    sightPriceIds.push(price.id)
                })

                // Собираем ids цен у истории
                this.historyContent.history_prices.forEach((price) => {
                    if (price.price_id == null) {
                        price.new = true
                        changedSight.prices.push(price)
                    } else if (
                        price.price_id != null &&
                        price.on_delete == true
                    ) {
                        forDeletePriceIds.push(price.price_id)
                    } else {
                        historyContentTypeIds.push(price.price_id)
                    }
                })

                // Ищем цены на удаление
                changedSight.prices.forEach((price) => {
                    if (forDeletePriceIds.includes(price.id)) {
                        price.delete = true
                    }
                })

                sightPriceIds.forEach((id, index) => {
                    let hpIndex = historyContentTypeIds.indexOf(id)

                    if (hpIndex != -1) {
                        mergedPriceIds.push(id)

                        let sightPriceKeys = Object.keys(
                            changedSight.price[index],
                        )
                        let historyContentPriceKeys = Object.keys(
                            this.historyContent.history_prices[hpIndex],
                        )

                        let mergedKeys = sightPriceKeys.filter(
                            (key) =>
                                historyContentPriceKeys.includes(key) &&
                                this.historyContent.history_prices[index][
                                    key
                                ] != null &&
                                !['id', 'created_at', 'updated_at'].includes(
                                    key,
                                ),
                        )

                        mergedKeys.forEach((key) => {
                            changedSight.prices[index][key] =
                                this.historyContent.history_prices[hpIndex][key]
                        })
                    }
                })

                mergedKeys.forEach((key) => {
                    changedSight[key] = this.historyContent[key]
                    changedFields_[key] = true
                })
                this.changedPriceIds = mergedPriceIds
                this.changedTypeIds = mergedTypeIds
                this.changedFields = changedFields_

                this.historyContent = changedSight
            },
            mergeEvent() {
                let eventAttr = Object.keys(this.event)
                let historyContentAttr = Object.keys(this.historyContent)
                let changedEvent = JSON.parse(JSON.stringify(this.event))

                let eventPlaceIds = []
                let eventPriceIds = []
                let eventSeanceIds = []

                let forDeleteTypeIds = []
                let forDeleteSeanceIds = []
                let forDeletePlaceIds = []
                let forDeletePriceIds = []

                let historyContentPlaceIds = []
                let historyContentPriceIds = []
                let historyContentSeanceIds = []
                let historyContentTypeIds = []

                let mergedPlaceIds = []
                let mergedSeanceIds = []
                let mergedPriceIds = []
                let mergedTypeIds = []

                // Собираем типы для обоих
                historyContentTypeIds =
                    this.historyContent.history_event_types.map((obj) => obj.id)

                // типы истории на удаление
                this.historyContent.history_event_types.forEach((type) => {
                    if (type.pivot.on_delete == true) {
                        forDeleteTypeIds.push(type.id)
                    }
                })
                // Типы которые общие и не на удаление
                historyContentTypeIds.forEach((typeId, index) => {
                    if (
                        !this.event.types.includes(typeId) &&
                        this.historyContent.history_event_types[index].pivot
                            .on_delete == null
                    ) {
                        mergedTypeIds.push(typeId)
                    }
                })

                // Собираем id плейсов и сеансов у события
                console.log(this.event)
                this.event.places_full.forEach((place) => {
                    eventPlaceIds.push(place.id)
                    let seanceIds = []
                    place.seances.forEach((seance) => {
                        seanceIds.push(seance.id)
                    })
                    eventSeanceIds.push(seanceIds)
                })

                // Собираем id плейсов и сеансов у истории
                this.historyContent.history_places.forEach((place) => {
                    if (place.place_id == null && place.on_delete == null) {
                        let p = place
                        p.new = true
                        changedEvent.places_full.push(p)
                    } else if (place.on_delete == true) {
                        forDeletePlaceIds.push(place.place_id)
                    } else {
                        historyContentPlaceIds.push(place.place_id)
                        let historySeanceIds = []
                        place.history_seances.forEach((seance) => {
                            if (seance.on_delete == true) {
                                forDeleteSeanceIds.push(seance.seance_id)
                            } else {
                                historySeanceIds.push(seance.seance_id)
                            }
                        })
                        historyContentSeanceIds.push(historySeanceIds)
                    }
                })

                // Собираем ids цен
                this.event.price.forEach((price) => {
                    eventPriceIds.push(price.id)
                })
                this.historyContent.history_prices.forEach((price) => {
                    if (price.price_id == null) {
                        price.new = true
                        changedEvent.price.push(price)
                    } else if (price.on_delete == true) {
                        // historyContentPriceIds.push(price.price_id)
                        forDeletePriceIds.push(price.price_id)
                    } else {
                        historyContentPriceIds.push(price.price_id)
                    }
                })

                // Ищем различия в ценах
                eventPriceIds.forEach((id, index) => {
                    let hpIndex = historyContentPriceIds.indexOf(id)

                    if (hpIndex != -1) {
                        mergedPriceIds.push(id)

                        let eventPriceKeys = Object.keys(
                            changedEvent.price[index],
                        )
                        let historyContentPriceKeys = Object.keys(
                            this.historyContent.history_prices[hpIndex],
                        )

                        let mergedKeys = eventPriceKeys.filter(
                            (key) =>
                                historyContentPriceKeys.includes(key) &&
                                this.historyContent.history_prices[index][
                                    key
                                ] != null &&
                                !['id', 'created_at', 'updated_at'].includes(
                                    key,
                                ),
                        )

                        mergedKeys.forEach((key) => {
                            changedEvent.price[index][key] =
                                this.historyContent.history_prices[hpIndex][key]
                        })
                    }
                })

                // Собираем id которые пересекаются в массивах
                eventPlaceIds.forEach((id, index) => {
                    let hpIndex = historyContentPlaceIds.indexOf(id)
                    if (hpIndex != -1) {
                        mergedPlaceIds.push(id)

                        // ищем новые сеансы
                        this.historyContent.history_places[
                            hpIndex
                        ].history_seances.forEach((seance) => {
                            if (seance.seance_id == null) {
                                let s = seance
                                s.new = true
                                changedEvent.places_full[index].seances.push(s)
                            }
                        })

                        // Зацепка для подставления найдена, она выше, через индекс ищем, потом достаем элементы и смотрим разницу и после перезаписываем
                        let eventPlaceKeys = Object.keys(
                            changedEvent.places_full[index],
                        )
                        let historyContentPlaceKeys = Object.keys(
                            this.historyContent.history_places[hpIndex],
                        )

                        let mergedKeys = eventPlaceKeys.filter(
                            (key) =>
                                historyContentPlaceKeys.includes(key) &&
                                this.historyContent.history_places[hpIndex][
                                    key
                                ] != null &&
                                ![
                                    'id',
                                    'sight_id',
                                    'updated_at',
                                    'created_at',
                                ].includes(key),
                        )

                        // сливаем свойства которые различаются
                        mergedKeys.forEach((key) => {
                            changedEvent.places_full[index][key] =
                                this.historyContent.history_places[hpIndex][key]
                        })

                        eventSeanceIds[index].forEach((id, indexS) => {
                            historyContentSeanceIds[hpIndex].forEach(
                                (idH, indexH) => {
                                    if (id == idH) {
                                        mergedSeanceIds.push(id)
                                        let eventSeanceKeys = Object.keys(
                                            changedEvent.places_full[index]
                                                .seances[indexS],
                                        )
                                        let historySeanceKeys = Object.keys(
                                            this.historyContent.history_places[
                                                hpIndex
                                            ].history_seances[indexH],
                                        )

                                        let mergedKeys = eventSeanceKeys.filter(
                                            (key) =>
                                                historySeanceKeys.includes(
                                                    key,
                                                ) &&
                                                this.historyContent
                                                    .history_places[hpIndex]
                                                    .history_seances[indexH][
                                                    key
                                                ] != null &&
                                                [
                                                    'date_start',
                                                    'date_end',
                                                ].includes(key),
                                        )

                                        mergedKeys.forEach((key) => {
                                            changedEvent.places_full[
                                                index
                                            ].seances[indexS][key] =
                                                this.historyContent.history_places[
                                                    hpIndex
                                                ].history_seances[indexH][key]
                                        })
                                    }
                                },
                            )
                        })
                    }
                })
                // Ищем цены на удаление
                changedEvent.price.forEach((price) => {
                    if (forDeletePriceIds.includes(price.id)) {
                        price.delete = true
                    }
                })
                // Ищем сеансы или плейсы на удаление
                console.log(forDeletePlaceIds)
                changedEvent.places_full.forEach((place) => {
                    if (forDeletePlaceIds.includes(place.id)) {
                        place.delete = true
                    }
                    place.seances.forEach((seance) => {
                        if (forDeleteSeanceIds.includes(seance.id)) {
                            seance.delete = true
                        }
                    })
                })

                let mergedStandartAttr = eventAttr.filter(
                    (key) =>
                        historyContentAttr.includes(key) &&
                        this.historyContent[key] != null &&
                        ![
                            'id',
                            'created_at',
                            'updated_at',
                            'statuses',
                            'user_id',
                        ].includes(key),
                )

                mergedStandartAttr.forEach((key) => {
                    changedEvent[key] = this.historyContent[key]
                })

                this.changedPlaceIds = mergedPlaceIds
                this.changedSeanceIds = mergedSeanceIds
                this.changedPriceIds = mergedPriceIds
                this.changedTypeIds = mergedTypeIds
                this.changedFields = mergedStandartAttr

                this.historyContent.history_event_types.forEach((type) => {
                    if (forDeleteTypeIds.includes(type.id)) {
                        // смотрим если тип на удаление подсвечиваем его
                        let t = JSON.parse(JSON.stringify(type))
                        t.on_delete = true
                        changedEvent.types = changedEvent.types.filter(
                            (obj) => obj.id != t.id,
                        )
                        changedEvent.types.push(t)
                    } else if (
                        !forDeleteTypeIds.includes(type.id) &&
                        mergedTypeIds.includes(type.id)
                    ) {
                        changedEvent.types.push(type)
                    }
                })

                this.historyContent = changedEvent
            },
        },
    }
</script>
<style lang=""></style>
