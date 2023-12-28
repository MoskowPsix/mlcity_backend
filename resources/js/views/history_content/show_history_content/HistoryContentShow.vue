<template lang="">
    <div class="flex flex-row rounded-lg h-auto border m-1" @click="getElement">
        <h1></h1>
        <!-- Общая информация -->
        {{event}} | {{sight}}

    </div>
    <div class="grid grid-cols-2">
        <div class="rounded-lg border m-1">
            <!-- Оригинал -->
            <EventShow v-if="event.id" class="rounded-lg" :id="event.id" :connectState="eventSettings"/>
            <SightShow v-if="sight.id" class="rounded-lg" :id="sight.id" :connectState="sightSettings"/>
        </div>
        <div class="rounded-lg border m-1">
            <!-- История -->
            <div class="flex flex-col min-w-full min-h-full bg-gray-300 dark:bg-gray-900 p-1 rounded-lg">
                <!-- Имя и id -->
                <div class="flex items-center border rounded-lg bg-gray-50 dark:border-gray-700 dark:bg-gray-800/90 dark:text-gray-300 p-2 mb-2">
                    <label @click="getElement(type_element+'-'+event.id+'-name')" v-if="historyContent.name" class="flex items-center w-8/12" :id="'event-'+event.id+'name'"><h1>Имя: {{historyContent.name}}</h1></label>
                    <label class="flex items-center w-3/12" :id="'event-'+event.id+'-id'"><h1>ID: {{historyContent.id}}</h1></label>
                </div>
                <!-- Галерея -->
                <CarouselGallery v-if="historyContent.history_files" :files="historyContent.history_files" :wrightState="false" class="mb-1"></CarouselGallery>
                <!-- Описание -->
                <div v-if="historyContent.sponsor || historyContent.description || historyContent.materials || historyContent.date_start || historyContent.date_end" class="flex flex-col border rounded-lg bg-gray-50 dark:border-gray-700 dark:bg-gray-800/90 dark:text-gray-300 p-2 mb-2">
                    <label @click="getElement(type_element+'-'+event.id+'-sponsor')">
                        <h1 class="text-xl font-medium dark:text-gray-300 mb-2">Спонсор</h1>
                        <p v-if="historyContent.sponsor" class="text-sm font-normal dark:text-gray-200 mb-2">{{historyContent.sponsor}}</p>
                    </label>
                    <label @click="getElement(type_element+'-'+event.id+'-description')">
                        <h1 class="text-xl font-medium dark:text-gray-300 mb-2">Описание</h1>
                        <p v-if="historyContent.description" class="text-sm font-normal dark:text-gray-200 mb-2" >{{historyContent.description}}</p>
                    </label>
                    <label @click="getElement(type_element+'-'+event.id+'-materials')">
                        <h1 class="text-xl font-medium dark:text-gray-300 mb-2">Материалы</h1>
                        <p v-if="historyContent.materials" class="text-sm font-normal dark:text-gray-200 mb-2">{{historyContent.materials}}</p>
                    </label>
                    <label @click="getElement(type_element+'-'+event.id+'-date_start')">
                        <h1 class="text-xl font-medium dark:text-gray-300 mb-2">Дата начала и конца</h1>
                        <p @click="getElement(type_element+'-'+event.id+'-date_start')" v-if="historyContent.date_start" class="text-sm font-normal dark:text-gray-200 mb-1">Начало: {{historyContent.date_start}}</p>
                        <p @click="getElement(type_element+'-'+event.id+'-date_end')" v-if="historyContent.date_end" class="text-sm font-normal dark:text-gray-200">Конец: {{historyContent.date_end}}</p>
                    </label>
                </div>

                <!-- Цены и типы -->
                <div v-if="historyContent.history_prices || historyContent.history_event_types || historyContent.history_sight_types" class="grid 2xl:grid-cols-2 xl:grid-cols-2 lg:grid-cols-2 ">
                    <!-- Цены -->
                    <div v-if="historyContent.history_prices" class="border 2xl:col-span-1 xl:col-span-1 rounded-lg w-full h-auto dark:bg-gray-800 dark:border-gray-700/70 p-2">
                        <label>
                            <h1 class="text-xl font-medium dark:text-gray-300 mb-1">Цены</h1>
                            <hr class="dark:border-gray-700/70">
                        </label>
                        <div @click="getElement(type_element+'-'+event.id+'-price-' + price.id)" v-for="price in historyContent.history_prices" class="flex flex-row mt-2">
                            <PriceSegment :price="price" :state="false"/>
                        </div>
                    </div>
                    <!-- Типы -->
                    <div v-if="historyContent.history_event_types || historyContent.history_sight_types" class="border 2xl:col-span-1 xl:col-span-1 rounded-lg w-full h-auto dark:bg-gray-800 dark:border-gray-700/70 p-2">
                        <label>
                            <h1 class="text-xl font-medium dark:text-gray-300 mb-1">Типы</h1>
                            <hr class="dark:border-gray-700/70">
                            <!-- Типы мероприятий -->
                            <div v-if="historyContent.history_event_types">
                                <h2 @click="getElement(type_element+'-'+event.id+'-type-' + etype.id)" v-for="etype in historyContent.history_event_types">
                                    <h1>{{etype.name}}</h1>
                                    <hr class="dark:border-gray-700/70">
                                </h2>
                            </div>
                            <!-- Типы мест -->
                            <div v-if="historyContent.history_sight_types">
                                <h2 v-for="etype in historyContent.history_sight_types">
                                    <h1>{{etype.name}}</h1>
                                    <hr class="dark:border-gray-700/70">
                                </h2>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Плэйсы, статус и автор -->
                <div v-if="historyContent.history_places" class="grid 2xl:grid-cols-1 xl:grid-cols-1 lg:grid-cols-1 md:grid-cols-1 sm:grid-cols-1 w-full p-1 ">
                    <!-- Плэйс -->
                    <div  v-if="historyContent.history_places" class="2xl:col-span-3 xl:col-span-1 lg:ol-span-1 mt-2 ">
                        <div class="border dark:bg-gray-800/50 dark:border-gray-700 p-1 rounded-lg">
                            <div @click="getElement(type_element+'-'+event.id+'-place-' + place.id)" v-for="place in historyContent.history_places" >
                                <PlacesListCard :stateUpd="false" :place="place" @onClickPlaceSeance="clickSeance" class="mt-2"/>
                            </div>
                        </div>
                    </div>
                    <!-- Статусы и автор -->
                    <div v-if="historyContent || historyContent.user" class="m-2 grid 2xl:grid-cols-1 xl:grid-cols-2 lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-2">
                        <!-- Автор -->
                        <div @click="getElement(type_element+'-'+event.id+'-author')" v-if="historyContent.user">
                            <AuthorMiniCard :author="historyContent.user"/>
                        </div>
                        <!-- Статусы -->
                        <div v-if="historyContent.statuses" class=" border rounded-lg p-2 mt-1 dark:border-gray-700/70 dark:bg-gray-800">
                            <ChangeStatus :editButton="true" :status="historyContent.statuses[0].name" @statusChanged="statusChange"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>
<script>
import { mapActions } from 'pinia'
import { useLoaderStore } from '../../../stores/LoaderStore'
import { useHistoryContentStore } from '../../../stores/HistoryContentStore'
import { useSightStore } from '../../../stores/SightStore'
import { useEventStore } from '../../../stores/EventStore'
import { catchError, tap, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'
import { useToastStore } from '../../../stores/ToastStore'
import { MessageContents } from '../../../enums/content_messages'

import EventShow from '../../events/event_show/EventShow.vue'
import SightShow from '../../sights/sight_show/SightShow.vue'
import CarouselGallery from '../../../components/carousel_gallery/CarouselGallery.vue'
import PriceSegment from '../../../components/price_segment/PriceSegment.vue'
import PlacesListCard from '../../../components/places_list_card/PlacesListCard.vue'
import AuthorMiniCard from '../../../components/author-mini-card/AuthorMiniCard.vue'
import ChangeStatus from '../../../components/change_status/ChangeStatus.vue'

export default {
    name: 'ShowHistoryContent',
    // props: {
    //     historyContent: Object
    // },
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        } 
    },
    data() {
        return {
            historyContent: '',
            event: {},
            sight: {},
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
                StatusCard: true,
                EditButton: false,
            },
            sightSettings:{
                BackButton: false,
                NameLine: true,
                IdLine: true,
                Gallery: true,
                PricesCard: true,
                TypeCard: true,
                AuthorCard: true,
                StatusCard: true,
                EditButton: false,
            }
        }
    },
    components: {
        EventShow,
        SightShow,
        CarouselGallery,
        PriceSegment,
        PlacesListCard,
        AuthorMiniCard,
        ChangeStatus
    },
    methods: {
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useHistoryContentStore, ['getHistoryContentByIds', 'changeStatus']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useSightStore, ['getSightForIds']),
        ...mapActions(useEventStore, ['getEventForIds']),
        getElement(id) {
            let element = document.getElementById(id)
            if (element) {
                element.scrollIntoView({block: 'center', behavior: 'smooth'})
                element.style.background = '#00ff01'
                setInterval(() => {element.style.background = ''}, 2000)
            } else {
                this.showToast('Элемента не существует', 'info')
            }
        },
        clickSeance(seance) {
            this.getElement(this.type_element+'-'+this.event.id+'-place-' + seance.place_id+ '-seance-'+seance.seance_id)
        },
        statusChange(status) {
            this.openLoaderFullPage()
            this.changeStatus(status, this.historyContent.id).pipe(
                map(response => {
                    this.showToast(MessageContents.success_upd_status_content, 'success')
                    this.getHistoryContent()
                }),
                catchError( err => {
                    399 < err.response.status && err.response.status < 500 ? this.showToast(MessageContents.warning_upd_status_content + ': ' + err.message, 'warning') : null
                    499 < err.response.status && err.response.status < 600 ? this.showToast(MessageContents.error_upd_status_content + ': ' + err.message, 'error') : null
                    console.log(err)
                    this.closeLoaderFullPage()
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$),
            ).subscribe()
        },
        getHistoryContent() {
            this.getHistoryContentByIds(this.$route.params.id).pipe(
                delay(100),
                retry(2),
                map(response => {
                    if (response.data.historyContents.history_contentable_type == 'App\\Models\\Sight') {
                        this.sight.id = response.data.historyContents.history_contentable_id
                        this.historyContent = response.data.historyContents
                        this.type_element = 'sight'
                    } else if (response.data.historyContents.history_contentable_type == 'App\\Models\\Event') {
                        this.event.id = response.data.historyContents.history_contentable_id
                        this.historyContent = response.data.historyContents
                        this.type_element = 'event'
                    } else {
                        this.showToast(MessageContents.warning_one_history_content_type, 'warning')
                    }
                }),
                catchError(err => {
                    console.log(err)
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$)
            ).subscribe()
        },
    },
    mounted() {
        this.getHistoryContent()
    },
}
</script>
<style lang="">
    
</style>