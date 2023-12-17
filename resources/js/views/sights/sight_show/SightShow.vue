<template lang="">
<div class="min-w-full min-h-full bg-gray-300 dark:bg-gray-900 p-1">
    <form>
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
        <input v-if="state" v-bind:value=sight.name @input="sight => text = sight.target.value" class="text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-2/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700rounded-lgp-2pl-1border-2m-0">
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
                    <input class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700 rounded-lg p-2 pl-1 border-2 m-0"
                     v-bind:value="sight.address"
                     type="text"
                     @input="sight => text = sight.target.value">
                    
                </div>
            </div>

            <div>
                <p class="">Время проведения:</p>
                <div v-if="!state" >
                    <h6 class="mb-4" >{{ sight.work_time }}</h6>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <textarea class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700 rounded-lg p-2 pl-1 border-2 m-0"
                     v-bind:value="sight.work_time"
                     type="text"
                     @input="sight => text = sight.target.value">
                    </textarea>
                </div>
            </div>
            
            <div class="">
                <div v-if="!state">
                    <p class="">Описание:</p>

                    <p class="mb-4 text-base text-neutral-800 dark:text-neutral-200">
                        {{ sight.description }}
                    </p>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <textarea class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-full border-sky-400/30 bg-indigo-50 dark:bg-gray-700 rounded-lg p-2 pl-1 border-2 m-0"
                    v-bind:value="sight.description"
                    type="text"
                    rows=7
                    @input="sight => text = sight.target.value">
                    </textarea>  
                </div> 
            </div>

            
           

            <div class="mb-4">
                <div class="">
                    <p>Типы события:</p>
                    <div v-for="stype in allTypes" class="space-y-2 border py-4">
                        <TypeList v-if="allTypes" :currentTypes="stype" />
                    </div>
                    
                </div>
                
                <!-- <div v-if="!state">
                    <p v-for="type_s in sight.types">{{ type_s.name }}</p>
                </div>

                <div v-if="state" class="flex" v-for="(type_s, index) in sight.types" v-bind:key="index">
                    <input 
                    class=" text-xl  leading-tight mb-2 text-neutral-800 dark:text-neutral-50 w-2/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700 rounded-lg p-2 pl-1 border-2 m-0"
                    v-bind:value="sight.types[index].name"
                    type="text"
                    @input="sight => text = sight.target.value">  
                </div> -->
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
                <div v-if="state">

                </div>
                <p v-if="sightPriceCheck()==false"> Цена не указанна!</p>

               
            </div>

            <div>
                <p class=" ">Спонсор:</p>
                <div v-if="!state" class="">
                    <h6 class="mb-4 " >{{ sight.sponsor }}</h6>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <input class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700 rounded-lg p-2 pl-1 border-2 m-0"
                     v-bind:value="sight.sponsor"
                     type="text">   
                </div>
            </div>

            <div>
                <p class=" ">Материалы:</p>
                <div v-if="!state" class="">
                    <h6 class="mb-4 " >{{ sight.materials }}</h6>
                </div>

                <div v-if="state" class="flex mb-4 space-x-4">
                    <input class=" text-xl  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700 rounded-lg p-2 pl-1 border-2 m-0"
                     v-bind:value="sight.materials"
                     type="text">   
                </div>
            </div>

            <div class="mb-4">
                <label for="status" class="block mb-2   text-gray-900 dark:text-white">Статус</label>
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
            class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs  uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
            data-te-ripple-init
            data-te-ripple-color="light"
            v-on:click="saveChanges">
            Сохранить
            </button>
        </div>
    </div>

        <input v-if="state" @click="clickUpd($event)" class="absolute rounded-lg bottom-0 right-0 bg-green-600 m-5 p-2 z-50" type="button" value="Применить">
        <button @click="state= !state" v-if="state" class="absolute rounded-lg bottom-0 right-0 bg-red-600 m-5 mr-36 p-2 z-50">Отмена</button>
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

export default {
    name: 'SightShow',
    components:{
        CarouselGallery,
        TypeList
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
            state: false,
            filesDel: [],
            filesUpd: [],

            
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
        })
      
      

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
        clickUpd(event) {
            // Передаём форму обработанную в масси в локальную переменную функции
            let mass = Object.entries(event.target.form)
            
            // Перебираем массив и формируем форм дату
            mass.forEach(item => {
                switch(item[1].id) {
                    case('name'):
                    if (item[1].value != this.event.name) {
                        console.log('new name value: ' + item[1].value)
                        this.sightUpd.append('name', item[1].value)
                    }
                    break;
                    case('sponsor'):
                    if (item[1].value != this.event.sponsor) {
                        console.log('new sponsor value: ' + item[1].value)
                        this.sightUpd.append('sponsor', item[1].value)
                    }
                    break;
                    case('description'):
                    if (item[1].value != this.event.description) {
                        console.log('new description value: ' + item[1].value)
                        this.sightUpd.append('description', item[1].value)
                    }
                    break;
                    case('materials'):
                    if (item[1].value != this.event.materials) {
                        console.log('new materials value: ' + item[1].value)
                        this.sightUpd.append('materials', item[1].value)
                    }
                    break;
                    
                }
            })
            // Перебираем и передаём фото на добавлений в форм дату
            this.filesUpd.forEach((item) => {
                // console.log('upd' + item)
                this.sightUpd.append('files[]', item)
            })
            // Перебираем, добавляем поле и передаём фото на удаление в форм дату
            this.filesDel.forEach((item) => {
                // console.log('del' + item)
                item.onDelete = true
                this.sightUpd.append('files[]', item)
            })
            this.state = false
            console.log(this.sightUpd)
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