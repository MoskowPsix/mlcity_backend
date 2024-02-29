<template lang="">

    <div class=" transition border dark:border-gray-700/80  rounded-lg mt-12 p-2 dark:bg-gray-800 active:dark:bg-gray-700 active:bg-gray-300 shadow-md">

        <div v-if="stateUpd" @click.prevent="setDeletePlace" class="transition border p-2 ml-[auto] mr-[-1%]  mt-[-2rem] mb-[auto] rounded-lg font-medium  text-center border-none  bg-red-600 hover:bg-red-400/70 hover:text-red-900/70 flex justify-center dark:hover:border-red-500/30 dark:border-red-500/70 text-[#fff] dark:bg-red-600 dark:hover:bg-red-700 min-w-[4rem] max-w-[4rem] max-h-[2.2rem] dark:hover:text-red-400 hover:border-red-500/30 active:scale-95 cursor-pointer ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-4 h-4 ">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
        </div>

        <div @click.prevent="changeState" :id="'event-'+eventId+'-place-' + place.id+ '-button'" class=" transition flex flex-row justify-content-center active:dark:border-gray-600/80 active:scale-95">

            <label class="w-11/12 ml-2">
                <h1 :id="'event-'+eventId+'-place-' + place.id+ '-name'" v-if="place.location && place.location.name" class="dark:text-gray-200 text-xl font-medium">{{place.location.name}}</h1>
                <p v-if="place.address" :id="'event-'+eventId+'-place-' + place.id+ '-address'" class="dark:text-gray-400 text-sm font-normal">{{place.address}}</p>
                <p v-if="place.latitude && place.longitude" :id="'event-'+eventId+'-place-' + place.id+ '-coords'" class="dark:text-gray-400 text-sm front-light"> {{place.latitude}} /  {{place.longitude}}</p>
                <!-- <p v-if="place.sight_id" :id="'event-'+eventId+'-place-' + place.id+ '-sight'" class="dark:text-gray-400 text-sm front-light">ID Достопримечательности: {{place.sight_id ? place.sight_id : 'Нет'}}</p> -->
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
        <div v-if="state" class="m-[auto] lg:max-w-[80%] mt-8">
            <div class="">
                <MapCardOnlyRead :id="'event-'+eventId+'-place-' + place.id+ '-map'" v-if="!stateUpd && place.latitude && place.longitude" class="h-[20rem] mt-2" :marker="place" :zoom="16" />
                <MapCardInteractive :id="'event-'+eventId+'-place-' + place.id+ '-map-input'" v-if="stateUpd" @onCoords="setCoords" @onAddress="setAddress" class="h-[20rem] mt-2" :marker="[place.latitude, place.longitude]" :zoom="16" />
            </div>

            <div class=" flex flex-col  justify-items-center" >
                <RouterLink :id="'event-'+eventId+'-place-' + place.id+ '-sight-route'" v-if="place.sight_id && !stateUpd" :to="{name: 'sight', params: {id: place.sight_id}}" class="transition font-medium hover:bg-gray-300 text-blue-400 dark:text-blue-400 mx-auto hover:dark:bg-gray-700 p-1 rounded-lg">
                    Проходит в достопримечательноти c id: {{place.sight_id}}
                </RouterLink>
              <div class="pl-[10%] pr-[10%]">
                    <div v-if="place.seances && place.seances.length > 0" class="flex justify-between mt-8 w-[100%] ">

                        <label class="p-1  font-bold  text-gray-700 dark:text-gray-300">Начало</label>

                        <label class="p-1  font-bold  text-gray-700 dark:text-gray-300">Конец</label>
                </div>
              </div>


                <div v-if="place.seances" :id="'event-'+eventId+'-place-' + place.id+ '-seance'" class="   pl-1 pr-1  rounded-lg dark:border-gray-600 " id="journal-scroll">
                    <div v-if="place.seances.length && place.seances" v-for="(seance, index) in place.seances" :key="seance.id">
                        <SeancesListSegment :changedSeanceIds="this.$props.changedSeanceIds" :location="place.location" :id="'event-'+eventId+'-place-' + place.id+ '-seance-'+ seance.id" v-if="seance && !seance.on_delete" :index="index" :seance="seance" :state="stateUpd" @onUpdSeance="setSeance"></SeancesListSegment>
                    </div>
                </div>

                <div v-if="place.history_seances" class="overflow-y-auto  pl-1 pr-1 max-h-[40rem] border rounded-lg dark:border-gray-600 dark:bg-gray-900/40 bg-gray-300/50" id="journal-scroll">
                    <div v-if="place.history_seances.length && place.history_seances" v-for="(seance, index) in place.history_seances" :key="seance.id">
                        <SeancesListSegment :changedSeanceIds="this.$props.changedSeanceIds" :location="place.location" :seance="seance"  @onClickSeance="clickSeance"></SeancesListSegment>
                    </div>
                </div>
                <div v-if="stateUpd" @click.prevent="addSeancePlace" class="mt-4 mb-4 transition border p-2 ml-[auto] mr-8 min-w-[14rem] rounded-lg font-medium text-center text-[#fff] bg-[#4C81F7] hover:bg-[#6393FF]  dark:hover:border-blue-500/30 dark:border-blue-500/70 dark:text-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:hover:text-blue-400 hover:border-blue-500/30 active:scale-95 cursor-pointer max-w-[14rem]">Добавить сеанс</div>

            </div>
        </div>
    </div>
</template>
<script>
import { mapActions} from 'pinia'
import moment from "moment"
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
        },
        changedPlaceIds:{
            type: Array,
            default: null
        },
        changedSeanceIds:{
            type: Array,
            default: null
        }
    },

    components: {
        MapCardOnlyRead,
        MapCardInteractive,
        SeancesListSegment,
    },
    methods: {
        ...mapActions(useLocationStore, ['getLocationsByName', 'getLocationByCoords']),
        ...mapActions(useSightStore, ['getSights']),
        changeState() {
            this.state ? this.state = false : this.state = true
        },
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
            this.getLocationByCoords(coords).pipe(
                map(response => {
                    this.$emit('onUpdPlace', {
                        index: this.index,
                        id: this.place.id,
                        latitude: coords[0],
                        longitude: coords[1],
                        location: response.data.location,
                    })
                }),
                takeUntil(this.destroy$),
                catchError(err => {
                    console.log(err)
                    return of(EMPTY)
                })
            ).subscribe()
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
                // Здесь можно будет просто по идеи можно будет подогнать дату к нужному формату через toLocaleDateString()
                // подробнее https://stackoverflow.com/questions/3552461/how-do-i-format-a-date-in-javascript
                date_start: this.$helpers.OutputCurentTime.outputCurentTime(new Date(), this.place.location.time_zone),
                date_end: this.$helpers.OutputCurentTime.outputCurentTime(new Date(),  this.place.location.time_zone),
                index: JSON.parse(JSON.stringify(this.place.seances.length))
            }
            // console.log(this.place.location.time_zone)
            // this.place.seances.push({ ...newSeance })
            // const seancesCopy = newSeance;
            this.$emit('onUpdPlace', {
                index: JSON.parse(JSON.stringify(this.index)),
                id: JSON.parse(JSON.stringify(this.place.id)),
                seances: [JSON.parse(JSON.stringify(newSeance))]
            });
        }
    },
    mounted(){

    },
    watch: {
        // place(data) {
        //     console.log('place')
        // }
    }

}
</script>
<style lang="">

</style>
