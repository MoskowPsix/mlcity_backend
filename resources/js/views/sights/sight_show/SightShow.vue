<template lang="">
<div class="min-w-full min-h-full bg-gray-300 dark:bg-gray-900 p-1">
    <form enctype="multipart/form-data">
    <div class="flex items-center border rounded-lg bg-gray-50 dark:border-gray-700 dark:bg-gray-800/90 dark:text-gray-300 p-2 mb-2">
        <button
            @click.prevent="backButton()"
            type="button"
            data-te-ripple-init
            data-te-ripple-color="light"
            class="flex items-center rounded bg-primary min-w-1/12 max-w-2/12 mr-3 px-4 pb-2 pt-2.5 text-xs  uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
            </svg>
            <h1 class="flex items-center mr-1 ml-1">Назад</h1>
        </button>
    
        <label v-if="!state" class="flex items-center w-8/12"><h1>Имя: {{sight.name}}</h1></label>
        <input v-if="state" id="name" v-bind:value=sight.name @input="event => text = event.target.value" class="text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-2/4   dark:bg-gray-700rounded-lgp-2pl-1borderm-0">
        <label class="flex items-center w-3/12"><h1>ID: {{sight.id}}</h1></label>
    </div>

    <div
        class="block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-gray-800">
        
        <div class="p-6">
            <div class="text-center">
                <CarouselGallery :files="sight.files" :wrightState="state" @onDeleteFile="deleteFiles" @onUpdateFile="updateFiles"></CarouselGallery>
            </div>
            
            
            
            <div>
                <div class=" inline-block">
                    <p class="">Место проведение:   </p>
                    <div v-if="!state"  class="">
                        <h6 class="mb-6"> {{ sight.address }}</h6>
                    </div>
                </div>
                
                <div v-if="state" class="flex mb-4 space-x-4">
                    <input class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4   dark:bg-gray-700 rounded-lg p-2 pl-1 border m-0"
                     v-bind:value="sight.address"
                     id="address"
                     type="text"
                     @input="event => text = event.target.value">
                    
                </div>
            </div>

            <div>
                <p class="">Время проведения:</p>
                <div v-if="!state" >
                    <h6 class="mb-4" >{{ sight.work_time }}</h6>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <textarea class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4   dark:bg-gray-700 rounded-lg p-2 pl-1 border m-0"
                     v-bind:value="sight.work_time"
                     id="work_time"
                     type="text"
                     @input="event => text = event.target.value">
                    </textarea>
                </div>
            </div>
            
            <div class="">
                <p class="">Описание:</p>
                <div v-if="!state">
                    <p class="mb-4 text-base text-neutral-800 dark:text-neutral-200">
                        {{ sight.description }}
                    </p>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <textarea class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-full   dark:bg-gray-700 rounded-lg p-2 pl-1 border m-0"
                    v-bind:value="sight.description"
                    type="text"
                    id="description"
                    rows=7
                    @input="event => text = event.target.value">
                    </textarea>  
                </div> 
            </div>

            
           

            <div class="mb-4">
                <div class="">
                    <p>Типы события:</p>
                    <div v-for="stype in allTypes" class="space-y-2 border py-4" v-if="allTypes ">
                        <TypeList v-if="allTypes && sight.types != null" :allSTypes="stype" :enableState="state" :currentStypes="sight.types" @checked="addToCurrentTypes"/>
                    </div>
                    
                </div>
                
                
            </div>

            <div class="mb-4">
                <div class=" ">Цены: </div>
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
                <div v-if="state" class="grid 2xl:grid-cols-12 xl:grid-cols-12 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-1">
                    <div class="2xl:col-span-3 xl:col-span-5 lg:col-span-8 md:col-span-9 sm:col-span-1 rounded-lg bg-white p-6 shadow-lg dark:bg-neutral-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-8 text-emerald-600 ml-auto"
                        v-on:click="addToCurrentPrices()">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div v-for="(price, index) in sight.prices" class="flex items-center border rounded-lg p-2 mb-4" >
                            <!--  -->
                            <PriceSegment :price="price" :index="index" @onDelPrice="deleteFromCurrentPrices" @onUpdPrice="sightUpdPrice"/>
                        </div>
                        
                    </div>
                </div>
                <p v-if="sightPriceCheck()==false"> Цена не указанна!</p>

               
            </div>

            <div>
                <p class=" ">Спонсор:</p>
                <div v-if="!state" class="">
                    <h6 class="mb-4 " >{{ sight.sponsor }}</h6>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <input class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4   dark:bg-gray-700 rounded-lg p-2 pl-1 border m-0"
                     v-bind:value="sight.sponsor"
                     id="sponsor"
                     @input="event => text = event.target.value"
                     type="text">   
                </div>
            </div>

            <div>
                <p class=" ">Материалы:</p>
                <div v-if="!state" class="">
                    <h6 class="mb-4 " >{{ sight.materials }}</h6>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <input class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4   dark:bg-gray-700 rounded-lg p-2 pl-1 border m-0"
                     v-bind:value="sight.materials"
                     id="materials"
                     @input="event => text = event.target.value"
                     type="text">   
                </div>
            </div>

            
            <ChangeStatus :status="status" @statusChanged="statusChange" v-if="!state"/>
            
            
        </div>
    </div>

        <input v-if="state" @click="clickUpd($event)" class="absolute rounded-lg bottom-0 right-0 bg-green-600 m-5 p-2 z-50" type="button" value="Применить">
        <button @click="discardChanges()" v-if="state" class="absolute rounded-lg bottom-0 right-0 bg-red-600 m-5 mr-36 p-2 z-50">Отмена</button>
        <button @click="state= !state" v-if="!state" class="absolute rounded-lg bottom-0 right-0 bg-blue-600 m-5 p-2 z-50">Редактировать</button>
    </form>
</div>
</template>
<script>
import TypeList from '../../../components/types_list/TypeList.vue'
import { mapActions} from 'pinia'
import { useToastStore } from '../../../stores/ToastStore'
import { useSightStore } from '../../../stores/SightStore'
import { useLoaderStore } from '../../../stores/LoaderStore'
import {Ripple,initTE,Carousel} from "tw-elements"
import router from '../../../routes'
import { catchError, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'
import CarouselGallery from '../../../components/carousel_gallery/CarouselGallery.vue'
import { useTypeStore } from '../../../stores/TypeStore'
import ChangeStatus from '../../../components/change_status/ChangeStatus.vue'
import PriceSegment from '../../../components/price_segment/PriceSegment.vue'

export default {
    name: 'SightShow',
    components:{
        CarouselGallery,
        TypeList,
        PriceSegment,
        ChangeStatus
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
            allTypes: null,
            status: "",
            sightUpd: new FormData(),
            sightFiles: [],
            
            state: false,
            currentSightPrice:[],
            filesDel: [],
            filesUpd: [],
            typesDel: [],
            typesUpd: [],
            pricesDel:[],
            pricesUpd:[],
            

            
        }
    },
    methods: {
        ...mapActions(useSightStore, ['getSightForIds','saveSightHistory']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useTypeStore,['getTypes']),
        getSight() {
            this.openLoaderFullPage()
            this.getSightForIds(this.$route.params.id).pipe(
                retry(3),
                delay(100),
                map(response => {
                    this.sight = response.data
                    this.status = this.sight.statuses[0].name
                    this.currentSightPrice = JSON.parse(JSON.stringify(this.sight.prices))
                    console.log(response)
                    
                }),
                catchError(err => {
                    console.log(err)
                    // router.go(-1)
                    this.closeLoaderFullPage()
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$),
            ).subscribe()
        },
        getAllTypes(){
      
        this.getTypes().pipe(
          retry(3),
          delay(200),
          catchError(error => {
            console.log(err)
          })
        ).subscribe(response => {
          this.allTypes = response.data.types
          console.log(this.allTypes)
          this.closeLoaderFullPage()
        })
    },
        addToCurrentTypes(type){
            if (this.checkObjInArray(type, this.sight.types)){
                if (this.checkObjInArray(type, this.typesDel)){
                    this.typesDel = this.typesDel.filter(item => item.id !== type.id)
                }
                else{
                    this.typesDel.push({"id": type.id, "on_delete":true})
                }
                // this.sight.types = this.sight.types.filter(item => item.id !== type.id)
                
            }
            else{
                if(this.checkObjInArray(type, this.typesUpd)){
                    this.typesUpd = this.typesUpd.filter(item => item.id !== type.id)
                }
                else{
                    this.typesUpd.push({"id": type.id})
                }
            }
            // console.log(this.sight.types)
            
            console.log("на удаление", this.typesDel)
            console.log("на добавление", this.typesUpd)
        },

        deleteFromCurrentPrices(price){
            if (this.checkObjInArray(price, this.sight.prices)){
                this.sight.prices = this.sight.prices.filter(item => item.id !== price.id)
                if (price.id){
                    this.pricesDel.push({"id":price.id, "on_delete":true})
                }
            }
            
            console.log(this.pricesDel)
        },
        addToCurrentPrices(){
            this.sight.prices.push({"cost_rub":null, "descriptions":""})
        },
        checkObjInArray(obj, array){
            for (let i = 0; i<array.length; i++){
                if (array[i].id === obj.id){
                    return true
                }
            }
            return false
        },

        discardChanges(){
            this.state = this.state
            this.getSight()
        },

        backButton(){
            router.go(-1)
        },
        deleteFiles(file) {
            this.sight.files.find((item, index) => { 
                if (item.name == file.name ) {
                    let coin = this.filesUpd.find((itm, i) => { 
                        if (itm.name == file.name ) {
                            this.filesUpd.splice(i, 1)
                            return true
                        }
                    })
                    
                    coin ? null : this.filesDel.push({file_id:file.id})
                    this.sight.files.splice(index, 1)
                    return true
                }
            })
            
        },
        updateFiles(files) {
            
            files = Array.from(files)
            files.forEach(file => {
                let reader = new FileReader()
                reader.readAsDataURL(file)
                reader.onload = () => {
                    this.sight.files.push({link: reader.result, name: file.name, size: file.size, type: file.type}) 
                }
                this.filesUpd.push(file)
            })
            console.log(this.filesUpd)
        },
        

        sightPriceCheck(){
            return true
        },
        sightUpdPrice(price){
            if(this.checkObjInArray(price, this.pricesUpd)){
                let data
                let index

                for (let i=0; i<this.pricesUpd.length; i++){
                    if (this.pricesUpd[i].id === price.id){
                        data = this.pricesUpd[i]
                        index = i
                    }
                }

                console.log(data)

                if (price.descriptions){
                    this.pricesUpd[index].descriptions = price.descriptions
                }
                else if(price.cost_rub){
                    this.pricesUpd[index].cost_rub = price.cost_rub
                }
            }
            else{
                this.pricesUpd.push(price)
            }
            
        },
        statusChange(status){
            this.status = status
            console.log(status)
        },
        clickUpd(event) {
            // Передаём форму обработанную в масси в локальную переменную функции
            let mass = Object.entries(event.target.form)

            let historyData = {
                id: this.sight.id,
                type:"Sight",
                history_content: {

                }
            }

           
            
            // Перебираем массив и формируем форм дату
            mass.forEach(item => {
                switch(item[1].id) {
                    case('name'):
                    if (item[1].value != this.sight.name) {
                        console.log('new name value: ' + item[1].value)
                        historyData.history_content.name = item[1].value
                    }
                    break;
                    case('sponsor'):
                    if (item[1].value != this.sight.sponsor) {
                        console.log('new sponsor value: ' + item[1].value)
                        historyData.history_content.sponsor = item[1].value
                    }
                    break;
                    case('description'):
                    if (item[1].value != this.sight.description) {
                        console.log('new description value: ' + item[1].value)
                        historyData.history_content.description = item[1].value
                    }
                    break;
                    case('materials'):
                    if (item[1].value != this.sight.materials) {
                        console.log('new materials value: ' + item[1].value)
                        historyData.history_content.materials =  item[1].value
                    }
                    break;
                    case('work_time'):
                    if (item[1].value != this.sight.work_time) {
                        console.log('new work_time value: ' + item[1].value)
                        historyData.history_content.work_time = item[1].value
                    }
                    break;
                    case('address'):
                    if (item[1].value != this.sight.address) {
                        console.log('new address value: ' + item[1].value)
                        historyData.history_content.address = item[1].value
                    }
                    break;
                    
                }
            })
            if (this.filesDel.length > 0 || this.filesUpd.length > 0){
                historyData.history_content.history_files = []
                    // Перебираем и передаём фото на добавлений в форм дату
                this.filesUpd.forEach((item) => {
                    // console.log('upd' + item)
                    // this.sightUpd.append("file[]",item)
                    historyData.history_content.history_files.push(item)
                })
                // Перебираем, добавляем поле и передаём фото на удаление в форм дату
                this.filesDel.forEach((item) => {
                    // console.log('del' + item)
                    item.on_delete = true
                    historyData.history_content.history_files.push(item)
                })
            }
            

            if (this.typesDel.length != 0 || this.typesUpd.length != 0){
                historyData.history_content.history_types = []

                this.typesDel.forEach(item => {
                    historyData.history_content.history_types.push(item)
                })
                this.typesUpd.forEach(item => {
                    historyData.history_content.history_types.push(item)
                })
            }

            if (this.pricesDel.length > 0 || this.pricesUpd > 0){
                historyData.history_content.history_prices = []

                this.pricesUpd.forEach(item => {
                    historyData.history_content.history_prices.push(item)
                })

                this.pricesDel.forEach(item => {
                    historyData.history_content.history_prices.push(item)
                })    
            }

            if (this.status != this.sight.statuses[0].name){
                historyData.history_content.status = this.status
            }
            

            // this.sightUpd.append("history_data",JSON.stringify(historyData))
            

            // this.saveSightHistory(this.sightUpd).pipe().subscribe(response => {console.log(response)})
            this.saveSightHistory(historyData).pipe().subscribe(response => {console.log(response)})
            this.state = false
            console.log(historyData)
        }
    },
    mounted() {
        initTE({ Ripple  }, { Carousel }, { allowReinits: true })
        this.openLoaderFullPage()
        this.getSight()
        this.getAllTypes()
    },
}
</script>
<style lang="">
    
</style>