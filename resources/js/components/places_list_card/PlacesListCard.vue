<template lang="">
    <div class="transition border dark:border-gray-700/80 p-2 rounded-lg w-full bg-gray-100 dark:bg-gray-800 active:dark:bg-gray-700 active:bg-gray-300 shadow-md">
        <div @click="state = !state" class=" transition flex flex-row justify-content-center active:dark:border-gray-600/80 active:scale-95">
            <label class="w-11/12 ml-2">
                <h1 class="dark:text-gray-200 text-xl font-medium">{{place.location.name}} | ID:{{place.id}}</h1>
                <p class="dark:text-gray-400 text-sm font-normal">{{place.address}}</p>
                <p class="dark:text-gray-400 text-sm front-light"> {{place.latitude}} /  {{place.longitude}}</p>
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
                    <input v-if="stateUpd" placeholder="Найти адрес" type="text" name="address_search" id="address_search" class="m-1 w-[96%] border rounded-lg flex items-center dark:bg-gray-700/20 dark:border-gray-600/50" require>
                </div>
                <!-- <MapCard class="min-h-full" :marker="place" :center="[place.latitude, place.longitude]" :zoom="16" /> -->
            </div>
            <div class=" flex flex-col  w-1/3 p-1 h-96 overflow-y-auto justify-items-center" id="journal-scroll" >
                <RouterLink v-if="place.sight_id && !stateUpd" :to="{name: 'sight', params: {id: place.sight_id}}" class="transition font-medium hover:bg-gray-300 text-blue-400 dark:text-blue-400 mx-auto hover:dark:bg-gray-700 p-1 rounded-lg">
                    Проходит в достопримечательноти c id: {{place.sight_id}}
                </RouterLink>
                <div class="flex flex-col justify-items-center">
                    <p class="my-auto">ID Досторимечательности</p>
                    <input v-if="stateUpd" placeholder="ID достопримечательности" type="number" name="sight_id" id="sight_id" class="border rounded-lg flex items-center dark:bg-gray-700/20 dark:border-gray-600/50" :value="place.sight_id" @input="event => text = event.target.value">
                </div>
                <div class="grid grid-cols-2">
                    <label class="p-1 mx-auto font-medium text-gray-700 dark:text-gray-300">Начало</label>
                    <label class="p-1 mx-auto font-medium text-gray-700 dark:text-gray-300">Конец</label>
                </div>
                <div v-if="place.seances.length" v-for="seance in place.seances">
                    <SeancesListSegment :seance="seance"></SeancesListSegment>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import MapCard from '../map_card/MapCard.vue';
import VueTailwindDatepicker from 'vue-tailwind-datepicker'
import SeancesListSegment from '../seances_list_card/SeancesListSegment.vue';


export default {
    name: 'PlaceListCard',
    data() {
        return {
            state: false,
            locationSearch: '',
            locationsList: [],
            addressSearch: '',
            addressList: []
        }
    },
    props: {
        place: Object,
        stateUpd: Boolean
    },
    components: {
        MapCard,
        VueTailwindDatepicker,
        SeancesListSegment
    },
    methods: {   
        onSearchAddress() {

        },
        onSearchLocation() {

        },    
        placeUpd() {

        }, 
        placeDel() {

        },
        seancesUpd() {

        },
        seancesDel() {

        } 
    },
    watch: {
        searchAddress(address) {
            if(address > 3){
                searchAddress()
            } else if(address = 3) {
                this.addressList = []
            }
        },
        searchLocation(location) {
            if(location > 3){
                searchAddress()
            } else if(location = 3) {
                this.locationList = []
            }
        },
    },
    mounted() {
        // this.state == null || this.state == undefined ? this.state = false : this.state = true
    },
}
</script>
<style lang="">
    
</style>