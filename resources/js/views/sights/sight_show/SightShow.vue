<template lang="">
    <!-- <div>
        <button class="p-4 bg-red-500" @click="searchTypeOpen()">Popen</button>
        <button class="p-4 bg-red-500" @click="searchTypeClose()">Copen</button>

    </div> -->

    <ModalContainer/>
<div class="min-w-full min-h-full bg-gray-300 dark:bg-gray-900 p-1" :id="'sight-'+sight.id">
    <form enctype="multipart/form-data">
    <div v-if="connectState.IdLine || connectState.NameLine || connectState.BackButton" class="flex items-center border rounded-lg bg-gray-50 dark:border-gray-700 dark:bg-gray-800/90 dark:text-gray-300 p-2 mb-2">
        <button
            v-if="connectState.BackButton"
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

        <label v-if="!state && connectState.NameLine" class="flex items-center w-8/12" :id="'sight-'+sight.id+'-name'"><h1>Имя: {{sight.name}}</h1></label>
        <input v-if="state && connectState.NameLine" :id="'sight-'+sight.id+'-name-input'" v-bind:value=sight.name @input="event => text = event.target.value" class="text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-2/4   dark:bg-gray-700rounded-lgp-2pl-1borderm-0">
        <label class="flex items-center w-3/12" :id="'sight-'+sight.id+'-id'"><h1>ID: {{sight.id}}</h1></label>
    </div>

    <div
        class="block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-gray-800">

        <div class="p-6">
            <div class="text-center">
                <CarouselGallery :id="'sight-'+sight.id+'-gallery'" :files="sight.files" :wrightState="state" v-if="sight.files && connectState.Gallery" @onDeleteFile="deleteFiles" @onUpdateFile="updateFiles"></CarouselGallery>
            </div>



            <div>
                <div class=" inline-block">
                    <p class="">Место проведение:   </p>
                    <div v-if="!state"  class="">
                        <h6 :id="'sight-'+sight.id+'-address'" class="mb-6"> {{ sight.address }}</h6>
                    </div>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <input :id="'sight-'+sight.id+'-address-input'" class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4   dark:bg-gray-700 rounded-lg p-2 pl-1 border m-0"
                     v-bind:value="sight.address"
                     id="address"
                     type="text"
                     @input="event => text = event.target.value">

                </div>
            </div>

            <div>
                <p class="">Время проведения:</p>
                <div v-if="!state" >
                    <h6 :id="'sight-'+sight.id+'-work_time'" class="mb-4" >{{ sight.work_time }}</h6>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <textarea :id="'sight-'+sight.id+'-work_time-input'" class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4   dark:bg-gray-700 rounded-lg p-2 pl-1 border m-0"
                     v-bind:value="sight.work_time"
                     id="work_time"
                     type="text"
                     @input="event => text = event.target.value">
                    </textarea>
                </div>
            </div>

            <div class="" v-if="connectState.DescriptionsCard">
                <p class="">Описание:</p>
                <div v-if="!state">
                    <p :id="'sight-'+sight.id+'-description'" class="mb-4 text-base text-neutral-800 dark:text-neutral-200">
                        {{ sight.description }}
                    </p>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <textarea :id="'sight-'+sight.id+'-description-input'" class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-full   dark:bg-gray-700 rounded-lg p-2 pl-1 border m-0"
                    v-bind:value="sight.description"
                    type="text"
                    id="description"
                    rows=7
                    @input="event => text = event.target.value">
                    </textarea>
                </div>
            </div>




            <div :id="'sight-'+sight.id+'-type'" class="mb-4" v-if="connectState.TypeCard">
                <div class="">
                    <p>Типы события:</p>
                    <div class="space-x-2">
                        <a :id="'type-'+stype.stype_id" v-on:click.prevent="goToElement($event)" class="inline hover:text-blue-700 transition duration-700 w-auto ease-in-out hover:cursor-pointer" v-for="stype in sight.types">{{ stype.name }}</a>
                    </div>

                    <div  class="space-y-4 border py-4 tree" v-if="allTypes">
                        <TypeList :sightId="sight.id" v-for="stype in allTypes" v-if="allTypes && sight.types != null" :allSTypes="stype" :enableState="state" :currentStypes="sight.types" @checked="addToCurrentTypes"/>
                    </div>
                </div>
            </div>

            <div class="mb-4" >


                <div v-if="connectState.PricesCard && state==false && (sight.prices && sight.prices.length > 0)" class="grid 2xl:grid-cols-12 xl:grid-cols-12 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-1">
                    <div   :id="'sight-'+sight.id+'-price'" class="2xl:col-span-3 xl:col-span-7 lg:col-span-12 md:col-span-12 sm:col-span-12 rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-8 text-emerald-600 ml-auto"
                        v-on:click="addToCurrentPrices()" v-if="state">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div v-for="(price, index) in sight.prices" class="flex items-center  border rounded-lg p-2 mb-4" >
                            <PriceSegment :id="'sight-'+sight.id+'-price-'+price.id" :state="state" :price="price" :index="index" @onDelPrice="deleteFromCurrentPrices" @onUpdPrice=""/>
                        </div>
                    </div>
                </div>
                <div v-if="connectState.PricesCard && state" class="grid 2xl:grid-cols-12 xl:grid-cols-12 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-1">
                    <div   :id="'sight-'+sight.id+'-price'" class="2xl:col-span-3 xl:col-span-7 lg:col-span-12 md:col-span-12 sm:col-span-12 rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-8 text-emerald-600 ml-auto"
                        v-on:click="addToCurrentPrices()" v-if="state">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div v-for="(price, index) in sight.prices" class="flex items-center  border rounded-lg p-2 mb-4" >
                            <PriceSegment :id="'sight-'+sight.id+'-price-'+price.id" :state="state" :price="price" :index="index" @onDelPrice="deleteFromCurrentPrices" @onUpdPrice="test"/>
                        </div>
                    </div>
                </div>
                <p v-if="sightPriceCheck()==false"> Цена не указанна!</p>
            </div>

            <div>
                <p class=" ">Спонсор:</p>
                <div v-if="!state" class="">
                    <h6 :id="'sight-'+sight.id+'-sponsor'" class="mb-4" >{{ sight.sponsor }}</h6>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <input :id="'sight-'+sight.id+'-sponsor-input'" class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4   dark:bg-gray-700 rounded-lg p-2 pl-1 border m-0"
                     v-bind:value="sight.sponsor"
                     id="sponsor"
                     @input="event => text = event.target.value"
                     type="text">
                </div>
            </div>

            <div>
                <p class=" ">Материалы:</p>
                <div v-if="!state" class="">
                    <h6 :id="'sight-'+sight.id+'-materials'" class="mb-4" >{{ sight.materials }}</h6>
                </div>
                <div v-if="state" class="flex mb-4 space-x-4">
                    <input :id="'sight-'+sight.id+'-materials-input'" class=" text-xl leading-tight text-neutral-800 dark:text-neutral-50 w-3/4   dark:bg-gray-700 rounded-lg p-2 pl-1 border m-0"
                     v-bind:value="sight.materials"
                     id="materials"
                     @input="event => text = event.target.value"
                     type="text">
                </div>
            </div>
            <div v-if="connectState.StatusCard">
                <ChangeStatus  :status="status" @statusChanged="statusChange" v-if="!state"/>
            </div>

        </div>
    </div>
    <div v-if="connectState.EditButton">
        <input v-if="state" @click="clickUpd($event)" class="absolute rounded-lg bottom-0 right-0 bg-green-600 m-5 p-2 z-50" type="button" value="Применить">
        <button @click="discardChanges()" v-if="state" class="absolute rounded-lg bottom-0 right-0 bg-red-600 m-5 mr-36 p-2 z-50">Отмена</button>
        <button @click="state= !state" v-if="!state" class="absolute rounded-lg bottom-0 right-0 bg-blue-600 m-5 p-2 z-50">Редактировать</button>
    </div>

    </form>
</div>
</template>
<script>
import TypeList from '../../../components/types_list/TypeList.vue'
import { mapActions} from 'pinia'
import { MessageContents } from '../../../enums/content_messages'
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
import {container as ModalContainer, openModal, closeModal} from "jenesius-vue-modal";
import searchType from '../../../components/search_type/searchType.vue'

export default {
    name: 'SightShow',
    components:{
        CarouselGallery,
        TypeList,
        PriceSegment,
        ChangeStatus,
        searchType,
        ModalContainer
    },
    props:{
        connectState:{
            type: Object,
            default:{
                BackButton: true,
                NameLine: true,
                IdLine: true,
                Gallery: true,
                PricesCard: true,
                TypeCard: true,
                AuthorCard: true,
                StatusCard: true,
                EditButton: true,
            }
        },
        id:{
            type: Number,
            default: null
        }
    },
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        }
    },
    mounted(){

    },
    data() {
        return {
            sight: [],
            allTypes: null,
            status: "",
            sightFiles: [],
            priceId: 0,
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
            let id
            this.$props.id ? id = this.id : id = this.$route.params.id
            this.openLoaderFullPage()
            this.getSightForIds(id).pipe(
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
        searchTypeOpen(){
            openModal(searchType)
        },
        searchTypeClose(){
            closeModal(searchType)
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

            // console.log("на удаление", this.typesDel)
            // console.log("на добавление", this.typesUpd)
        },

        deleteFromCurrentPrices(price){
            for(let i = 0; i < this.sight.prices.length; i++){
                console.log(Object.keys(this.sight.prices[i]).indexOf("new_id"))

                if((Object.keys(this.sight.prices[i]).indexOf("new_id") != -1) && (this.sight.prices[i].new_id == price.new_id)){
                    this.sight.prices.splice(i, 1)
                }
                else if(this.sight.prices[i].id == price.id){
                    // this.sight.prices.splice(i, 1)
                }

            }
            for(let i = 0; i < this.pricesUpd.length; i++){
                console.log(Object.keys(this.pricesUpd[i]).indexOf("new_id"))

                if((Object.keys(this.pricesUpd[i]).indexOf("new_id") != -1) && (this.pricesUpd[i].new_id == price.new_id)){
                    this.pricesUpd.splice(i, 1)
                }
            }

            if (price.id && !price.new_id){
                this.pricesDel.push({"price_id":price.id, "on_delete":true})
            }


            console.log(this.pricesDel)
        },
        addToCurrentPrices(){
            let price1 = {"cost_rub":0, "descriptions":"Описание", "new_id":this.priceId}
            let price2 = price1
            this.pricesUpd.push(price2)
            this.sight.prices.push(price1)
            this.priceId++
        },
        checkObjInArray(obj, array){
            for (let i = 0; i<array.length; i++){
                if (array[i].id === obj.id){
                    console.log(obj, array)
                    return true
                }
            }
            return false
        },

        checkPriceInArray(obj, array, new_id = false){
            if(!new_id){
                for (let i = 0; i<array.length; i++){
                if(array[i].price.price.new_id != undefined){
                    console.log("WROK")
                    if (array[i].price.price.new_id == obj.price.new_id){

                        return true
                    }
                }
                else{

                }

            }
                return false
            }
            else{
                for (let i = 0; i<array.length; i++){
                    console.log(array[i].price)
                if(array[i].price.price.id != undefined){
                    if (array[i].price.price.id == obj.price.id){

                        return true
                    }
                }
                else{
                    console.log(array[i].price)
                }
            }
                return false
            }

        },

        discardChanges(){
            this.state = this.state
            this.getSight()
        },
        test(price){
            if(price.id != undefined && !this.checkObjInArray(price,this.pricesUpd)){
                console.log("WORK")
                this.pricesUpd.push(price)
            }
            console.log("На обновление",this.pricesUpd)
            console.log("Все цены", this.sight.prices)
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
        goToElement(event){
            let target = "s"+event.target.id

            let type = document.getElementById(target)
            type.scrollIntoView({behavior: "smooth",
                                block:'center'})
            type.classList.add('bg-sky-100')
            setInterval(() => {type.classList.remove('bg-sky-100')}, 1500)

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
        },


        sightPriceCheck(){
            return true
        },

        statusChange(status){
            this.status = status
        },
        freshAll(){
            this.sight = []
            this.allTypes = null
            this.status = ""
            this.sightFiles = []
            this.priceId = 0
            this.state = false
            this.currentSightPrice =[]
            this.filesDel = []
            this.filesUpd = []
            this.typesDel = []
            this.typesUpd = []
            this.pricesDel =[]
            this.pricesUpd =[]
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
                    case(`sight-${this.sight.id}-name-input`):
                    if (item[1].value != this.sight.name) {
                        console.log('new name value: ' + item[1].value)
                        historyData.history_content.name = item[1].value
                    }
                    break;
                    case(`sight-${this.sight.id}-sponsor-input`):
                    if (item[1].value != this.sight.sponsor) {
                        console.log('new sponsor value: ' + item[1].value)
                        historyData.history_content.sponsor = item[1].value
                    }
                    break;
                    case(`sight-${this.sight.id}-description-input`):
                    if (item[1].value != this.sight.description) {
                        console.log('new description value: ' + item[1].value)
                        historyData.history_content.description = item[1].value
                    }
                    break;
                    case(`sight-${this.sight.id}-materials-input`):
                    if ((item[1].value != this.sight.materials) && (item[1].value != "")) {
                        console.log('new materials value: ' + item[1].value)
                        historyData.history_content.materials =  item[1].value
                    }
                    break;
                    case(`sight-${this.sight.id}-work_time-input`):
                    if (item[1].value != this.sight.work_time) {
                        console.log('new work_time value: ' + item[1].value)
                        historyData.history_content.work_time = item[1].value
                    }
                    break;
                    case(`sight-${this.sight.id}-address-input`):
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

            if (this.pricesDel.length > 0 || this.pricesUpd.length > 0){
                historyData.history_content.history_prices = []



                this.pricesUpd.forEach(item => {
                    delete item.new_id
                    delete item.event_id
                    delete item.created_at
                    delete item.updated_at
                    delete item.sight_id

                    if(item.id != undefined){
                        item.price_id = item.id
                        delete item.id
                    }

                    historyData.history_content.history_prices.push(item)
                })

                this.pricesDel.forEach(item => {
                    historyData.history_content.history_prices.push(item)
                })

                console.log(this.pricesUpd)
            }





            // this.sightUpd.append("history_data",JSON.stringify(historyData))


            // this.saveSightHistory(this.sightUpd).pipe().subscribe(response => {console.log(response)})
            console.log(historyData)
            if(Object.keys(historyData.history_content).length != 0){
                this.openLoaderFullPage()
                this.saveSightHistory(historyData).pipe(
                    takeUntil(this.destroy$),
                    catchError(err => {
                        399 < err.response.status && err.response.status < 500 ? this.showToast(MessageContents.warning_upd_content + ': ' + err.message, 'warning') : null
                        499 < err.response.status && err.response.status < 600 ? this.showToast(MessageContents.error_upd_content + ': ' + err.message, 'error') : null
                        console.log(err)
                        return of(EMPTY)
                    })
                )

                .subscribe(response => {
                    this.showToast(MessageContents.sight_on_moderate, 'success')
                    this.freshAll()
                    this.getSight()
                    this.closeLoaderFullPage()

                })
                this.state = false
            }
            else{
                this.showToast("Изменения не обнаружены", "warning")
            }



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
<style>
.modal-container {
	z-index: 100;
}
</style>
