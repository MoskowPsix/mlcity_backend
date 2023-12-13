<template lang="">
    <div v-if="seance" class="grid grid-cols-2">
        <div class=" mt-1 flex flex-row border rounded-lg dark:border-gray-700 mr-1">
            <div class=" -rotate-90 h-8 mt-4 text-lg font-medium">
                {{new Date(seance.dateStart).getFullYear()}}
            </div>
            <div class="flex flex-col">
                <div :class="new Date(seance.dateStart).getDay() === 5 || new Date(seance.dateStart).getDay() === 6 ? 'text-red-600 text-4xl font-bold' : 'text-gray-500 text-4xl font-bold'">
                    {{new Date(seance.dateStart).getDate()}}
                </div>
                <div class="text-sm mx-auto">
                    <label v-if="new Date(seance.dateStart).getMonth() == 0">
                        Янв
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 1">
                        Фев
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 2">
                        Март
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 3">
                        Апр
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 4">
                        Май
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 5">
                        Июнь
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 6">
                        Июль
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 7">
                        Авг
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 8">
                        Сент
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 9">
                        Окт
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 10">
                        Нояб
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 11">
                        Дек
                    </label>
                </div>
            </div>
            <div class="text-xl p-2 my-auto">
                {{new Date(seance.dateStart).getUTCHours()}}:{{new Date(seance.dateStart).getUTCMinutes() ? new Date(seance.dateStart).getUTCMinutes() : '00'}}
            </div>
        </div>
        <div class=" mt-1 flex flex-row border rounded-lg dark:border-gray-700">
            <div class=" -rotate-90 h-8 mt-4 text-lg font-medium">
                {{new Date(seance.dateEnd).getFullYear()}}
            </div>
            <div class="flex flex-col">
                <div :class="new Date(seance.dateEnd).getDay() === 5 || new Date(seance.dateEnd).getDay() === 6 ? 'text-red-600 text-4xl font-bold' : 'text-gray-500 text-4xl font-bold'">
                    {{new Date(seance.dateEnd).getDate()}}
                </div>
                <div class="text-sm mx-auto">
                    <label v-if="new Date(seance.dateEnd).getMonth() == 0">
                        Янв
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 1">
                        Фев
                    </label>
                    <label v-if="new Date(seance.dateStart).getMonth() == 2">
                        Март
                    </label>
                    <label v-if="new Date(seance.dateEnd).getMonth() == 3">
                        Апр
                    </label>
                    <label v-if="new Date(seance.dateEnd).getMonth() == 4">
                        Май
                    </label>
                    <label v-if="new Date(seance.dateEnd).getMonth() == 5">
                        Июнь
                    </label>
                    <label v-if="new Date(seance.dateEnd).getMonth() == 6">
                        Июль
                    </label>
                    <label v-if="new Date(seance.dateEnd).getMonth() == 7">
                        Авг
                    </label>
                    <label v-if="new Date(seance.dateEnd).getMonth() == 8">
                        Сент
                    </label>
                    <label v-if="new Date(seance.dateEnd).getMonth() == 9">
                        Окт
                    </label>
                    <label v-if="new Date(seance.dateEnd).getMonth() == 10">
                        Нояб
                    </label>
                    <label v-if="new Date(seance.dateEnd).getMonth() == 11">
                        Дек
                    </label>
                </div>
            </div>
            <div class="text-xl p-2 my-auto">
                {{new Date(seance.dateEnd).getUTCHours()}}:{{new Date(seance.dateEnd).getUTCMinutes() ? new Date(seance.dateEnd).getUTCMinutes() : '00'}}
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'SeancesListSegment',
    props: {
        seance: Array
    }
}
</script>
<style lang="">
    
</style>