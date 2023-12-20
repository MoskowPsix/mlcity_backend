<template lang="">
    <div>
        <div class="border dark:bg-gray-800 bg-gray-200 border-gray-300 dark:border-gray-700 shadow rounded grid grid-cols-4 gap-6 p-6 dark:text-gray-300">
        <input v-model="eventName" type="text" name="name" id="name" placeholder="Название" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <input v-model="eventSponsor" type="text" name="sponsor" id="sponsor" placeholder="Спонсор мероприятия" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <input v-model="eventText" type="text" name="text" id="text" placeholder="Поиск по тексту" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <VueTailwindDatepicker v-model="eventDate" :start-from="new Date()" placeholder="Дата начала и конца" />
        <div class="flex border p-1 rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
            <div>
                <select class="h-6" v-model="eventStatuses" data-te-select-init>
                    <option v-for="status in statuses" :value="status.name">{{status.name}}</option>
                </select>
                <label data-te-select-label-ref>статусы</label>
            </div>
        </div>
        <div class="mb-[0.125rem] block min-h-[1.5rem] pl-7  border rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
                <input
                    v-bind:true-value="1"
                    v-bind:false-value="0"
                    class="relative float-left -ml-[0.5rem] mr-[6px] mt-[0.5rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                    type="checkbox"
                    v-model="eventStatusLast"
                    id="checkboxDefault" />
                <label
                    class="inline-block pl-[0.15rem] mt-[0.4rem] hover:cursor-pointer"
                    for="checkboxDefault">
                    Последний статус
                </label>
            </div>
        <input v-model="eventUser" type="text" name="user" id="user" placeholder="Имя или почта автора" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">

    </div>
    </div>
</template>
<script>
import { useEventFilterStore } from '../../../stores/EventFilterStore'
import { useStatusStore} from '../../../stores/StatusStore'
import { mapState, mapActions } from 'pinia'
import { catchError, tap, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'
import { Select, initTE } from "tw-elements";
import VueTailwindDatepicker from 'vue-tailwind-datepicker'

export default {
    name: 'EventFilter',
    setup() {
        const destroy$ =  new Subject()
        const formatter = {
            date: 'YYYY-MM-DD hh:mm:ss',
            month: 'MM',
        }
        return {
            destroy$,
            formatter
        }
    },
    data() {
        return {
        eventName: this.getEventName(),
        eventSponsor: this.getEventSponsor(),
        eventText: this.getEventText(),
        eventDate:{
                startDate: this.getEventDate().split('~')[0].slice(0,19).replace("T", ' '),
                endDate: this.getEventDate().split('~')[1].slice(0,19).replace("T", ' ')
            },
        eventStatuses: this.getEventStatuses(),
        eventStatusLast: this.getEventStatusLast(),
        eventUser: this.getEventUser(),
        statuses: [],
        allStatuses: []
        }
    },
    components: {
        VueTailwindDatepicker
    },
    methods: {
        ...mapActions(useEventFilterStore, [
        'setEventName',
        'getEventName',
        'setEventSponsor',
        'getEventSponsor',
        'getEventDate',
        'setEventDate',
        'setEventText',
        'getEventText',
        'setEventStatuses',
        'getEventStatuses',
        'setEventStatusLast',
        'getEventStatusLast',
        'setEventUser',
        'getEventUser',
        ]),
        ...mapActions(useStatusStore, ['getStatuses']),
        getAllStatuses() {
            this.getStatuses().pipe(
                map(response => {
                    if (response.data.statuses.length) {
                        this.statuses = response.data.statuses
                    } else {
                        this.statuses = []
                    }
                }),
                catchError(err => {
                    console.log(err)
                    return of(EMPTY)
                }),
                takeUntil(this.destroy$)
            ).subscribe()
        }
    },
    mounted() {
        initTE({ Select }, { allowReinits: true })
        this.getAllStatuses()
    },
    watch: {
        eventName(name) {
            if (name.length > 3) {
                this.setEventName(name)
            } else if(name == 0) {
                this.setEventName(name)
            }
        },
        eventSponsor(sponsor) {
            if (sponsor.length > 3) {
                this.setEventSponsor(sponsor)
            } else if(sponsor == 0) {
                this.setEventSponsor(sponsor)
            }
        },
        eventDate(date) {
            this.setEventDate(date.startDate + '~' + date.endDate)
        },
        eventText(text) {
            if (text.length > 3) {
                this.setEventText(text)
            } else if(text == 0) {
                this.setEventText(text)
            }
        },
        eventStatuses(status) {
            this.setEventStatuses(status)
        },
        eventStatusLast(status) {
            this.setEventStatuses(status)
        },
        eventUser(user) {
            if (user.length > 3) {
                this.setEventUser(user)
            } else if(user == 0) {
                this.setEventUser(user)
            }
        },
    },
}
</script>
<style lang="">
    
</style>