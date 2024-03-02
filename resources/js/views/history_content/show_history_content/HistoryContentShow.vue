<template lang="">

    <div class="min[1366]:grid min[1366px]:grid-cols-2">
        <div class="rounded-lg border ">
            <!-- Оригинал -->
            <div class="flex justify-center">
                <h1 class=" font-[Montserrat-Bold] text-2xl mb-2 mt-2 text-[#3eb76c]" >Текущий</h1>
            </div>
            <EventShow v-if="event.id" class="rounded-lg"  :event_="event" :connectState="eventSettings"/>
            <SightShow v-if="sight.id" class="rounded-lg" :sight_="sight" :connectState="sightSettings"/>
        </div>

        <div class="rounded-lg border mt-8 md:mt-0 ">
            <!-- Жалкая пародия -->
            <!-- Кринж - Weqil -->
            <div class="flex justify-center fons">
                <h1 class=" font-[Montserrat-Bold] text-2xl mb-2 mt-2 text-[#6393FF]">Изменённый</h1>
            </div>

            <EventShow v-if="event.id" class="rounded-lg" :event_="historyContent" :changedFields="changedFields" :changedPlaceIds="changedPlaceIds" :changedPriceIds="changedPriceIds" :changedTypeIds="changedTypeIds" :changedSeanceIds="changedSeanceIds" :connectState="eventSettings"/>
            <SightShow v-if="sight.id" class="rounded-lg" :sight_="historyContent" :changedFields="changedFields" :changedPriceIds="changedPriceIds" :changedTypeIds="changedTypeIds" :connectState="sightSettings"/>
        </div>

    </div>

    <div class="flex justify-center min-w-[100%] mt-8" v-if="historyStatus">
        <div>
            <ChangeStatus :editButton="true" :status="historyStatus" @statusChanged="statusChange"/>
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
import { ref } from 'vue'
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
            historyStatus: '',
            event: {},
            sight: {},
            mergedSight: {},
            mergedEvent: {},
            changedFields: {},
            changedPlaceIds:[],
            changedSeanceIds:[],
            changedTypeIds: [],
            changedPriceIds:[],
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
            sightSettings:{
                BackButton: false,
                NameLine: true,
                IdLine: true,
                Gallery: true,
                PricesCard: true,
                TypeCard: true,
                AuthorCard: true,
                StatusCard: false,
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
                    this.closeLoaderFullPage()
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
                        this.historyContent = response.data.historyContents
                        this.type_element = 'sight'
                        this.getSight(response.data.historyContents.history_contentable_id)
                        this.historyStatus = response.data.historyContents.statuses[0].name
                    } else if (response.data.historyContents.history_contentable_type == 'App\\Models\\Event') {
                        this.historyContent = response.data.historyContents
                        this.type_element = 'event'
                        this.getEvent(response.data.historyContents.history_contentable_id)
                        this.historyStatus = response.data.historyContents.statuses[0].name
                    } else {
                        this.showToast(MessageContents.warning_one_history_content_type, 'warning')
                    }
                    this.closeLoaderFullPage()
                    console.log( this.historyContent)
                }),
                catchError(err => {
                    console.log(err)
                    this.closeLoaderFullPage()
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$)
            ).subscribe(()=>{

            })
        },
        getSight(id){
            this.getSightForIds(id).pipe(
                map((data) => {
                    this.sight = data.data
                    // console.log(this.sight)

                })
            ).subscribe(()=>{
                this.mergeSight()
            })
        },
        getEvent(id){
            this.getEventForIds(id).pipe(
                map(data => {
                    this.event = data.data
                })
            ).subscribe(()=>{
                this.mergeEvent()
            })
        },
        mergeSight(){
            let sightPriceIds = []

            let mergedPriceIds = []
            let mergedTypeIds = []

            let historyContentTypeIds = []

            let sightAttr = Object.keys(this.sight)
            let historyContentAttr = Object.keys(this.historyContent)

            let mergedKeys = sightAttr.filter(key => historyContentAttr.includes(key) && this.historyContent[key] != null && !["id","created_at","updated_at","statuses", "user_id"].includes(key))

            let changedSight = JSON.parse(JSON.stringify(this.sight))
            let changedFields_ = {}

            // типы истории на удаление
            this.historyContent.history_sight_types.forEach((type) => {
                if (type.pivot.on_delete == true){
                    forDeleteTypeIds.push(type.id)
                }
            })
            // Типы которые общие и не на удаление
            historyContentTypeIds.forEach((typeId, index) => {
                if(!this.sight.types.includes(typeId) && this.historyContent.history_sight_types[index].pivot.on_delete == null){
                    mergedTypeIds.push(typeId)
                }
            })

            this.sight.prices.forEach((price) => {
                sightPriceIds.push(price.id)
            })
            this.historyContent.history_prices.forEach((price) => {
                if(price.price_id == null){
                    price.new = true
                    changedSight.prices.push(price)
                }
                else{
                    historyContentPriceIds.push(price.price_id)
                }
            })

            sightPriceIds.forEach((id, index) => {
                let hpIndex = historyContentPriceIds.indexOf(id)

                if (hpIndex != -1){
                    mergedPriceIds.push(id)

                    let sightPriceKeys = Object.keys(changedSight.price[index])
                    let historyContentPriceKeys = Object.keys(this.historyContent.history_prices[hpIndex])


                    let mergedKeys = sightPriceKeys.filter(key =>
                    historyContentPriceKeys.includes(key)
                    &&
                    this.historyContent.history_prices[index][key] != null
                    &&
                    !['id','created_at','updated_at'].includes(key)
                    )

                    mergedKeys.forEach((key) => {
                        changedSight.prices[index][key] = this.historyContent.history_prices[hpIndex][key]
                    })
                }
            })

            mergedKeys.forEach(key => {
                changedSight[key] = this.historyContent[key]
                changedFields_[key] = true
            })
            this.changedPriceIds = mergedPriceIds
            this.changedTypeIds = mergedTypeIds
            this.changedFields = changedFields_


            this.historyContent = changedSight
        },
        mergeEvent(){

            console.log(this.historyContent)
            let eventAttr = Object.keys(this.event)
            let historyContentAttr = Object.keys(this.historyContent)
            let changedEvent = JSON.parse(JSON.stringify(this.event))

            let eventPlaceIds = []
            let eventPriceIds = []
            let eventSeanceIds = []

            let eventTypeIds = []
            let forDeleteTypeIds = []

            let historyContentPlaceIds = []
            let historyContentPriceIds = []
            let historyContentSeanceIds = []
            let historyContentTypeIds = []

            let mergedPlaceIds = []
            let mergedSeanceIds = []
            let mergedPriceIds = []
            let mergedTypeIds = []




            // Собираем типы для обоих
            historyContentTypeIds = this.historyContent.history_event_types.map(obj => obj.id)
            eventTypeIds = this.event.types.map(obj => obj.id)

            // типы истории на удаление
            this.historyContent.history_event_types.forEach((type) => {
                if (type.pivot.on_delete == true){
                    forDeleteTypeIds.push(type.id)
                }
            })
            // Типы которые общие и не на удаление
            historyContentTypeIds.forEach((typeId, index) => {
                if(!this.event.types.includes(typeId) && this.historyContent.history_event_types[index].pivot.on_delete == null){
                    mergedTypeIds.push(typeId)
                }
            })

            // Собираем id плейсов и сеансов у события
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

                if(place.place_id == null){
                    let p = place
                    p.new = true
                    changedEvent.places_full.push(p)
                }
                else{
                    historyContentPlaceIds.push(place.place_id)
                    let historySeanceIds = []
                    place.history_seances.forEach((seance) => {
                        historySeanceIds.push(seance.seance_id)
                    })
                    historyContentSeanceIds.push(historySeanceIds)
                }

            })

            // Собираем ids цен
            this.event.price.forEach((price) => {
                eventPriceIds.push(price.id)
            })
            this.historyContent.history_prices.forEach((price) => {
                if(price.price_id == null){
                    price.new = true
                    changedEvent.price.push(price)
                }
                else{
                    historyContentPriceIds.push(price.price_id)
                }
            })

            // Ищем различия в ценах
            eventPriceIds.forEach((id, index) => {
                let hpIndex = historyContentPriceIds.indexOf(id)

                if (hpIndex != -1){
                    mergedPriceIds.push(id)

                    let eventPriceKeys = Object.keys(changedEvent.price[index])
                    let historyContentPriceKeys = Object.keys(this.historyContent.history_prices[hpIndex])


                    let mergedKeys = eventPriceKeys.filter(key =>
                    historyContentPriceKeys.includes(key)
                    &&
                    this.historyContent.history_prices[index][key] != null
                    &&
                    !['id','created_at','updated_at'].includes(key)
                    )

                    mergedKeys.forEach((key) => {
                        changedEvent.price[index][key] = this.historyContent.history_prices[hpIndex][key]
                    })
                }
            })

            // Собираем id которые пересекаются в массивах
            eventPlaceIds.forEach((id, index) => {
                let hpIndex = historyContentPlaceIds.indexOf(id)
                if (hpIndex != -1){
                    mergedPlaceIds.push(id)

                    // ищем новые сеансы
                    this.historyContent.history_places[hpIndex].history_seances.forEach((seance) => {
                        if (seance.seance_id == null){
                            let s = seance
                            s.new = true
                            changedEvent.places_full[index].seances.push(s)
                        }
                    })

                    // Зацепка для подставления найдена, она выше, через индекс ищем, потом достаем элементы и смотрим разницу и после перезаписываем
                    let eventPlaceKeys = Object.keys(changedEvent.places_full[index])
                    let historyContentPlaceKeys = Object.keys(this.historyContent.history_places[hpIndex])

                    let mergedKeys = eventPlaceKeys.filter(key =>

                        historyContentPlaceKeys.includes(key)
                        &&
                        this.historyContent.history_places[hpIndex][key] != null
                        &&
                        !["id","sight_id","updated_at","created_at"].includes(key)

                    )

                    // сливаем свойства которые различаются
                    mergedKeys.forEach(key => {
                        changedEvent.places_full[index][key] = this.historyContent.history_places[hpIndex][key]
                    })

                    eventSeanceIds[index].forEach((id, indexS) => {
                        historyContentSeanceIds[hpIndex].forEach((idH, indexH) => {
                            if(id == idH){
                                mergedSeanceIds.push(id)
                                let eventSeanceKeys = Object.keys(changedEvent.places_full[index].seances[indexS])
                                let historySeanceKeys = Object.keys(this.historyContent.history_places[hpIndex].history_seances[indexH])

                                let mergedKeys = eventSeanceKeys.filter(key =>
                                historySeanceKeys.includes(key)
                                &&
                                this.historyContent.history_places[hpIndex].history_seances[indexH][key] != null
                                &&
                                ["date_start","date_end"].includes(key)
                                )

                                mergedKeys.forEach(key => {
                                    changedEvent.places_full[index].seances[indexS][key] = this.historyContent.history_places[hpIndex].history_seances[indexH][key]
                                })
                            }
                        })
                    })
                }
            })

            let mergedStandartAttr = eventAttr.filter(key => historyContentAttr.includes(key) && this.historyContent[key] != null && !["id","created_at","updated_at","statuses", "user_id"].includes(key))

            mergedStandartAttr.forEach(key => {
                changedEvent[key] = this.historyContent[key]
            })


            this.changedPlaceIds = mergedPlaceIds
            this.changedSeanceIds =mergedSeanceIds
            this.changedPriceIds = mergedPriceIds
            this.changedTypeIds = mergedTypeIds
            this.changedFields = mergedStandartAttr

            this.historyContent.history_event_types.forEach((type) => {
                if(forDeleteTypeIds.includes(type.id)){
                    let t = JSON.parse(JSON.stringify(type))
                    t.on_delete = true
                    console.log(t)
                    console.log(changedEvent.types)
                    changedEvent.types = changedEvent.types.filter(obj => obj.id != t.id)
                    changedEvent.types.push(t)


                }
                else if (!forDeleteTypeIds.includes(type.id) && mergedTypeIds.includes(type.id)){
                    changedEvent.types.push(type)
                }
            })


            console.log(changedEvent)

            this.historyContent = changedEvent
        }
    },
    mounted() {
        this.getHistoryContent()


    },
}
</script>
<style lang="">

</style>
