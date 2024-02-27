<template lang="">
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

        <label v-if="!state && connectState.NameLine" :id="'sight-'+sight.id+'-name'"><h1>Имя: {{sight.name}}</h1></label>
        <input v-if="state && connectState.NameLine" :id="'sight-'+sight.id+'-name-input'" v-bind:value=sight.name @input="event => text = event.target.value" class="text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-2/4   dark:bg-gray-700rounded-lgp-2pl-1borderm-0">
        <label class="flex items-center w-3/12" :id="'sight-'+sight.id+'-id'"><h1>ID: {{sight.id}}</h1></label>
    </div>
        <div class="block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-gray-800">

            <div class="flex justify-center">
                <div class="flex items-center justify-center min-w-[100%] flex-col m-2 p-5">
                    <div class="w-[100%] xl:w-[80%] text-xs lg:text-lg">
                        <div>
                            <h1 class="font-[Montserrat-Regular]">Название</h1>
                            <div v-bind:class="{'border-blue-700/70':state, 'bg-green-500/50': history_mode, 'bg-red-500/50': history_mode, 'bg-blue-500/50': history_mode,'border-blue-400':this.$props.changedFields && this.$props.changedFields.name}" class="transition flex justify-center items-center duration-1000 text-center p-2 w-[100%] border-2 border-[#EDEDED] h-10 rounded-lg mt-1 font-[Montserrat-Regular]">
                                <p v-if="!state && connectState.NameLine" class="text-center" :id="'sight-'+sight.id+'-name'">{{sight.name}}</p>
                                <input v-if="state && connectState.NameLine" v-bind:value=sight.name @input="event => text = event.target.value"
                                type="text"
                                :id="'sight-'+sight.id+'-name-input'"
                                class="rounded-sm border-none p-0 focus:shadow-md focus:rounded-sm focus:ring-1  focus:ring-blue-500 focus:ring-opacity-50 focus:outline-none transition delay-200 duration-300 w-full text-center"/>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="font-[Montserrat-Regular]">Организатор</label>
                            <div v-bind:class="{'border-blue-700/70':state, 'bg-green-500/50': history_mode, 'bg-red-500/50': history_mode, 'bg-blue-500/50': history_mode, 'border-blue-400':this.$props.changedFields && this.$props.changedFields.sponsor}" class="transition duration-1000 border-2 h-10 flex justify-center items-center border-[#EDEDED] rounded-md p-2 font-[Montserrat-Medium] sm:text-sm text-center lg:text-lg">
                                <p v-if="!state && connectState.NameLine" :id="'sight-'+sight.id+'-sponsor'" >{{ sight.sponsor }}</p>
                                <input v-if="state && connectState.NameLine" v-bind:value=sight.sponsor @input="event => text = event.target.value"
                                type="text"
                                :id="'sight-'+sight.id+'-sponsor-input'"
                                class="rounded-sm border-none focus:shadow-md focus:rounded-sm focus:ring-1 p-0 focus:ring-blue-500 focus:ring-opacity-50 focus:outline-none transition delay-200 duration-300 w-full text-center"/>
                            </div>
                        </div>


                        <div class="grid lg:grid-cols-2 mt-4">
                            <div>
                                <label class="font-[Montserrat-Regular] text-xs lg:text-lg">Типы</label>
                                <div v-bind:class="{'border-blue-700/70':state}" class="transition duration-1000 border-2 rounded-md font-[Montserrat-Medium] max-w-[60%] py-0.5">
                                    <div v-if="sight.types" class="text-center py-2 space-y-2.5">
                                        <p v-for="s_type in sight.types" class="border-b-2 mx-4" v-bind:class="{ 'bg-green-500/50': history_mode, 'bg-red-500/50': history_mode, 'bg-blue-500/50': history_mode}"> {{ s_type.name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="lg:max-w-[100%] sm:max-w-[70%] h-40">
                                    <label class="font-[Montserrat-Regular] text-xs lg:text-lg">Расписание</label>
                                    <div v-bind:class="{'border-blue-700/70':state,'bg-green-500/50': history_mode, 'bg-red-500/50': history_mode, 'bg-blue-500/50': history_mode, 'border-blue-400':this.$props.changedFields && this.$props.changedFields.work_time}" class="transition duration-1000 border-2 border-[#EDEDED] rounded-md min-h-[112px] h-max p-2 font-[Montserrat-Medium] leading-6">
                                        <p v-if="!state && connectState.NameLine" :id="'sight-'+sight.id+'-work_time'">{{sight.work_time }}</p>
                                        <textarea  v-if="state && connectState.NameLine" v-bind:value=sight.work_time @input="event => text = event.target.value"
                                        rows="3"
                                        type="text"
                                        :id="'sight-'+sight.id+'-work_time-input'"
                                        class="rounded-sm border-none focus:shadow-md focus:rounded-sm focus:ring-1 p-0 focus:ring-blue-500 focus:ring-opacity-50 focus:outline-none transition delay-200 duration-300 w-full"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 mb-8">
                            <label class="font-[Montserrat-Regular] text-xs lg:text-lg">Место проведения</label>
                            <div v-bind:class="{'border-blue-700/70':state,'bg-green-500/50': history_mode, 'bg-red-500/50': history_mode, 'bg-blue-500/50': history_mode, 'border-blue-400':this.$props.changedFields && this.$props.changedFields.address}" class="transition duration-1000 border-2 border-[#ededed] rounded-md p-2 font-[Montserrat-Medium]">
                                <p v-if="!state && connectState.NameLine" :id="'sight-'+sight.id+'-address'">{{ sight.address }}</p>
                                <input v-if="state && connectState.NameLine" v-bind:value=sight.address @input="event => text = event.target.value"
                                type="text"
                                :id="'sight-'+sight.id+'-address-input'"
                                class="rounded-sm border-none focus:shadow-md focus:rounded-sm focus:ring-1  focus:ring-blue-500 focus:ring-opacity-50 focus:outline-none transition delay-200 duration-300 w-full"/>
                            </div>
                        </div>

                        <div>
                            <div>
                                <CarouselGallery :id="'sight-'+sight.id+'-gallery'" :files="sight.files" :wrightState="state" v-if="sight.files && connectState.Gallery" @onDeleteFile="deleteFiles" @onUpdateFile="updateFiles"></CarouselGallery>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="font-[Montserrat-Regular] text-xs lg:text-lg">Описание</label>
                            <div  v-bind:class="{' border-2 border-blue-400 border-lg':this.$props.changedFields && this.$props.changedFields.description}" class="rounded-md p-2 font-[Montserrat-Medium] leading-8 sm:leading-7.5">
                                <p v-if="!state && connectState.NameLine" :id="'sight-'+sight.id+'-description'">{{sight.description}}</p>
                                <textarea v-bind:class="{'border-blue-700/70':state,'bg-green-500/50': history_mode, 'bg-red-500/50': history_mode, 'bg-blue-500/50': history_mode}" v-if="state && connectState.NameLine" v-bind:value=sight.description @input="event => text = event.target.value"
                                        rows="12"
                                        type="text"
                                        :id="'sight-'+sight.id+'-description-input'"
                                        class="rounded-sm border-none focus:shadow-md focus:rounded-sm focus:ring-1  focus:ring-blue-500 focus:ring-opacity-50 focus:outline-none transition delay-200 duration-300 w-full">
                                </textarea>
                            </div>
                        </div>



                    <div v-bind:class="{'bg-green-500/50': history_mode, 'bg-red-500/50': history_mode, 'bg-blue-500/50': history_mode, 'border-blue-400':this.$props.changedFields && this.$props.changedFields.materials}" class="mt-4">
                        <label v-if="!sight.materials" class="font-[Montserrat-Regular] text-xs lg:text-lg">Материалы отсутствуют</label>
                        <div v-if="sight.materials">
                            <label class="font-[Montserrat-Regular] text-xs lg:text-lg">Материалы</label>
                            <div class="border-2 border-[#ededed] rounded-md p-2 font-[Montserrat-Medium] leading-5">
                                <p :id="'sight-'+sight.id+'-materials'">{{ sight.materials }}</p>
                            </div>
                        </div>
                        <textarea v-if="state && connectState.NameLine" v-bind:value=sight.materials @input="event => text = event.target.value"
                                    rows="6"
                                    type="text"
                                    :id="'sight-'+sight.id+'-materials-input'"
                                    class="rounded-sm border-2 border-blue-500 focus:shadow-md focus:rounded-sm focus:ring-1  focus:ring-blue-500 focus:ring-opacity-50 focus:outline-none transition delay-200 duration-300 w-full">
                        </textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div v-if="connectState.EditButton">

            <input class="hidden" type="button" value="Применить" @click="clickUpd($event)" ref="accept" id="acceptButton">
            <label for="acceptButton">
                <button  v-if="state" @click.prevent="$refs.accept.click()" class="absolute rounded-lg bottom-0 right-0 bg-gray-100 m-5 p-2 z-50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-green-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
            </button>
            </label>

            <button @click="discardChanges()" v-if="state" class="absolute rounded-lg bottom-0 right-0 bg-gray-100 m-5 mr-20 p-2 z-50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-red-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <button @click="state= !state" v-if="!state" class="absolute rounded-lg bottom-0 right-0 bg-gray-100 flex justify-items-center m-5 p-2 z-50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-700/70">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
            </button>
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



export default {
    name: 'SightShow',
    components:{
        CarouselGallery,
        TypeList,
        PriceSegment,
        ChangeStatus
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
        },
        sight_: {
            type: Object,
            default: null
        },
        changedFields: {
            type: Object,
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
            history_mode: false,
            history_add: true,
            history_edit: true,
            history_delete: true,
        }
    },
    methods: {
        ...mapActions(useSightStore, ['getSightForIds','saveSightHistory']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useTypeStore,['getSightTypes']),
        getSight() {
            let id
            this.$props.id ? id = this.id : id = this.$route.params.id

            if(this.$props.sight_ == null){
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
            }
            else{
                console.log(this.$props.sight_)
                console.log(this.$props)
                this.sight = this.$props.sight_
            }

        },
        searchTypeOpen(){
            const props = {}
            openModal(TypeList)
        },
        searchTypeClose(){
            closeModal(searchType)
        },
        getAllTypes(){

        this.getSightTypes().pipe(
          retry(3),
          delay(200),
          catchError(error => {
            console.log(err)
          })
        ).subscribe(response => {
          this.allTypes = response.data.types
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

                if((Object.keys(this.sight.prices[i]).indexOf("new_id") != -1) && (this.sight.prices[i].new_id == price.new_id)){
                    this.sight.prices.splice(i, 1)
                }
                else if(this.sight.prices[i].id == price.id){
                    // this.sight.prices.splice(i, 1)
                }

            }
            for(let i = 0; i < this.pricesUpd.length; i++){

                if((Object.keys(this.pricesUpd[i]).indexOf("new_id") != -1) && (this.pricesUpd[i].new_id == price.new_id)){
                    this.pricesUpd.splice(i, 1)
                }
            }

            if (price.id && !price.new_id){
                this.pricesDel.push({"price_id":price.id, "on_delete":true})
            }


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
                    return true
                }
            }
            return false
        },

        checkPriceInArray(obj, array, new_id = false){
            if(!new_id){
                for (let i = 0; i<array.length; i++){
                if(array[i].price.price.new_id != undefined){
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
                if(array[i].price.price.id != undefined){
                    if (array[i].price.price.id == obj.price.id){

                        return true
                    }
                }
                else{
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
                this.pricesUpd.push(price)
            }
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

            console.log(event.target)
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
                        historyData.history_content.name = item[1].value
                    }
                    break;
                    case(`sight-${this.sight.id}-sponsor-input`):
                    if (item[1].value != this.sight.sponsor) {
                        historyData.history_content.sponsor = item[1].value
                    }
                    break;
                    case(`sight-${this.sight.id}-description-input`):
                    if (item[1].value != this.sight.description) {
                        historyData.history_content.description = item[1].value
                    }
                    break;
                    case(`sight-${this.sight.id}-materials-input`):
                    if ((item[1].value != this.sight.materials) && (item[1].value != "")) {
                        historyData.history_content.materials =  item[1].value
                    }
                    break;
                    case(`sight-${this.sight.id}-work_time-input`):
                    if (item[1].value != this.sight.work_time) {
                        historyData.history_content.work_time = item[1].value
                    }
                    break;
                    case(`sight-${this.sight.id}-address-input`):
                    if (item[1].value != this.sight.address) {
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
            }





            // this.sightUpd.append("history_data",JSON.stringify(historyData))


            // this.saveSightHistory(this.sightUpd).pipe().subscribe(response => {console.log(response)})
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
input:focus {
        outline:none;
    }
</style>
