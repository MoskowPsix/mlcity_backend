<template lang="">
    <div class="transition border dark:border-gray-700/80 p-2 rounded-lg w-full bg-gray-100 dark:bg-gray-800 active:dark:bg-gray-700 active:bg-gray-300 shadow-md">
        <div @click="state = !state" class=" transition flex flex-row justify-content-center active:dark:border-gray-600/80 active:scale-95">
            <label class="w-11/12 ml-2">
                <h1 class="dark:text-gray-200 text-xl font-medium">{{place.location.name}} | ID:{{place.id}}</h1>
                <p class="dark:text-gray-400 text-sm font-normal">{{place.address}}</p>
                <p class="dark:text-gray-400 text-sm front-light"> {{place.latitude}} /  {{place.longitude}}</p>
                <p class="dark:text-gray-400 text-sm front-light">ID Достопримечательности: {{place.sight_id ? place.sight_id : 'Нет'}}</p>
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
            <div class="w-2/3 min-h-full">
                <div class="grid grid-cols-2" v-if="stateUpd">
                    <div>
                            <input v-if="stateUpd" @input="$event.target.value  ? onSearchLocation($event) : locationsList = []" placeholder="Найти город" type="text" name="location_search" id="location_search" class="m-1 w-[96%] border rounded-lg flex items-center dark:bg-gray-700/20 dark:border-gray-600/50"  require>
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
                        <input v-if="stateUpd" v-model="place.address" placeholder="адрес" type="text" name="address_search" id="address_search" class="m-1 w-[96%] border rounded-lg flex items-center dark:bg-gray-700/20 dark:border-gray-600/50" readonly>
                    </div>
                </div>
                <MapCardOnlyRead v-if="!stateUpd" class="h-[30rem]" :marker="place" :zoom="16" />
                <MapCardInteractive v-if="stateUpd" @onCoords="setCoords" @onAddress="setAddress" class="h-[30rem] mt-2" :marker="[place.latitude, place.longitude]" :zoom="16" />
            </div>
            <div class=" flex flex-col  w-1/3 pl-1 h-full justify-items-center" >
                <RouterLink v-if="place.sight_id && !stateUpd" :to="{name: 'sight', params: {id: place.sight_id}}" class="transition font-medium hover:bg-gray-300 text-blue-400 dark:text-blue-400 mx-auto hover:dark:bg-gray-700 p-1 rounded-lg">
                    Проходит в достопримечательноти c id: {{place.sight_id}}
                </RouterLink>
                <div v-if="stateUpd" class="flex flex-col justify-items-center">
                    <div class="w-full">
                        <input placeholder="Имя достопримечательности" type="text" name="sight_id" id="sight_id" class="m-1 w-[96%] border rounded-lg flex items-center dark:bg-gray-700/20 dark:border-gray-600/50" :value="place.sight_id" @input="event => text = event.target.value">
                        <div class="relative top-0 h-40">
                            <div class="border rounded-lg dark:border-gray-700 border-gray-300 flex flex-col h-full m-1 w-[96%] overflow-y-scroll" id="journal-scroll">
                                <h1 v-if="!sightList.length" class="my-auto mx-auto text-xl font-medium dark:text-gray-500 text-gray-400 text-center">Нет результатов</h1>
                                <div v-if="sightList.length" class="p-1 border rounded-sm dark:border-gray-700 hover:dark:bg-gray-100/10" >
                                    <h1 class="text-gray-100 text-base">Xnjjd</h1>
                                    <p class="text-xs dark:text-gray-300" >asdasd</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2">
                    <label class="p-1 mx-auto font-medium text-gray-700 dark:text-gray-300">Начало</label>
                    <label class="p-1 mx-auto font-medium text-gray-700 dark:text-gray-300">Конец</label>
                </div>
                <div class="overflow-y-auto max-h-[30rem]" id="journal-scroll">
                    <div v-if="place.seances.length" v-for="seance in place.seances">
                        <SeancesListSegment :seance="seance" :state="stateUpd"></SeancesListSegment>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions} from 'pinia'
import MapCardOnlyRead from '../map_card/map_card_only_read/MapCardOnlyRead.vue';
import VueTailwindDatepicker from 'vue-tailwind-datepicker'
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
            locationSearch: '',
            locationsList: [],
            sightSearch: '',
            sightList: []
        }
    },
    props: {
        place: Object,
        stateUpd: Boolean
    },
    components: {
        MapCardOnlyRead,
        MapCardInteractive,
        VueTailwindDatepicker,
        SeancesListSegment,
    },
    methods: {   
        ...mapActions(useLocationStore, ['getLocationsByName']),
        ...mapActions(useSightStore, ['getSight']),
        setAddress(address) {
            this.$emit('onUpdPlace', {
                id: this.place.id,
                address: address,
            })
        },
        setLocation(location) {

            this.$emit('onUpdPlace', {
                id: this.place.id,
                location: location,
            })
        },
        setCoords(coords) {
            this.$emit('onUpdPlace', {
                id: this.place.id,
                latitude: coords[0],
                longitude: coords[1]
            })
        },
        onSearchLocation(event) {
            let text = event.target.value
            if (text.length > 3) {
                this.getLocationsByName(text).pipe(
                map(response => {
                    this.locationsList = response.data.locations
                    console.log(this.locationsList)
                }),
                takeUntil(this.destroy$),
                catchError(err => {
                    return of(EMPTY)
                })
                ).subscribe(

                )
            } else {
                console.log('менее 3 символов')
            }
        },    
        getSightByName(text) {
            this
        }
    },
    watch: {
     
    },
    mounted() {
        // this.state == null || this.state == undefined ? this.state = false : this.state = true
    },
}
</script>
<style lang="">
    
</style>