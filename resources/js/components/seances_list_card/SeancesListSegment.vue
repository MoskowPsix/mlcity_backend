<template lang="">
    <div
        :class="{
            'border-blue-700/70 border rounded-lg':
                $props.changedSeanceIds != null &&
                $props.changedSeanceIds.includes(seance.id),
            'border-green-600 border rounded-lg':
                seance.new != null && seance.new,
            'border-red-600 border rounded-lg': seance.delete,
        }"
        class="bg-gray-900/5 dark:border-gray-700 dark:bg-gray-900/40 rounded-lg lg:max-w-[90%] lg:ml-[auto] mr-[auto] mb-8 lg:pl-8 lg:pr-8 lg:pb-4 lg:pt-4"
        @click="clickElement"
    >
        <div
            v-if="state"
            class="h-5 w-5 dark:bg-red-900 ml-[95%] dark:text-red-500 bg-red-400 text-red-800 cursor-pointer rounded-lg"
            @click.prevent="setDelete"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                data-slot="icon"
                class="w-2 h-4 mx-auto my-auto"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                />
            </svg>
        </div>
        <div
            v-if="seance"
            class="min-w-[100%] flex justify-between"
        >
            <div
                class="mt-1 flex border rounded-lg dark:border-gray-700 dark:bg-gray-900/30 bg-gray-100"
            >
                <div class="-rotate-90 h-8 mt-4 text-lg font-medium">
                    {{ new Date(seance.date_start).getFullYear() }}
                </div>
                <div class="flex flex-col">
                    <div
                        :class="
                            new Date(seance.date_start).getDay() === 5 ||
                            new Date(seance.date_start).getDay() === 6
                                ? 'text-red-600 text-4xl font-bold'
                                : 'text-gray-500 text-4xl font-bold'
                        "
                    >
                        {{ new Date(seance.date_start).getDate() }}
                    </div>
                    <div class="text-sm mx-auto">
                        <label
                            v-if="new Date(seance.date_start).getMonth() == 0"
                        >
                            Янв
                        </label>
                        <label
                            v-if="new Date(seance.date_start).getMonth() == 1"
                        >
                            Фев
                        </label>
                        <label
                            v-if="new Date(seance.date_start).getMonth() == 2"
                        >
                            Март
                        </label>
                        <label
                            v-if="new Date(seance.date_start).getMonth() == 3"
                        >
                            Апр
                        </label>
                        <label
                            v-if="new Date(seance.date_start).getMonth() == 4"
                        >
                            Май
                        </label>
                        <label
                            v-if="new Date(seance.date_start).getMonth() == 5"
                        >
                            Июнь
                        </label>
                        <label
                            v-if="new Date(seance.date_start).getMonth() == 6"
                        >
                            Июль
                        </label>
                        <label
                            v-if="new Date(seance.date_start).getMonth() == 7"
                        >
                            Авг
                        </label>
                        <label
                            v-if="new Date(seance.date_start).getMonth() == 8"
                        >
                            Сент
                        </label>
                        <label
                            v-if="new Date(seance.date_start).getMonth() == 9"
                        >
                            Окт
                        </label>
                        <label
                            v-if="new Date(seance.date_start).getMonth() == 10"
                        >
                            Нояб
                        </label>
                        <label
                            v-if="new Date(seance.date_start).getMonth() == 11"
                        >
                            Дек
                        </label>
                    </div>
                </div>
                <div class="text-xl p-2 my-auto">
                    <!-- <h1>{{new Date(seance.date_start).getUTCHours()}}:{{new Date(seance.date_start).getUTCMinutes() ? new Date(seance.date_start).getUTCMinutes() : '00'}}</h1> -->
                    <h1>{{
                        new Date(seance.date_start).toLocaleTimeString('ru', {
                            hour: '2-digit',
                            minute: '2-digit',
                        })
                    }}</h1>
                </div>
            </div>
            <div
                class="mt-1 flex flex-row border rounded-lg dark:border-gray-700 dark:bg-gray-900/30 bg-gray-100"
            >
                <div class="-rotate-90 h-8 mt-4 text-lg font-medium">
                    {{ new Date(seance.date_end).getFullYear() }}
                </div>
                <div class="flex flex-col">
                    <div
                        :class="
                            new Date(seance.date_end).getDay() === 5 ||
                            new Date(seance.date_end).getDay() === 6
                                ? 'text-red-600 text-4xl font-bold'
                                : 'text-gray-500 text-4xl font-bold'
                        "
                    >
                        {{ new Date(seance.date_end).getDate() }}
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
                        <label
                            v-if="new Date(seance.date_end).getMonth() == 10"
                        >
                            Нояб
                        </label>
                        <label
                            v-if="new Date(seance.date_end).getMonth() == 11"
                        >
                            Дек
                        </label>
                    </div>
                </div>
                <div class="text-xl p-2 my-auto">
                    <!-- {{new Date(seance.date_end)}} -->
                    <!-- {{new Date(seance.date_end).getUTCHours()}}:{{new Date(seance.date_end).getUTCMinutes() ? new Date(seance.date_end).getUTCMinutes() : '00'}} -->
                    {{
                        new Date(seance.date_end).toLocaleTimeString('ru', {
                            hour: '2-digit',
                            minute: '2-digit',
                        })
                    }}
                </div>
            </div>
        </div>
        <div v-if="seance && state">
            <VueDatePicker
                v-if="state"
                v-model="seanceTime"
                locale="ru"
                cancel-text="Отменить"
                select-text="Добавить"
                range
                model-type="yyyy-MM-dd HH:mm:ss"
                :class="
                    themeState
                        ? 'w-full h-full mt-1 dp_theme_dark'
                        : 'w-full h-full mt-1 dp_theme_light'
                "
                placeholder="Дата и время события"
                @update:model-value="handleDate"
            />
        </div>
    </div>
</template>
<script>
    // import VueTailwindDatepicker from 'vue-tailwind-datepicker'
    import { ref } from 'vue'
    import VueDatePicker from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'
    import { useDark } from '@vueuse/core'

    export default {
        name: 'SeancesListSegment',
        components: {
            VueDatePicker,
        },
        props: {
            seance: {
                type: Object,
                default() {
                    return {}
                },
            },
            index: {
                type: Number,
                default: null,
            },
            state: {
                type: Boolean,
                default: false,
            },
            location: {
                type: Object,
                default: null,
            },
            changedSeanceIds: {
                type: Array,
                default: null,
            },
        },
        emits: ['onUpdSeance', 'onClickSeance'],
        // getDate(date) {
        //     return date[0] + '~' + date[1]
        // },
        setup() {
            const themeState = useDark()
            const seanceTime = ref([])
            return {
                seanceTime,
                themeState,
            }
        },
        watch: {
            seance() {
                this.seanceTime = [
                    JSON.parse(JSON.stringify(this.$props.seance.date_start)),
                    JSON.parse(JSON.stringify(this.$props.seance.date_end)),
                ]
            },
            // seanceTime(date) {
            //     this.$emit('onUpdSeance', {
            //         index: this.index,
            //         id: this.seance.id,
            //         date_start: JSON.parse(JSON.stringify(this.seanceTime[0])),
            //         date_end: JSON.parse(JSON.stringify(this.seanceTime[1])),
            //     })
            // }
        },
        mounted() {
            // eslint-disable-next-line vue/no-mutating-props
            this.seance.date_start =
                this.$helpers.OutputCurentTime.outputCurentTime(
                    this.$props.seance.date_start,
                    this.$props.location.time_zone,
                )
            // eslint-disable-next-line vue/no-mutating-props
            this.seance.date_end =
                this.$helpers.OutputCurentTime.outputCurentTime(
                    this.$props.seance.date_end,
                    this.$props.location.time_zone,
                )
            this.seanceTime = [
                JSON.parse(JSON.stringify(this.$props.seance.date_start)),
                JSON.parse(JSON.stringify(this.$props.seance.date_end)),
            ]
        },
        methods: {
            handleDate(modelData) {
                this.$emit('onUpdSeance', {
                    index: this.index,
                    id: this.seance.id,
                    date_start: JSON.parse(JSON.stringify(modelData[0])),
                    date_end: JSON.parse(JSON.stringify(modelData[1])),
                })
            },
            clickElement() {
                this.$emit('onClickSeance', {
                    id: this.seance.id,
                })
            },
            setDelete() {
                let seance = JSON.parse(JSON.stringify({ ...this.seance }))
                seance.on_delete = true
                seance.index = this.index
                this.$emit('onUpdSeance', seance)
            },
        },
    }
</script>
<style lang="">
    /* .dp_theme_light {
    --dp-background-color: #fff;
    --dp-text-color: #212121;
    --dp-hover-color: #f3f3f3;
    --dp-hover-text-color: #212121;
    --dp-hover-icon-color: #959595;
    --dp-primary-color: #1976d2;
    --dp-primary-disabled-color: #6bacea;
    --dp-primary-text-color: #f8f5f5;
    --dp-secondary-color: #c0c4cc;
    --dp-border-color: #ddd;
    --dp-menu-border-color: #ddd;
    --dp-border-color-hover: #aaaeb7;
    --dp-disabled-color: #f6f6f6;
    --dp-scroll-bar-background: #f3f3f3;
    --dp-scroll-bar-color: #959595;
    --dp-success-color: #76d275;
    --dp-success-color-disabled: #a3d9b1;
    --dp-icon-color: #959595;
    --dp-danger-color: #ff6f60;
    --dp-marker-color: #ff6f60;
    --dp-tooltip-color: #fafafa;
    --dp-disabled-color-text: #8e8e8e;
    --dp-highlight-color: rgb(25 118 210 / 10%);
    --dp-range-between-dates-background-color: var(--dp-hover-color, #f3f3f3);
    --dp-range-between-dates-text-color: var(--dp-hover-text-color, #212121);
    --dp-range-between-border-color: var(--dp-hover-color, #f3f3f3);
}
    /* Тёмный стиль datepicker */
    /* .dp_theme_dark {
    --dp-background-color: #2b3444;
    --dp-text-color: #fff;
    --dp-hover-color: #484848;
    --dp-hover-text-color: #fff;
    --dp-hover-icon-color: #959595;
    --dp-primary-color: #005cb2;
    --dp-primary-disabled-color: #61a8ea;
    --dp-primary-text-color: #fff;
    --dp-secondary-color: #a9a9a9;
    --dp-border-color: #323c4c;
    --dp-menu-border-color: #2d2d2d;
    --dp-border-color-hover: #aaaeb7;
    --dp-disabled-color: #737373;
    --dp-disabled-color-text: #d0d0d0;
    --dp-scroll-bar-background: #212121;
    --dp-scroll-bar-color: #484848;
    --dp-success-color: #00701a;
    --dp-success-color-disabled: #428f59;
    --dp-icon-color: #959595;
    --dp-danger-color: #e53935;
    --dp-marker-color: #e53935;
    --dp-tooltip-color: #3e3e3e;
    --dp-highlight-color: rgb(0 92 178 / 20%);
    --dp-range-between-dates-background-color: var(--dp-hover-color, #484848);
    --dp-range-between-dates-text-color: var(--dp-hover-text-color, #fff);
    --dp-range-between-border-color: var(--dp-hover-color, #fff);
}  */
</style>
