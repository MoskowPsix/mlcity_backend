<template lang="">
    <div class="border dark:border-gray-700 dark:bg-gray-900/40 p-1 bg-gray-300/70 border-gray-500/30 rounded-lg mb-2 ml-2 mr-2">
        <div @click.prevent="setDelete" class="h-5 w-5 dark:bg-red-900 ml-[95%] dark:text-red-500  bg-red-400 text-red-800 cursor-pointer rounded-lg" v-if="state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-4 h-4  mx-auto my-auto">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
        </div>
        <div v-if="seance" class="grid grid-cols-2">
            <div class=" mt-1 flex flex-row border rounded-lg dark:border-gray-700 dark:bg-gray-900/30 bg-gray-100 mr-1">
                <div class=" -rotate-90 h-8 mt-4 text-lg font-medium">
                    {{new Date(seance.date_start).getFullYear()}}
                </div>
                <div class="flex flex-col">
                    <div :class="new Date(seance.date_start).getDay() === 5 || new Date(seance.date_start).getDay() === 6 ? 'text-red-600 text-4xl font-bold' : 'text-gray-500 text-4xl font-bold'">
                        {{new Date(seance.date_start).getDate()}}
                    </div>
                    <div class="text-sm mx-auto">
                        <label v-if="new Date(seance.date_start).getMonth() == 0">
                            Янв
                        </label>
                        <label v-if="new Date(seance.date_start).getMonth() == 1">
                            Фев
                        </label>
                        <label v-if="new Date(seance.date_start).getMonth() == 2">
                            Март
                        </label>
                        <label v-if="new Date(seance.date_start).getMonth() == 3">
                            Апр
                        </label>
                        <label v-if="new Date(seance.date_start).getMonth() == 4">
                            Май
                        </label>
                        <label v-if="new Date(seance.date_start).getMonth() == 5">
                            Июнь
                        </label>
                        <label v-if="new Date(seance.date_start).getMonth() == 6">
                            Июль
                        </label>
                        <label v-if="new Date(seance.date_start).getMonth() == 7">
                            Авг
                        </label>
                        <label v-if="new Date(seance.date_start).getMonth() == 8">
                            Сент
                        </label>
                        <label v-if="new Date(seance.date_start).getMonth() == 9">
                            Окт
                        </label>
                        <label v-if="new Date(seance.date_start).getMonth() == 10">
                            Нояб
                        </label>
                        <label v-if="new Date(seance.date_start).getMonth() == 11">
                            Дек
                        </label>
                    </div>
                </div>
                <div class="text-xl p-2 my-auto">
                    {{new Date(seance.date_start).getUTCHours()}}:{{new Date(seance.date_start).getUTCMinutes() ? new Date(seance.date_start).getUTCMinutes() : '00'}}
                </div>
            </div>
            <div class=" mt-1 flex flex-row border rounded-lg dark:border-gray-700 dark:bg-gray-900/30 bg-gray-100">
                <div class=" -rotate-90 h-8 mt-4 text-lg font-medium">
                    {{new Date(seance.date_end).getFullYear()}}
                </div>
                <div class="flex flex-col">
                    <div :class="new Date(seance.date_end).getDay() === 5 || new Date(seance.date_end).getDay() === 6 ? 'text-red-600 text-4xl font-bold' : 'text-gray-500 text-4xl font-bold'">
                        {{new Date(seance.date_end).getDate()}}
                    </div>
                    <div class="text-sm mx-auto">
                        <label v-if="new Date(seance.date_end).getMonth() == 0">
                            Янв
                        </label>
                        <label v-if="new Date(seance.date_end).getMonth() == 1">
                            Фев
                        </label>
                        <label v-if="new Date(seance.date_end).getMonth() == 2">
                            Март
                        </label>
                        <label v-if="new Date(seance.date_end).getMonth() == 3">
                            Апр
                        </label>
                        <label v-if="new Date(seance.date_end).getMonth() == 4">
                            Май
                        </label>
                        <label v-if="new Date(seance.date_end).getMonth() == 5">
                            Июнь
                        </label>
                        <label v-if="new Date(seance.date_end).getMonth() == 6">
                            Июль
                        </label>
                        <label v-if="new Date(seance.date_end).getMonth() == 7">
                            Авг
                        </label>
                        <label v-if="new Date(seance.date_end).getMonth() == 8">
                            Сент
                        </label>
                        <label v-if="new Date(seance.date_end).getMonth() == 9">
                            Окт
                        </label>
                        <label v-if="new Date(seance.date_end).getMonth() == 10">
                            Нояб
                        </label>
                        <label v-if="new Date(seance.date_end).getMonth() == 11">
                            Дек
                        </label>
                    </div>
                </div>
                <div class="text-xl p-2 my-auto">
                    {{new Date(seance.date_end).getUTCHours()}}:{{new Date(seance.date_end).getUTCMinutes() ? new Date(seance.date_end).getUTCMinutes() : '00'}}
                </div>
            </div>
        </div>
        <div v-if="seance && state">
            <VueTailwindDatepicker :auto-apply="false" as-single use-range  v-model="seanceTime" :formatter="formatter" class="w-full h-full mt-1"  placeholder="Дата и время сеанса" />
        </div>
    </div>
</template>
<script>
import VueTailwindDatepicker from 'vue-tailwind-datepicker'
import { unref, ref } from 'vue'


export default {
    name: 'SeancesListSegment',
    setup() {
        const formatter = {
            date: 'YYYY-MM-DD hh:mm:ss',
            month: 'MM',
        }
        let seanceTime = ref({
            startDate: "",
            endDate: ""
         })
        return {
            seanceTime,
            formatter,
        }
    },
    date() {
        return {
            
        }
    },
    props: {
        seance: Object,
        index: Number,
        state: Boolean
    },
    components: {
        VueTailwindDatepicker
    },
    methods: {
        getDate(event) {

        },
        setDelete() {
            let seance = JSON.parse(JSON.stringify({...this.seance}))
            seance.on_delete = true
            seance.index = this.index 
            this.$emit('onUpdSeance', seance)
        } 
    },
    mounted() {
        this.seanceTime.startDate = this.$props.seance.date_start
        this.seanceTime.endDate = this.$props.seance.date_end
        console.log(this.seanceTime)
    },
    emits: ['onUpdSeance'],
    watch: {
        seanceTime(date) {
            this.$emit('onUpdSeance', {
                index: this.index,
                id: this.seance.id,
                date_start: this.seanceTime.startDate,
                date_end: this.seanceTime.endDate,
            })
        }
    }

    
}
</script>
<style lang="">
    
</style>