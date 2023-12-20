<template lang="">
<div class="flex flex-col min-w-full min-h-full bg-gray-300 dark:bg-gray-900 p-1" v-if="event">
    <form>
        <div class="flex items-center border rounded-lg bg-gray-50 dark:border-gray-700 dark:bg-gray-800/90 dark:text-gray-300 p-2 mb-2">
            <button
                @click.prevent="state = !state"
                type="button"
                data-te-ripple-init
                data-te-ripple-color="light"
                class="flex items-center rounded bg-primary min-w-1/12 max-w-2/12 mr-3 px-4 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                </svg>
                <h1 class="flex items-center mr-1 ml-1">Назад</h1>
            </button>
            <label v-if="!state" class="flex items-center w-8/12" ><h1>Имя: {{event.name}}</h1></label>
            <input v-if="state" class="border rounded-lg flex w-8/12 items-center dark:bg-gray-700/20 dark:border-gray-600/50" :value="event.name" @input="event => text = event.target.value" type="text" name="name" id="name">
            <label class="flex items-center w-3/12"><h1>ID: {{event.id}} | {{state}}</h1></label>
        </div>

        <CarouselGallery class="mb-1" v-if="event.files" :files="event.files" :wrightState="state" @onDeleteFile="deleteFiles" @onUpdateFile="updateFiles"></CarouselGallery>


        <div class="flex flex-col border rounded-lg bg-gray-50 dark:border-gray-700 dark:bg-gray-800/90 dark:text-gray-300 p-2 mb-2">
            <label>
                <h1 class="text-xl font-medium dark:text-gray-300 mb-2">Спонсор</h1>
                <p v-if="!state" class="text-sm font-normal dark:text-gray-200 mb-2">{{event.sponsor}}</p>
                <input v-if="state" class="w-full dark:bg-gray-700/50" type="text" name="sponsor" id="sponsor" :value="event.sponsor" @input="event => text = event.target.value">
            </label>
            <label>
                <h1 class="text-xl font-medium dark:text-gray-300 mb-2">Описание</h1>
                <p v-if="!state" class="text-sm font-normal dark:text-gray-200 mb-2" >{{event.description}}</p>
                <textarea v-if="state" class="w-full dark:bg-gray-700/50" :value="event.description" name="description" id="description" cols="30" rows="10" @input="event => text = event.target.value"></textarea>
            </label>
            <label>
                <h1 class="text-xl font-medium dark:text-gray-300 mb-2">Материалы</h1>
                <p v-if="!state" class="text-sm font-normal dark:text-gray-200 mb-2">{{event.materials}}</p>
                <input v-if="state" class="w-full dark:bg-gray-700/50" type="text" name="materials" id="materials" :value="event.materials" @input="event => text = event.target.value">
            </label>
            <label>
                <h1 class="text-xl font-medium dark:text-gray-300 mb-2">Дата начала и конца</h1>
                <p v-if="!state" class="text-sm font-normal dark:text-gray-200 mb-1">Начало: {{event.date_start}}</p>
                <p v-if="!state" class="text-sm font-normal dark:text-gray-200">Конец: {{event.date_end}}</p>
            </label>
        </div>

        <div class="grid 2xl:grid-cols-4 xl:grid-cols-1 lg:grid-cols-1 md:grid-cols-1 sm:grid-cols-1 w-full p-1 ">
            <div class="2xl:col-span-3 xl:col-span-1 lg:ol-span-1 mt-2 ">
                <div class="border dark:bg-gray-800/50 dark:border-gray-700 p-1 rounded-lg">
                    <div v-for="(place, index) in event.places_full" >
                        <PlacesListCard v-if="!place.on_delete" :stateUpd="state" :index="index" :place="place" @onUpdPlace="setPlace" class="mt-2"/>
                    </div>
                    <div v-if="state" @click.prevent="addNewPlace" class="transition border p-2 mt-2 rounded-lg font-medium text-center border-blue-500/70 text-blue-900 bg-blue-400 hover:bg-blue-400/70 hover:text-blue-900/70 dark:hover:border-blue-500/30 dark:border-blue-500/70 dark:text-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:hover:text-blue-400 hover:border-blue-500/30 active:scale-95 cursor-pointer">Добавить place</div>
                </div>
            </div>
            <div>
                <div>
                    <AuthorMiniCard v-if="event.author" :author="event.author" class="h-96"/>
                </div>
            </div>
        </div>
        <input v-if="state" @click="clickUpd($event)" class="absolute rounded-lg bottom-0 right-0 bg-green-600 m-5 p-2 z-50" type="button" value="Применить">
        <button @click="state= !state" v-if="state" class="absolute rounded-lg bottom-0 right-0 bg-red-600 m-5 mr-36 p-2 z-50">Отмена</button>
        <button @click="state= !state" v-if="!state" class="absolute rounded-lg bottom-0 right-0 bg-blue-600 m-5 p-2 z-50">Редактировать</button>
        <!-- <div>Удалённые {{filesDel}}</div>
        <div>Обновлённые {{filesUpd}}</div> -->
    </form>
</div>
</template>
<script>
import { mapActions} from 'pinia'
import { useToastStore } from '../../../stores/ToastStore'
import { MessageEvents } from '../../../enums/events_messages'
import { useEventStore } from '../../../stores/EventStore'
import { useLoaderStore } from '../../../stores/LoaderStore'
import { catchError, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'
import {unref} from 'vue'
import router from '../../../routes'

import CarouselGallery from '../../../components/carousel_gallery/CarouselGallery.vue'
import AuthorMiniCard from '../../../components/author-mini-card/AuthorMiniCard.vue'
import PlacesListCard from '../../../components/places_list_card/PlacesListCard.vue'

export default {
    name: 'EventShow',
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        } 
    },
    data() {
        return {
            event: [],
            eventUpd: new FormData(),
            newPlaces: [],
            state: true,
            filesDel: [],
            filesUpd: [],
            placeUpd: [],
        }
    },
    components: {
        CarouselGallery,
        AuthorMiniCard,
        PlacesListCard
    },
    methods: {
        ...mapActions(useEventStore, ['getEventForIds']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        clickUpd(event) {
            // Передаём форму обработанную в масси в локальную переменную функции
            let mass = Object.entries(event.target.form)
            // Перебираем массив и формируем форм дату
            mass.forEach(item => {
                switch(item[1].id) {
                    case('name'):
                    if (item[1].value != this.event.name) {
                        console.log('new name value: ' + item[1].value)
                        this.eventUpd.append('name', item[1].value)
                    }
                    break;
                    case('sponsor'):
                    if (item[1].value != this.event.sponsor) {
                        console.log('new sponsor value: ' + item[1].value)
                        this.eventUpd.append('sponsor', item[1].value)
                    }
                    break;
                    case('description'):
                    if (item[1].value != this.event.description) {
                        console.log('new description value: ' + item[1].value)
                        this.eventUpd.append('description', item[1].value)
                    }
                    break;
                    case('materials'):
                    if (item[1].value != this.event.materials) {
                        console.log('new materials value: ' + item[1].value)
                        this.eventUpd.append('materials', item[1].value)
                    }
                    break;
                    case('date_start'):
                    if (item[1].value != this.event.date_start) {
                        console.log('new date_start value: ' + item[1].value)
                        this.eventUpd.append('date_start', item[1].value)
                    }
                    break;
                    case('date_end'):
                    if (item[1].value != this.event.date_end) {
                        console.log('new date_end value: ' + item[1].value)
                        this.eventUpd.append('date_end', item[1].value)
                    }
                    break;
                }
            })
            // Перебираем и передаём фото на добавлений в форм дату
            this.filesUpd.forEach((item) => {
                this.eventUpd.append('files[]', item)
            })
            // Перебираем, добавляем поле и передаём фото на удаление в форм дату
            this.filesDel.forEach((item) => {
                item.onDelete = true
                this.eventUpd.append('files[]', item)
            })
            this.state = false
            console.log(this.eventUpd)
        },
        getEvent() {
            retry(3),
            delay(100),
            this.openLoaderFullPage()
            this.getEventForIds(this.$route.params.id).pipe(
                map(response => {
                    this.event = response.data
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
        deleteFiles(file) {
            this.event.files.find((item, index) => { 
                if (item.name == file.name ) {
                    let coin = this.filesUpd.find((itm, i) => { 
                        if (itm.name == file.name ) {
                            this.filesUpd.splice(i, 1)
                            return true
                        }
                    })
                    coin ? null : this.filesDel.push(file)
                    this.event.files.splice(index, 1)
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
                    this.event.files.push({link: reader.result, name: file.name, size: file.size, type: file.type}) 
                }
                this.filesUpd.push(file)
            })
            console.log(this.filesUpd)
        },
        backButton(){
            router.go(-1)
        },
        setPlace(place) {
            let index = place.index
            let placeOnDel = Object.keys(place).find(key => {
                if ((key == 'on_delete') && (place[key] == true)) {
                    return true
                } else {
                    return false
                }
            });
            if (placeOnDel) {
                // Если есть поле on_delete
                if(place.id) {
                    // Если есть поле id и place существует в БД
                    this.event.places_full[index].on_delete = true
                    this.placeUpd.push(place)
                } else {
                    // Если нет поля id и place не существует в БД
                    this.event.places_full.splice(place.index)
                }
            } else { // Если поля on_delete нету
                    this.$helpers.deepMerge(this.event.places_full[index],place)
                    // Проверяем новое ли это обновление для place или place уже обновлялся и есть в массииве
                    let getIndex = this.placeUpd.findIndex((item, key) => {
                        if(item.index == index) {
                            return true
                        }
                    })
                    if (getIndex != -1) {
                        // Если уже есть в массиве то производим слияне
                        // Перебираем массив пришедших сеансов
                        place.seances.forEach((item, key) => {
                            // Проверяем есть ли поля on_delete в объекте seance и стоит ли у него значение true
                            let seanceOnDel = Object.keys(item).find(key => {
                                if ((key == 'on_delete') && (item[key] == true)) {
                                    return true
                                } else {
                                    return false
                                }
                            });
                            if (seanceOnDel) {
                                // Если есть поле on_delete со значением true
                                if (item.id){
                                    // Если есть поле id не нулевое, то сеанс есть в бд и его нужно зафиксировать
                                    this.event.full_places[index].seances[item.index].on_delete = true
                                    let updPlace = this.placeUpd.findIndex((i,k) => { if(i.index = index) {return true} })
                                    let updSeance = updPlace.seances.findIndex((i,k) => {if (i.index = item.index) {return true}})
                                } else {
                                    // Если поле id нулевое, то просто удалить
                                    this.placeUpd[getIndex].seances[item.index].splice(place.index)
                                }
                            } else {
                                // Если нет поля on_delete или его значение false
                                // Перебираем массив сеансов которые уже на обновлении
                                this.placeUpd[getIndex].seances.forEach((i, k) => {
                                    // Если совпадает индекс то заменяем этот элемент в массиве на обновление
                                    if (i.index != item.index) {
                                        place.seances.push(i)
                                    }
                                })
                            }

                        })
                        place.seance = this.placeUpd[getIndex].seance
                        this.$helpers.deepMerge(this.placeUpd[getIndex], place)
                    } else {
                        // Если нету в массиве, то добавляем
                        this.placeUpd.push(place)
                    }
            }
            console.log(this.placeUpd)
        },
        setSeance(seance) {
            let seanceOnDel = Object.keys(seance).find(key => {
                if ((key == 'on_delete') && (place[key] == true)) {
                    return true
                } else {
                    return false
                }
            });
            if (seanceOnDel) {
                // Если есть поле on_delete
                if(seance.id) {
                    // Если есть поле id и seance существует в БД
                } else {
                    // Если нет поля id и seance не существует в БД
                }
            } else {
                // Если нет поля on_delete
                if(seance.id) {
                    // Если есть поле id и seance существует в БД
                } else {
                    // Если нет поля id и seance не существует в БД
                }
            }
        },
        addNewPlace() {
            this.event.places_full.push({
                id: 0,
                address: this.event.places_full[0].address,
                event_id: this.event.id,
                sight_id: this.event.places_full[0].sight_id,
                latitude: this.event.places_full[0].latitude,
                longitude: this.event.places_full[0].longitude,
                location_id: 1,
                seances: [],
                location: {}
            })
        }
    },
    mounted() {
        this.openLoaderFullPage
        this.getEvent()
    },
}
</script>
<style>

</style>