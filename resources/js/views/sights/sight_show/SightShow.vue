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
        <label v-if="!state" class="flex items-center w-8/12"><h1>Имя: {{sight.name}}</h1></label>
        <input v-if="state" class="text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50 w-2/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700rounded-lgp-2pl-1border-2m-0">
        <label class="flex items-center w-3/12"><h1>ID: {{sight.id}}</h1></label>
    </div>

    <div
        class="block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-gray-800">
        
        <div class="p-6">
            <div class="text-center">
                <CarouselGallery :files="sight.files" :wrightState="state" @onDeleteFile="deleteFiles" @onUpdateFile="updateFiles"></CarouselGallery>
                <button @click="state ? state = false: state = true" class="p-2 mx-auto bg-green-500 rounded-lg border border-green-300 text-green-100 mt-1 md-1">Редактировать файлы</button>
            </div>
            
            <div class="">
                <div v-if="!state">
                    <p class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50  inline-block">
                        {{ sight.name }}
                    </p>
                </div>
                <div v-if="state" class="flex mb-4 space-x-4">
                    <input class=" text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50 w-2/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700rounded-lgp-2pl-1border-2m-0" 
                     v-bind:value="sight.name">
                </div>
            </div>
            
            <div>
                <div class=" inline-block">
                    <p class="font-medium">Место проведение:   </p>
                    <div v-if="!state"  class="">
                        <h6 class="mb-6"> {{ sight.address }}</h6>
                    </div>
                </div>
                
                <div v-if="state" class="flex mb-4 space-x-4">
                    <input class=" text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50 w-3/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700rounded-lgp-2pl-1border-2m-0" 
                     v-bind:value="sight.address"
                     type="text">
                    
                </div>
            </div>

            <div>
                <p class="font-medium">Время проведения:</p>
                <div v-if="!state" >
                    <h6 class="mb-4" >{{ sight.work_time }}</h6>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <input class=" text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50 w-3/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700rounded-lgp-2pl-1border-2m-0" 
                     v-bind:value="sight.work_time"
                     type="text">
                      
                </div>
            </div>
            
            <div class="">
                <div v-if="!state">
                    <p class="font-medium">Описание:</p>

                    <p class="mb-4 text-base text-neutral-800 dark:text-neutral-200">
                        {{ sight.description }}
                    </p>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <textarea class=" text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50 w-5/6 border-sky-400/30 bg-indigo-50 dark:bg-gray-700rounded-lgp-2pl-1border-2m-0" 
                    v-bind:value="sight.description"
                    type="text"
                    rows=7>
                    </textarea>  
                </div> 
            </div>


            <div class="mb-4">
                <div class="font-medium">
                    <p>Типы события:</p>
                </div>
                
                <div v-if="!state">
                    <p v-for="type_s in sight.types">{{ type_s.name }}</p>
                </div>

                <div v-if="state" class="flex" v-for="(type_s, index) in sight.types" v-bind:key="index">
                    <input 
                    class=" text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50 w-1/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700rounded-lgp-2pl-1border-2m-0"
                    v-bind:value="sight.types[index].name"
                    type="text">  
                </div>

            </div>

            <div class="mb-4">
                <div class=" font-medium">Цены: </div>
                <div v-if="sightPriceCheck() && state==false">
                    <div class="flex space-x-8">
                        <div>
                            <p v-for="price in sight.prices">{{ price.cost_rub }}₽</p>
                        </div>
                        <div>
                            <p v-for="price in sight.prices">{{ price.descriptions }}</p>
                        </div>
                    </div>
                    
                </div>
                <div v-if="state">

                </div>
                <p v-if="sightPriceCheck()==false"> Цена не указанна!</p>

               
            </div>

            <div>
                <p class=" font-medium">Спонсор:</p>
                <div v-if="!state" class="">
                    <h6 class="mb-4 " >{{ sight.sponsor }}</h6>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <input class=" text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50 w-3/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700rounded-lgp-2pl-1border-2m-0" 
                     v-bind:value="sight.sponsor"
                     type="text">   
                </div>
            </div>

            <div class="mb-4">
                <label for="status" class="block mb-2  font-medium text-gray-900 dark:text-white">Статус</label>
                <select 
                id="statuses" 
                class=" bg-gray-50 border  border-gray-300  text-gray-900 text-sm rounded-lg  focus:ring-blue-500 focus:border-blue-500 block w-1/10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                "
                
                v-bind:value="status">
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
            data-te-ripple-color="light"
            v-on:click="saveChanges">
            Сохранить
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
            status: "",
            sightUpd: new FormData(),
            state: false,
            filesDel: [],
            filesUpd: [],
            
        }
    },
    methods: {
        ...mapActions(useSightStore, ['getSightForIds','saveSightHistory']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        getSight() {
            this.openLoaderFullPage()
            this.getSightForIds(this.$route.params.id).pipe(
                retry(3),
                delay(100),
                map(response => {
                    this.sight = response.data
                    this.status = this.sight.statuses[0].name
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
            



            let coin = this.sightChange.sightFiles.findIndex((item) => { 
                if (item.name == file.name ) {
                    return true
                }
            })
            this.sightChange.sightFiles[coin] = null
            
        },
        updateFiles(files) {
            
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
        

        sightPriceCheck(){
            return true
        },
        saveChanges(){
            let data = {
                id: this.sight.id,
                type: "Sight",
                history_content:{
                    
                }
            }
            if(this.sightChange.sightName != this.sight.name){
                data.history_content.name = this.sightChange.sightName
            }
            if(this.sightChange.sightAddress != this.sight.address){
                data.history_content.address = this.sightChange.sightAddress
            }
            if(this.sightChange.sightDesc != this.sight.descriptions){
                data.history_content.description = this.sightChange.sightDesc
            }
            if(this.sightChange.sightTime != this.sight.work_time){
                data.history_content.work_time = this.sightChange.sightTime
            }
            if(this.sightChange.sightStatus != "" && this.sightChange.sightStatus != this.sight.statuses[0].name){
                data.history_content.statuses = this.sightChange.sightStatus
            }

            for(let i = 0; i<this.sight.types.length; i++){
            }

            
            this.saveSightHistory(data).pipe(
                catchError(error => {
                    console.log(error)
                })
            ).subscribe(response => {
                console.log(response)
            })
            console.log(data)   
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