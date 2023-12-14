<template lang="">
    <div v-if="seance" class="grid grid-cols-2">
        <div class=" mt-1 flex flex-row border rounded-lg dark:border-gray-700 mr-1">
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
        <div class=" mt-1 flex flex-row border rounded-lg dark:border-gray-700">
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
</template>
<script>
export default {
    name: 'SeancesListSegment',
    props: {
        seance: Object
    }
}
</script>
<style lang="">
    
</style>