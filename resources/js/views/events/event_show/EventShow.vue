<template lang="">
<div class="flex flex-col min-w-full min-h-full bg-gray-300 dark:bg-gray-900 p-1" v-if="event">
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
        <label class="flex items-center w-8/12"><h1>Имя: {{event.name}}</h1></label>
        <label class="flex items-center w-3/12"><h1>ID: {{event.id}}</h1></label>
    </div>

    <CarouselGallery v-if="event.files" :files="event.files" :wrightState="state" @onDeleteFile="deleteFiles" @onUpdateFile="updateFiles"></CarouselGallery>
    <button @click="state ? state = false: state = true" class="p-2 bg-green-500 rounded-lg border border-green-300 text-green-100 mt-1 md-1">Редактировать файлы</button>
    <div class="grid 2xl:grid-cols-4 xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 w-full p-1 ">
        <div class="2xl:col-span-3 xl:col-span-2 lg:ol-span-2 "></div>
        <!-- <CarouselGallery v-if="event.files" :files="event.files" :wrightState="state" @onDeleteFile="deleteFiles" @onUpdateFile="updateFiles" class="flex w-9/12"></CarouselGallery> -->
        <AuthorMiniCard v-if="event.author" :author="event.author" class="col-span-1" />
    </div>
    <!-- <div>Удалённые {{filesDel}}</div>
    <div>Обновлённые {{filesUpd}}</div> -->
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
import router from '../../../routes'

import CarouselGallery from '../../../components/carousel_gallery/CarouselGallery.vue'
import AuthorMiniCard from '../../../components/author-mini-card/AuthorMiniCard.vue'

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
            state: true,
            filesDel: [],
            filesUpd: [],
        }
    },
    components: {
        CarouselGallery,
        AuthorMiniCard
    },
    methods: {
        ...mapActions(useEventStore, ['getEventForIds']),
        ...mapActions(useToastStore, ['showToast']),
        ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
        getEvent() {
            retry(3),
            delay(100),
            this.openLoaderFullPage()
            this.getEventForIds(this.$route.params.id).pipe(
                map(response => {
                    this.event = response.data
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
            // console.log(['update', files])
            files = Array.from(files)
            files.forEach(file => {
                let reader = new FileReader()
                reader.readAsDataURL(file)
                console.log(reader)
                reader.onload = () => {
                    this.event.files.push({link: reader.result, name: file.name, size: file.size, type: file.type}) 
                }
                this.filesUpd.push(file)
            })
            console.log(this.filesUpd)
        },
        backButton(){
            router.go(-1)
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