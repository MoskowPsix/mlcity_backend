<template lang="">
    <DropZone @files-dropped="emitUpdImg" @state="setState" class="border w-full h-full rounded-lg dark:bg-gray-800 bg-gray-100 border-gray-400/70 shadow-lg dark:border-gray-700">
        <div class="w-[100%] mx-auto rounded-lg">
            <div v-if="stateDrop && wrightState" class="absolute flex border left-1/2 top-[9rem] w-48 h-48 z-50 bg-slate-300/70 dark:bg-gray-900/70 dark:border-gray-700/50 border-gray-200/90 text-gray-800/70 dark:text-gray-400/70 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-40 w-40">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15" />
                </svg>
            </div>
            <Carousel id="gallery" :items-to-show="1" :wrap-around="false" v-model="currentSlide" class="rounded-lg">
                <Slide v-for="slide in files" :key="slide" class="rounded-lg" >
                        <div  class="absolute w-[100%] h-[100%] blur-lg bg-gray-50/10 z-20 rounded-lg" :style="{backgroundImage:`url(${slide.link})`}"></div>
                        <img  :src="slide.link" :alt="slide.name" class="rounded-lg max-h-[20rem]  max-w-[30rem] z-30">
                </Slide>
            </Carousel>

            <div class="flex flex-row min-h-[7rem] min-w-[7rem]">
                <div v-if="wrightState"  @click="chooseFiles()" class="dark:text-gray-400/70 dark:bg-gray-700/70 hover:dark:text-gray-400 hover:dark:bg-gray-700 bg-slate-400/30 border-slate-400/40 text-gray-600 hover:bg-slate-400/60 hover:text-gray-700/90 w-[6rem] h-[6rem] m-3 rounded-lg p-0.2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[6rem] h-[6rem]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                    <input
                    id="fileUpload"
                    type="file"
                    @change="onFileChanged($event)" 
                    hidden                   
                    />
                </div>
                <Carousel
                    id="thumbnails"
                    :items-to-show="4"
                    :wrap-around="true"
                    v-model="currentSlide"
                    ref="carousel"
                    class="border dark:border-gray-600/50 bg-slate-400/30 border-slate-400/40 dark:bg-gray-700/50 m-2 rounded-lg w-10/12 mx-auto"
                    >
                    <Slide  v-if="files.length >1" v-for="(slide, index) in files" :key="slide" >
                        <div class="flex flex-col max-h-[7rem] max-w-[7rem]" @click="slideTo(index)" >
                            <img :src="slide.link" :alt="slide.name" class="rounded-lg">
                            <button v-if="wrightState" class="absolute dark:text-red-200 text-red-600 bg-red-200/70 dark:bg-red-600/70 p-1 rounded-lg" @click="emitDelImg(slide)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mx-auto">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </Slide>
                </Carousel>
            </div>
        </div>
    </DropZone>
</template>
<script>
import { Carousel, Slide, Navigation } from 'vue3-carousel'
import DropZone from '../drop_zone/DropZone.vue'


export default {
    name: 'CarouselGallery',
    props: {
        files: Array, // Входной массив файлов
        wrightState: Boolean, // Определяет состояние редактирования(true: включено редактирование, false: редактирование отключено)
    },
    data() {
        return {
            currentSlide: 0,
            stateDrop: false,
        }
    },
    components: {
        Carousel,
        Slide,
        Navigation,
        DropZone
    },
    methods: {
        // Функция передачи файла из input
        onFileChanged(event) {
            this.emitUpdImg(event.target.files)
        },
        // Функция обращения к скрытому полю input
        chooseFiles: function() {
            document.getElementById("fileUpload").click()
        },
        // Перехват состояния компонента DropZone (true: файл в поле дроп зоны, false: файл вне поля дроп зоны)
        setState(state) {
            this.stateDrop = state
        },
        // Функция переключения слайдов по клику на превью
        slideTo(val) {
            this.currentSlide = val
        },
        // Выходные данные на удаление(Отправляет элемент массива на удаление)
        emitDelImg(file) { 
            if (this.wrightState) {
                this.$emit('onDeleteFile', file)
            }
        },
        // Выходные данные на добавление(Отправляет элемент массива на добавление)
        emitUpdImg(file) {
            if (this.wrightState) {
                this.$emit('onUpdateFile', file)
            }
        }
  }
}
</script>
<style lang="">
    
</style>