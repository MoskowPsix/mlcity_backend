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
            <div class="m-2 grid 2xl:grid-cols-1 xl:grid-cols-2 lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-2">
                <div>
                    <AuthorMiniCard v-if="event.author" :author="event.author" class="h-96"/>
                </div>
                <div v-if="!state" class=" border rounded-lg p-2 mt-1 dark:border-gray-700/70 dark:bg-gray-800">
                    <ChangeStatus class="" :status="event.statuses[0].name" @statusChanged="statusChange"/>
                </div>
            </div>
        </div>
        <div class="transition absolute rounded-lg bottom-0 right-0 bg-gray-600/80 m-5 z-50 active:scale-95">
            <input v-if="state" @click="clickUpd($event)" class="rounded-lg bg-green-600 m-5 p-2 z-50 cursor-pointer" type="button" value="Применить">
            <button @click="state= !state" v-if="state" class="rounded-lg bg-red-600 m-5 p-2 cursor-pointer">Отмена</button>
            <button @click="state= !state" v-if="!state" class="rounded-lg bg-blue-600 m-5 p-2 cursor-pointer">Редактировать</button>
        </div>
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
import { useHistoryContentStore } from '../../../stores/HistoryContentStore'

import { catchError, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'
import router from '../../../routes'

import CarouselGallery from '../../../components/carousel_gallery/CarouselGallery.vue'
import AuthorMiniCard from '../../../components/author-mini-card/AuthorMiniCard.vue'
import PlacesListCard from '../../../components/places_list_card/PlacesListCard.vue'
import ChangeStatus from '../../../components/change_status/ChangeStatus.vue'

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
        PlacesListCard,
        ChangeStatus
    },
    methods: {
        ...mapActions(useEventStore, ['getEventForIds']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        ...mapActions(useHistoryContentStore, ['saveHistory']),
        clickUpd(event) {
            // Передаём форму обработанную в масси в локальную переменную функции
            let mass = Object.entries(event.target.form)
            let historyEvent = {
                history_files: [],
                history_places: [],
            }
            // Перебираем массив и формируем форм дату
            mass.forEach(item => {
                switch(item[1].id) {
                    case('name'):
                    if (item[1].value != this.event.name) {
                        console.log('new name value: ' + item[1].value)
                        historyEvent.name = item[1].value
                    }
                    break;
                    case('sponsor'):
                    if (item[1].value != this.event.sponsor) {
                        console.log('new sponsor value: ' + item[1].value)
                        historyEvent.name = item[1]
                    }
                    break;
                    case('description'):
                    if (item[1].value != this.event.description) {
                        console.log('new description value: ' + item[1].value)
                        historyEvent.name = item[1].value
                    }
                    break;
                    case('materials'):
                    if (item[1].value != this.event.materials) {
                        console.log('new materials value: ' + item[1].value)
                        historyEvent.name = item[1].value
                    }
                    break;
                    case('date_start'):
                    if (item[1].value != this.event.date_start) {
                        console.log('new date_start value: ' + item[1].value)
                        historyEvent.name = item[1].value
                    }
                    break;
                    case('date_end'):
                    if (item[1].value != this.event.date_end) {
                        console.log('new date_end value: ' + item[1].value)
                        historyEvent.name = item[1].value
                    }
                    break;
                }
            })
            historyEvent.history_files = []
            // Перебираем и передаём фото на добавлений в форм дату
            this.filesUpd.forEach((item) => {
                historyEvent.history_files.push(item)
            })
            // Перебираем, добавляем поле и передаём фото на удаление в форм дату
            this.filesDel.forEach((item) => {
                item.onDelete = true
                historyEvent.history_files.push(item)
            })
            if (this.placeUpd) {
                this.placeUpd.forEach((item, key) => {
                        Object.keys(item).find(k => {
                            if ((k == 'index')) {
                                delete this.placeUpd[key].index
                                return true
                            }
                        })
                        if (item.id == 0) {
                            delete item.id
                            delete this.placeUpd[key].id
                        }else{
                            this.placeUpd[key].place_id = JSON.parse(JSON.stringify(item.id))
                            delete this.placeUpd[key].id
                        }
                        Object.keys(item).find(k => {
                            if ((k == 'seances')) {
                                this.placeUpd[key].history_seances = []
                                item.seances.forEach((i,k) => {
                                    Object.keys(item).find(ind => {
                                        if ((ind == 'index')) {
                                            delete this.placeUpd[key].history_seances[k].index
                                            return true
                                        }
                                    })
                                    if (i.id == 0) {
                                        delete i.id
                                    } else {
                                        i.seance_id = i.id
                                        delete i.id
                                    }
                                    delete i.index
                                    this.placeUpd[key].history_seances.push(JSON.parse(JSON.stringify(i)))
                                })
                                delete this.placeUpd[key].seances
                                return true
                            }
                        })
                    })
                historyEvent.history_places = {...this.placeUpd}
            }
            this.state = false
            const params = {
                id: this.event.id,
                type: "Event",
                history_content: {...historyEvent}
            }
            console.log(params)
            this.saveHistory(params).pipe(
                map(response => {
                    console.log(response)
                }),
            ).subscribe()
            // console.log(historyEvent)
        },
        statusChange(status) {
            console.log(status)
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
                // this.$helpers.deepMerge(this.event.places_full[index],place)
                let mergePlace = {...place}
                delete mergePlace.seances
                this.$helpers.deepMerge(this.event.places_full[index], JSON.parse(JSON.stringify(mergePlace)))
                // Проверяем новое ли это обновление для place или place уже обновлялся и есть в массииве
                let getIndex = this.placeUpd.findIndex((item, key) => {
                    if(item.index == index) {
                        return true
                    }
                })
                if (getIndex != -1) {
                    // Проверяем есть ли сеансы
                    let seancesOnUpd = Object.keys(place).find(key => {
                            if ((key == 'seances')) {
                                return true
                            } else {
                                return false
                            }
                        });
                    if (seancesOnUpd) { 
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
                                if ((item.id !== 0) && item.id){
                                    console.log('Если есть поле id')
                                    // Если есть поле id не нулевое, то сеанс есть в бд и его нужно зафиксировать
                                    let oldSeance = this.placeUpd[getIndex].seances.findIndex((i,k) => {
                                        if (i.index == item.index) {
                                            console.log('='+JSON.parse(JSON.stringify(i)))
                                            this.placeUpd[getIndex].seances[k] = JSON.parse(JSON.stringify(item))
                                            return true
                                        }
                                    })     
                                    if (oldSeance != 0 && oldSeance) {
                                        this.placeUpd[getIndex].seances.push(JSON.parse(JSON.stringify(item)))
                                    }                         
                                    this.event.places_full[place.index].seances[item.index].on_delete = true
                                } else {
                                    console.log('Если нет поле id')
                                    let newSeances = []
                                    // Если поле id нулевое, то просто удалить
                                    this.placeUpd[getIndex].seances.map((i,k) => {
                                        if (i.index !== item.index) {
                                            newSeances.push(JSON.parse(JSON.stringify(i)))
                                            return true
                                        }
                                    })
                                    place.seances = JSON.parse(JSON.stringify(newSeances))
                                    this.placeUpd[getIndex].seances = JSON.parse(JSON.stringify(newSeances))
                                    this.event.places_full[place.index].seances.splice(item.index, 1)
                                }
                            } else {
                                // Если нет поля on_delete или его значение false
                                // Проверяем есть ли сеансы у плэйса на обновлении
                                let seanceOnUpd = Object.keys(this.placeUpd[getIndex]).find(key => {
                                    if (key == 'seances') {
                                        return true
                                    } else {
                                        return false
                                    }
                                })
                                console.log(seanceOnUpd)
                                if (seanceOnUpd) {
                                    // Если сеансы уже есть перебираем массив сеансов которые уже на обновлении
                                    this.placeUpd[getIndex].seances.forEach((i, k) => {
                                        // Если не совпадает индекс то добавляем к сеансам
                                        if (i.index !== item.index) {
                                            place.seances.push(JSON.parse(JSON.stringify(i)))
                                        }
                                    })
                                } else {
                                    // Если сеансов ещё нет
                                    this.placeUpd[getIndex].seances = []
                                    this.placeUpd[getIndex].seances.push(...place.seances)
                                }
                                this.event.places_full[index].seances[item.index] = item
                            }

                        })
                    }
                    const mergePlaceUpd = JSON.parse(JSON.stringify(place))
                    delete mergePlaceUpd.seances
                    this.$helpers.deepMerge(this.placeUpd[getIndex], mergePlaceUpd)
                } else {
                    // Если нету в массиве, то добавляем
                    let seanceOnUpd = Object.keys(place).find(key => {
                        if ((key == 'seances')) {
                            return true
                        } else {
                            return false
                        }
                    })
                    if(seanceOnUpd) {
                        place.seances.forEach((item, key) => {
                            // Проверяем есть ли поля on_delete в объекте seance и стоит ли у него значение true
                            let seanceOnDel = Object.keys(item).find(key => {
                                if ((key == 'on_delete') && (item[key] == true)) {
                                    return true
                                } else {
                                    return false
                                }
                            })
                            if (seanceOnDel) { 
                                console.log(seanceOnDel)
                                this.event.places_full[place.index].seances[item.index].on_delete = true
                            } 
                        })
                    }
                    this.placeUpd.push(JSON.parse(JSON.stringify(place)))
                }
            console.log(this.placeUpd)
            console.log(this.event)
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
                location: {},
                index: this.event.places_full.length
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