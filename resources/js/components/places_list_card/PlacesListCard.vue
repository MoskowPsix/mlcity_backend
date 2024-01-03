<template lang="">
    <div class="transition border dark:border-gray-700/80 p-2 rounded-lg w-full bg-gray-100 dark:bg-gray-800 active:dark:bg-gray-700 active:bg-gray-300 shadow-md">
        <div @click="state = !state" class=" transition flex flex-row justify-content-center active:dark:border-gray-600/80 active:scale-95">
            <label class="w-11/12 ml-2">
                <h1 :id="'event-'+eventId+'-place-' + place.id+ '-name'" v-if="place.location && place.location.name" class="dark:text-gray-200 text-xl font-medium">{{place.location.name}} | ID:{{place.id}}</h1>
                <p :id="'event-'+eventId+'-place-' + place.id+ '-address'" class="dark:text-gray-400 text-sm font-normal">{{place.address}}</p>
                <p :id="'event-'+eventId+'-place-' + place.id+ '-coords'" class="dark:text-gray-400 text-sm front-light"> {{place.latitude}} /  {{place.longitude}}</p>
                <p :id="'event-'+eventId+'-place-' + place.id+ '-sight'" class="dark:text-gray-400 text-sm front-light">ID Достопримечательности: {{place.sight_id ? place.sight_id : 'Нет'}}</p>
            </label>
            <div class="w-1/12 my-auto">
                <svg v-if="!state" class="my-auto mx-auto w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
                <svg v-if="state" class="my-auto mx-auto w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                </svg>
            </div>
        </div>
        <!-- <input v-if="state" type="text" name="address" id="" class="w-full"> -->
        <div v-if="state" class="flex flex-row mt-2 h-100">
            <div class="w-6/12 min-h-full">
                <div class="grid grid-cols-2" v-if="stateUpd">
                    <div>
                        <input :id="'event-'+eventId+'-place-' + place.id+ '-location-input'" v-if="stateUpd" @input="$event.target.value  ? onSearchLocation($event) : locationsList = []" placeholder="Найти город" type="text" name="location_search" id="location_search" class="m-1 w-[96%] border rounded-lg flex items-center dark:bg-gray-700/20 dark:border-gray-600/50">
                        <div class="relative top-0 h-40">
                            <div class="border rounded-lg dark:border-gray-700 border-gray-300 flex flex-col h-full m-1 w-[96%] overflow-y-scroll" id="journal-scroll">
                                <h1 v-if="!locationsList.length" class="my-auto mx-auto text-xl font-medium dark:text-gray-500 text-gray-400 text-center">Нет результатов</h1>
                                <div @click.prevent="setLocation(location)" class="p-1 border rounded-sm dark:border-gray-700 hover:dark:bg-gray-100/10" v-for="location in locationsList">
                                        <h1 class="text-gray-100 text-base">{{location.name}}</h1>
                                        <p class="text-xs dark:text-gray-300" v-if="location.location_parent">{{location.location_parent.name}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input :id="'event-'+eventId+'-place-' + place.id+ '-address-input'" v-if="stateUpd" v-model="place.address" placeholder="адрес" type="text" name="address_search" id="address_search" class="m-1 w-[96%] border rounded-lg flex items-center dark:bg-gray-700/20 dark:border-gray-600/50" readonly>
                    </div>
                </div>
                <MapCardOnlyRead :id="'event-'+eventId+'-place-' + place.id+ '-map'" v-if="!stateUpd && place.latitude && place.longitude"  :marker="place" :zoom="16" />
                <MapCardInteractive :id="'event-'+eventId+'-place-' + place.id+ '-map-input'" v-if="stateUpd" @onCoords="setCoords" @onAddress="setAddress" class="h-[47rem] mt-2" :marker="[place.latitude, place.longitude]" :zoom="16" />
            </div>
            <div class=" flex flex-col w-6/12 pl-1 h-full justify-items-center" >
                <RouterLink :id="'event-'+eventId+'-place-' + place.id+ '-sight-route'" v-if="place.sight_id && !stateUpd" :to="{name: 'sight', params: {id: place.sight_id}}" class="transition font-medium hover:bg-gray-300 text-blue-400 dark:text-blue-400 mx-auto hover:dark:bg-gray-700 p-1 rounded-lg">
                    Проходит в достопримечательноти c id: {{place.sight_id}}
                </RouterLink>
                <div v-if="stateUpd" class="flex flex-col justify-items-center">
                    <div class="w-full">
                        <input :id="'event-'+eventId+'-place-' + place.id+ '-sight-input'" @input="$event.target.value ? onSearchSight($event) : sightsList = []" placeholder="Имя достопримечательности" type="text" name="sight_id" id="sight_id" class="m-1 w-[96%] border rounded-lg flex items-center dark:bg-gray-700/20 dark:border-gray-600/50" require>
                        <div class="relative top-0 h-40">
                            <div class="border rounded-lg dark:border-gray-700 border-gray-300 flex flex-col h-full m-1 w-[96%] overflow-y-scroll" id="journal-scroll">
                                <h1 v-if="!sightsList.length" class="my-auto mx-auto text-xl font-medium dark:text-gray-500 text-gray-400 text-center">Нет результатов</h1>
                                <div v-if="sightsList.length" v-for="sight in sightsList"  @click="setSight(sight)" class="p-1 border rounded-sm dark:border-gray-700 hover:dark:bg-gray-100/10" >
                                    <h1 class="text-gray-100 text-base">{{sight.name}}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2">
                    <label class="p-1 mx-auto font-medium text-gray-700 dark:text-gray-300">Начало</label>
                    <label class="p-1 mx-auto font-medium text-gray-700 dark:text-gray-300">Конец</label>
                </div>
                <div v-if="place.seances" :id="'event-'+eventId+'-place-' + place.id+ '-seance'" class="overflow-y-auto  pl-1 pr-1 max-h-[40rem] border rounded-lg dark:border-gray-600 dark:bg-gray-900/40 bg-gray-300/50" id="journal-scroll">
                    <div v-if="place.seances.length && place.seances" v-for="(seance, index) in place.seances">
                        <SeancesListSegment :id="'event-'+eventId+'-place-' + place.id+ '-seance-'+ seance.id" v-if="seance && !seance.on_delete" :index="index" :seance="seance" :state="stateUpd" @onUpdSeance="setSeance"></SeancesListSegment>
                    </div>
                </div>
                <div v-if="place.history_seances" class="overflow-y-auto  pl-1 pr-1 max-h-[40rem] border rounded-lg dark:border-gray-600 dark:bg-gray-900/40 bg-gray-300/50" id="journal-scroll">
                    <div v-if="place.history_seances.length && place.history_seances" v-for="(seance, index) in place.history_seances">
                        <SeancesListSegment :seance="$helpers.OutputCurentTime.outputCurentTime(seance)"  @onClickSeance="clickSeance"></SeancesListSegment>
                    </div>
                </div>
                <div v-if="stateUpd" @click.prevent="addSeancePlace" class="transition border p-2 mt-2 rounded-lg font-medium text-center border-blue-500/70 text-blue-900 bg-blue-400 hover:bg-blue-400/70 hover:text-blue-900/70 dark:hover:border-blue-500/30 dark:border-blue-500/70 dark:text-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:hover:text-blue-400 hover:border-blue-500/30 active:scale-95 cursor-pointer">Добавить сеанс</div>
                <div v-if="stateUpd" @click.prevent="setDeletePlace" class="transition border p-2 mt-2 rounded-lg font-medium text-center border-red-500/70 text-red-900 bg-red-400 hover:bg-red-400/70 hover:text-red-900/70 dark:hover:border-red-500/30 dark:border-red-500/70 dark:text-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:hover:text-red-400 hover:border-red-500/30 active:scale-95 cursor-pointer">Удалить место проведение</div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions} from 'pinia'
import MapCardOnlyRead from '../map_card/map_card_only_read/MapCardOnlyRead.vue';
import SeancesListSegment from '../seances_list_card/SeancesListSegment.vue';
import MapCardInteractive from '../map_card/map_card_interactive/MapCardInteractive.vue';
import { catchError, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'
import { useLocationStore } from '../../stores/LocationStore'
import { useSightStore } from '../../stores/SightStore';


export default {
    name: 'PlaceListCard',
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        } 
    },
    data() {
        return {
            state: false,
            locationsList: [],
            sightsList: []
        }
    },
    props: {
        place: Object,
        stateUpd: {
            type: Boolean,
            default: false
        },
        index: {
            type: Number,
            default: null
        },
        eventId: {
            type: Number,
            default: null
        }
    },

    components: {
        MapCardOnlyRead,
        MapCardInteractive,
        SeancesListSegment,
    },
    methods: {   
        ...mapActions(useLocationStore, ['getLocationsByName']),
        ...mapActions(useSightStore, ['getSights']),
        clickSeance(seance) {
            this.$emit('onClickPlaceSeance', {
                seance_id: seance.id,
                place_id: this.place.id,
            })
        },
        setAddress(address) {
            this.$emit('onUpdPlace', {
                index: this.index,
                id: this.place.id,
                address: address,
            })
        },
        setLocation(location) {
            this.$emit('onUpdPlace', {
                index: this.index,
                id: this.place.id,
                latitude: location.latitude,
                longitude: location.longitude,
                location: location,
            })
        },
        setCoords(coords) {
            this.$emit('onUpdPlace', {
                index: this.index,
                id: this.place.id,
                latitude: coords[0],
                longitude: coords[1]
            })
        },
        setSight(sight) {
            this.$emit('onUpdPlace', {
                index: this.index,
                id: this.place.id,
                latitude: sight.latitude,
                longitude: sight.longitude,
                sight_id: sight.id,
            })
        },
        setSeance(seance) {
            this.$emit('onUpdPlace', {
                index: this.index,
                id: this.place.id,
                seances: [seance]
            })
        },
        setDeletePlace() {
            let place = this.place
            place.on_delete = true
            place.index = this.index
            this.$emit('onUpdPlace', place)
        },
        onSearchLocation(event) {
            let text = event.target.value
            if (text.length > 3) {
                this.getLocationsByName(text).pipe(
                map(response => {
                    this.locationsList = response.data.locations
                }),
                takeUntil(this.destroy$),
                catchError(err => {
                    return of(EMPTY)
                })
                ).subscribe(

                )
            } 
        },    
        onSearchSight(event) {
            let text = event.target.value
            if (text.length > 3) {
                const param = {
                    name: text,
                    location: this.place.location.id
                }
                this.getSights(param).pipe(
                    map(response => {
                        this.sightsList = response.data.sights.data
                    }),
                    catchError(err => {
                        this.sightsList = []
                        return of(EMPTY)
                    }),
                    takeUntil(this.destroy$),
                ).subscribe()
            }
        },
        addSeancePlace() {
            const newSeance = {
                id: 0,
                date_start: new Date().toISOString().split('~')[0].slice(0, 19).replace("T", ' '),
                date_end: new Date().toISOString().split('~')[0].slice(0, 19).replace("T", ' '),
                index: JSON.parse(JSON.stringify(this.place.seances.length))
            }
            this.place.seances.push({ ...newSeance })
            const seancesCopy = [JSON.parse(JSON.stringify(newSeance))];
            this.$emit('onUpdPlace', {
                index: this.index,
                id: this.place.id,
                seances: [...seancesCopy]
            });
        }
    },
    watch: {
     
    },
    mounted() {
    },
}
</script>
<style lang="">
    
</style>