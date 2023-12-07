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

    <CarouselGalleryAndUpdate :files="event.files" :wrightState="state"></CarouselGalleryAndUpdate>

    <button @click="state ? state = false: state = true">+++++++++</button>
    <!-- <div class="border rounded-lg dark:bg-gray-800 dark:border-gray-700">
        <div class="w-[70%] mx-auto rounded-lg ">
            <Carousel id="gallery" :items-to-show="1" :wrap-around="false" v-model="currentSlide" class="p-1">
                <Slide v-for="slide in event.files" :key="slide" class="rounded-lg">
                    <div class="absolute w-[100%] h-[100%] blur-lg bg-gray-50/10 z-20" :style="{backgroundImage:`url(${slide.link})`}"></div>
                <img :src="slide.link" :alt="slide.name" class="rounded-lg max-h-[20rem]  max-w-[30rem] z-30">
                </Slide>
            </Carousel>

            <Carousel
                id="thumbnails"
                :items-to-show="4"
                :wrap-around="true"
                v-model="currentSlide"
                ref="carousel"
                class="border dark:border-gray-600/50 dark:bg-gray-700/50 m-2 rounded-lg"
            >
                <Slide v-for="slide in event.files" :key="slide">
                <div class="max-h-[5rem max-w-[7rem]" @click="slideTo(slide - 1)">
                    <img :src="slide.link" :alt="slide.name" class="rounded-lg">
                </div>
                </Slide>
            </Carousel>
        </div>
    </div> -->
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

import CarouselGalleryAndUpdate from '../../../components/carousel_gallery_and_update/CarouselGalleryAndUpdate.vue'

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
            state: false,
        }
    },
    components: {
        CarouselGalleryAndUpdate
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
/* .carousel__item {
  min-height: 200px;
  width: 100%;
  background-color: var(--vc-clr-primary);
  color: var(--vc-clr-white);
  font-size: 20px;
  border-radius: 8px;
  display: flex;
  justify-content: center;
  align-items: center;
} */

/* .carousel__slide {
  padding: 5px;
} */

/* .carousel__prev,
.carousel__next {
  box-sizing: content-box;
  border: 5px solid white;
} */
</style>