<template lang="">
    <div class="border w-full h-full rounded-lg dark:bg-gray-800 bg-gray-100 border-gray-400/70 shadow-lg dark:border-gray-700">
        <div class="w-[100%] mx-auto rounded-lg">
            <Carousel id="gallery" :items-to-show="1" :wrap-around="false" v-model="currentSlide" class="rounded-lg">
                <Slide v-for="slide in files" :key="slide" class="rounded-lg">
                    <div class="absolute w-[100%] h-[100%] blur-lg bg-gray-50/10 z-20 rounded-lg" :style="{backgroundImage:`url(${slide.link})`}"></div>
                <img :src="slide.link" :alt="slide.name" class="rounded-lg max-h-[20rem]  max-w-[30rem] z-30">
                </Slide>
            </Carousel>

            <div class="flex flex-row min-h-[7rem] min-w-[7rem]">
                <form ref="fileform">
                    <div v-if="wrightState" class="dark:text-gray-400/70 dark:bg-gray-700/70 hover:dark:text-gray-400 hover:dark:bg-gray-700 bg-slate-400/30 border-slate-400/40 text-gray-600 hover:bg-slate-400/60 hover:text-gray-700/90 w-[7rem] h-[7rem] m-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[6rem] h-[6rem] m-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                    </div>
                </form>
                <Carousel
                    id="thumbnails"
                    :items-to-show="4"
                    :wrap-around="true"
                    v-model="currentSlide"
                    ref="carousel"
                    class="border dark:border-gray-600/50 bg-slate-400/30 border-slate-400/40 dark:bg-gray-700/50 m-2 rounded-lg w-10/12 mx-auto"
                    >
                    <Slide v-for="(slide, index) in files" :key="slide">
                        <div class="flex flex-col max-h-[7rem] max-w-[7rem]" @click="slideTo(index)">
                            <img :src="slide.link" :alt="slide.name" class="rounded-lg">
                            <button v-if="wrightState" class="absolute dark:text-red-200 text-red-600 bg-red-200 dark:bg-red-600/70 p-1 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mx-auto">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </Slide>
                </Carousel>
            </div>
        </div>
    </div>
</template>
<script>
import { Carousel, Slide, Navigation } from 'vue3-carousel'


export default {
    name: 'CarouselGalleryAndUpdate',
    props: [
        'files',
        'wrightState',
    ],
    data() {
        return {
            currentSlide: 0,
        }
    },
    components: {
        Carousel,
        Slide,
        Navigation,
    },
    methods: {
        slideTo(val) {
            console.log(val)
            this.currentSlide = val
        },
        emitDelImg(file) {
            this.$emit('onDeleteFile', file)
        },
        emitUpdImg(file) {
            this.$emit('onUpdateFile', file)
        }
  }
}
</script>
<style lang="">
    
</style>