<template lang="">
    <div @click="state = !state" class="transition border dark:border-gray-700/80 p-2 rounded-lg w-full dark:bg-gray-800 active:dark:bg-gray-700 active:dark:border-gray-600/80 active:scale-95">
        <div class="flex flex-row justify-content-center mb-4">
            <label class="w-11/12 ml-2">
                <h1 class="dark:text-gray-200 text-xl">{{place.location.name}}</h1>
                <p class="dark:text-gray-400 text-sm">{{place.address}}</p>
            </label>
            <div class="w-1/12 my-auto">
                <svg class="my-auto mx-auto w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>

            </div>
        </div>
        <hr v-if="state"/>
        <div v-if="state" class="flex flex-row mt-2">
            <div class="w-1/2">
                <YandexMap :center="[place.latitude, place.longitude]" :marker="place"/>
            </div>
            <div class="w-1/2">

            </div>
        </div>
    </div>
</template>
<script>
import YandexMap from '../yandex-map/YandexMap.vue';


export default {
    name: 'PlaceListCard',
    data() {
        return {
            state: false
        }
    },
    props: {
        place: Object
    },
    components: {
        YandexMap
    },
    methods: {
        placeUpd() {

        }, 
        placeDel() {

        },
        seancesUpd() {

        },
        seancesDel() {

        } 
    },
}
</script>
<style lang="">
    
</style>