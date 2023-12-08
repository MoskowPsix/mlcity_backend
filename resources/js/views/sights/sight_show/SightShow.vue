<template lang="">
<div class="min-w-full min-h-full bg-gray-300 dark:bg-gray-900 p-1">
    <div class="flex items-center border rounded-lg bg-gray-50 dark:border-gray-700 dark:bg-gray-800/90 dark:text-gray-300 p-2 mb-2">
        <button
            @click.prevent="backButton()"
            type="button"
            data-te-ripple-init
            data-te-ripple-color="light"
            class="flex items-center rounded bg-primary min-w-1/12 max-w-2/12 mr-3 px-4 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
            </svg>
            <h1 class="flex items-center mr-1 ml-1">Назад</h1>
        </button>
        <label class="flex items-center w-8/12"><h1>Имя: {{sight.name}}</h1></label>
        <label class="flex items-center w-3/12"><h1>ID: {{sight.id}}</h1></label>
    </div>

    <div
        class="block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-gray-800">
        
        <div class="p-6">
            <div class="text-center">
                <CarouselGallery :files="sightChange.sightFiles" :wrightState="state" @onDeleteFile="deleteFiles" @onUpdateFile="updateFiles"></CarouselGallery>
                <button @click="state ? state = false: state = true" class="p-2 mx-auto bg-green-500 rounded-lg border border-green-300 text-green-100 mt-1 md-1">Редактировать файлы</button>
            </div>
            
            <div v-on:dblclick="changeSightNameState" class="">
                <div v-if="!sightChange.sightNameState">
                    <p class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50 hover:cursor-pointer inline-block">
                        {{ sightChange.sightName }}
                    </p>
                </div>
                <div v-if="sightChange.sightNameState" class="flex mb-4 space-x-4">
                    <input class=" 
                    text-xl 
                    font-medium 
                    leading-tight
                    text-neutral-800
                    dark:text-neutral-50 
                    w-2/4
                    border-sky-400/30
                    bg-indigo-50
                    dark:bg-gray-700
                    rounded-lg
                    p-2
                    pl-1
                    border-2
                    m-0" 
                     v-model="sightChange.sightName">
                    
                    <svg
                    v-on:click="changeSightNameState"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor" 
                    class="w-6 h-6 my-auto text-green-700 hover:cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>

                    <svg 
                    v-on:click="declineSightName"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor" 
                    class="w-6 h-6 my-auto text-rose-800 hover:cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>


                </div>
            </div>
            
            <div>
                <div class="hover:cursor-pointer inline-block">
                    <p class="font-medium">Место проведение:   </p>
                    <div v-if="!sightChange.sightAddressState" v-on:dblclick="changeSightAddressState" class="">
                        <h6 class="mb-6"> {{ sightChange.sightAddress }}</h6>
                    </div>
                </div>
                

                <div v-if="sightChange.sightAddressState" class="flex mb-4 space-x-4">
                    <input class=" 
                    text-xl 
                    font-medium 
                    leading-tight
                    text-neutral-800
                    dark:text-neutral-50 
                    w-3/4
                    border-sky-400/30
                    bg-indigo-50
                    dark:bg-gray-700
                    rounded-lg
                    p-2
                    pl-1
                    border-2
                    m-0" 
                     v-model="sightChange.sightAddress"
                     type="text">
                    
                    <svg
                    v-on:click="changeSightAddressState"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor" 
                    class="w-6 h-6 my-auto text-green-700 hover:cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>

                    <svg 
                    v-on:click="declineSightAddress"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor" 
                    class="w-6 h-6 my-auto text-rose-800 hover:cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                
            </div>
            <div>
                <p class="font-medium">Время проведения:</p>
                <div v-if="!sightChange.sightTimeState" class="hover:cursor-pointer" v-on:dblclick="changeSightTimeState">
                    <h6 class="mb-4" >{{ sightChange.sightTime }}</h6>
                </div>

                <div v-if="sightChange.sightTimeState" class="flex mb-4 space-x-4">
                    <input class=" 
                    text-xl 
                    font-medium 
                    leading-tight
                    text-neutral-800
                    dark:text-neutral-50 
                    w-3/4
                    border-sky-400/30
                    bg-indigo-50
                    dark:bg-gray-700
                    rounded-lg
                    p-2
                    pl-1
                    border-2
                    m-0" 
                     v-model="sightChange.sightTime"
                     type="text">
                    
                    <svg
                    v-on:click="changeSightTimeState"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor" 
                    class="w-6 h-6 my-auto text-green-700 hover:cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>

                    <svg 
                    v-on:click="declineSightTime"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor" 
                    class="w-6 h-6 my-auto text-rose-800 hover:cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                
            </div>
            
            <div class="hover:cursor-pointer">

                <div v-if="!sightChange.sightDescState" v-on:dblclick="changeSightDescState">
                    <p class="font-medium">Описание:</p>

                    <p class="mb-4 text-base text-neutral-800 dark:text-neutral-200">
                        {{ sightChange.sightDesc}}
                    </p>
                </div>

                <div v-if="sightChange.sightDescState" class="flex mb-4 space-x-4">
                    <textarea class=" 
                    text-xl 
                    font-medium 
                    leading-tight
                    text-neutral-800
                    dark:text-neutral-50 
                    w-5/6
                    border-sky-400/30
                    bg-indigo-50
                    dark:bg-gray-700
                    rounded-lg
                    p-2
                    pl-1
                    border-2
                    m-0" 
                    v-model="sightChange.sightDesc"
                    type="text"
                    rows=7>
                    </textarea>
                    
                    <svg
                    v-on:click="changeSightDescState"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor" 
                    class="w-6 h-6 my-auto text-green-700 hover:cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>

                    <svg 
                    v-on:click="declineSightDesc"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor" 
                    class="w-6 h-6 my-auto text-rose-800 hover:cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div> 
            </div>

            <div class="mb-4">
                <div class=" font-medium">Цены: </div>
                <div v-if="sightPriceCheck() && sightChange.sightPricesState==false" v-on:dblclick="sightChange.sightPricesState = !sightChange.sightPricesState">
                    <div class="flex space-x-8">
                        <div>
                            <p v-for="price in sightChange.sightPrices">{{ price.cost_rub }}₽</p>
                        </div>
                        <div>
                            <p v-for="price in sightChange.sightPrices">{{ price.descriptions }}</p>
                        </div>
                    </div>
                    
                </div>
                <div v-if="sightChange.sightPricesState">

                </div>
                <p v-if="sightPriceCheck()==false"> Цена не указанна!</p>

                <!-- <p v-for="price in sight.prices">{{ price.cost_rub }}</p> -->
            </div>

            <div>
                <p class=" font-medium">Спонсор:</p>
                <div v-if="!sightChange.sightSponsorState" class="hover:cursor-pointer" v-on:dblclick="changeSightSponsorState">
                    <h6 class="mb-4 " >{{ sightChange.sightSponsor }}</h6>
                </div>

                <div v-if="sightChange.sightSponsorState" class="flex mb-4 space-x-4">
                    <input class=" 
                    text-xl 
                    font-medium 
                    leading-tight
                    text-neutral-800
                    dark:text-neutral-50 
                    w-3/4
                    border-sky-400/30
                    bg-indigo-50
                    dark:bg-gray-700
                    rounded-lg
                    p-2
                    pl-1
                    border-2
                    m-0" 
                     v-model="sightChange.sightSponsor"
                     type="text">
                    
                    <svg
                    v-on:click="changeSightSponsorState"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor" 
                    class="w-6 h-6 my-auto text-green-700 hover:cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>

                    <svg 
                    v-on:click="declineSightSponsor"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor" 
                    class="w-6 h-6 my-auto text-rose-800 hover:cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>

            <div class="mb-4">
                <label for="status" class="block mb-2  font-medium text-gray-900 dark:text-white">Статус</label>
                <select 
                id="status" 
                class="
                bg-gray-50 border 
                border-gray-300 
                text-gray-900 
                text-sm rounded-lg 
                focus:ring-blue-500 focus:border-blue-500 
                block w-full p-2.5 
                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                v-model="sightChange.sightStatus">
                    <option value="Отказ">Отказ</option>
                    <option value="Опубликовано">Опубликовано</option>
                    <option value="Черновик">Черновик</option>
                    <option value="На модерации">На модерации</option>
                    <option value="В архиве">В архиве</option>
                </select>
            </div>
            
            <button
            type="button"
            class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
            data-te-ripple-init
            data-te-ripple-color="light">
            Button
            </button>
        </div>
    </div>
</div>
</template>
<script>
import { mapActions} from 'pinia'
import { useToastStore } from '../../../stores/ToastStore'
import { useSightStore } from '../../../stores/SightStore'
import { useLoaderStore } from '../../../stores/LoaderStore'
import {Ripple,initTE,Carousel} from "tw-elements"
import router from '../../../routes'
import { catchError, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'
import CarouselGallery from '../../../components/carousel_gallery/CarouselGallery.vue'

export default {
    name: 'SightShow',
    components:{
        CarouselGallery
    },
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        } 
    },
    data() {
        return {
            sight: [],
            sightChange:{
                sightAddress: "",
                sightAddressState: false,
            
                sightName: "",
                sightNameState: false,

                sightDesc: "",
                sightDescState: false,

                sightTime: "",
                sightTimeState: false,
                
                sightPrices: [],
                sightPricesState: false,

                sightSponsor: "",
                sightSponsorState: false,

                sightFiles: null,

                sightStatus: ""
            },
            state: true,
            filesDel: [],
            filesUpd: [],
            
        }
    },
    methods: {
        ...mapActions(useSightStore, ['getSightForIds']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        getSight() {
            this.openLoaderFullPage()
            this.getSightForIds(this.$route.params.id).pipe(
                retry(3),
                delay(100),
                map(response => {
                    this.sight = response.data
                    this.sightChange.sightPrices = this.sight.prices
                    this.sightChange.sightAddress = this.sight.address
                    this.sightChange.sightName = this.sight.name
                    this.sightChange.sightDesc = this.sight.description
                    this.sightChange.sightTime = this.sight.work_time
                    console.log(this.sightChange.sightPrices)
                    this.sightChange.sightSponsor = this.sight.sponsor
                    this.sight.statuses[0] ? this.sightChange.sightStatus = this.sight.statuses[0].name : null
                    this.sightChange.sightFiles = this.sight.files
                    console.log(response)
                    this.closeLoaderFullPage()
                }),
                catchError(err => {
                    console.log(err)
                    router.go(-1)
                    this.closeLoaderFullPage()
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$),
            ).subscribe()
        },
        backButton(){
            router.go(-1)
        },
        deleteFiles(file) {
            console.log(['delete', file])
            // console.log(this.event.files)



            let coin = this.sightChange.sightFiles.findIndex((item) => { 
                if (item.name == file.name ) {
                    return true
                }
            })
            this.sightChange.sightFiles[coin] = null
            // if (coin) {
            //     this.filesUpd[coin] = null
            // } else {
            //     this.filesDel.push(...file)
            // }
        },
        updateFiles(files) {
            // console.log(['update', files])
            files = Array.from(files)
            files.forEach(file => {
                let reader = new FileReader()
                reader.readAsDataURL(file)
                console.log(reader)
                reader.onload = () => {
                    this.sightChange.sightFiles.push({link: reader.result, name: file.name, size: file.size, type: file.type}) 
                }
                this.filesUpd.push(file)
            })
            console.log(this.filesUpd)
        },
        changeSightAddressState(){
            console.log("change state")
            this.sightChange.sightAddressState = !this.sightChange.sightAddressState
        },
        changeSightNameState(){
           
            this.sightChange.sightNameState = !this.sightChange.sightNameState
        },changeSightDescState(){
       
            this.sightChange.sightDescState = !this.sightChange.sightDescState
        },
        changeSightTimeState(){
           
            this.sightChange.sightTimeState = !this.sightChange.sightTimeState
        },
        changeSightSponsorState(){
            
            this.sightChange.sightSponsorState = !this.sightChange.sightSponsorState
        },
        declineSightName(){
            this.sightChange.sightNameState = !this.sightChange.sightNameState
            this.sightChange.sightName = this.sight.name
        },
        declineSightAddress(){
            this.sightChange.sightAddressState = !this.sightChange.sightAddressState
            console.log(this.sightChange.sightAddressState)
            this.sightChange.sightAddress = this.sight.address
        },
        declineSightDesc(){
            this.sightChange.sightDescState = !this.sightChange.sightDescState
            this.sightChange.sightDesc = this.sight.description 
        },
        declineSightTime(){
            this.sightChange.sightTimeState = !this.sightChange.sightTimeState
            this.sightChange.sightTime = this.sight.work_time 
        },
        declineSightPrice(){
            this.sightChange.sightPriceState = !this.sightChange.sightPriceState
        },
        declineSightSponsor(){
            this.sightChange.sightSponsorState = !this.sightChange.sightSponsorState
            this.sightChange.sightSponsor = this.sight.sponsor
        },

        sightPriceCheck(){
            if(this.sightChange.sightPrices.length>0){
                return true
            }
            else{
                return false
            }
        }
    },
    mounted() {
        initTE({ Ripple  }, { Carousel }, { allowReinits: true })
        this.openLoaderFullPage()
        this.getSight()
    },
}
</script>
<style lang="">
    
</style>