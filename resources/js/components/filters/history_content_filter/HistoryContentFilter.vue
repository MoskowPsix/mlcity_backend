<template lang="">
    <div class="border dark:bg-gray-800 bg-gray-200 border-gray-300 dark:border-gray-700 shadow rounded grid grid-cols-4 gap-6 p-6 dark:text-gray-300">
        <input v-model="contentName" type="text" name="name" id="name" placeholder="Название" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <input v-model="contentSponsor" type="text" name="sponsor" id="sponsor" placeholder="Спонсор мероприятия" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <input v-model="contentSearchText" type="text" name="text" id="text" placeholder="Поиск по тексту" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <input v-model="contentUser" type="text" name="user" id="user" placeholder="Имя автора" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <VueTailwindDatepicker v-model="contentDate" placeholder="Дата начала и конца" />
        <select v-model="contentStatuses" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
            <option v-for="status in statuses" :value="status.name">
                {{ status.name }}
            </option>
            <option :value="''">Все</option>    
        </select>

        <input type="text" name="name" id="name" placeholder="Имя пользователя" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <input type="text" name="name" id="name" placeholder="Имя пользователя" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">

    </div>
</template>
<script>
import { mapActions } from 'pinia'
import { useHistoryContentsFilterStore } from '../../../stores/HistoryContentFilterStore'
import { useStatusStore} from '../../../stores/StatusStore'
import { catchError, tap, map, retry, delay, takeUntil} from 'rxjs/operators'
import { of, EMPTY, Subject } from 'rxjs'

import VueTailwindDatepicker from 'vue-tailwind-datepicker'


export default {
    name: 'HistoryContentFilter',
    components: {
        VueTailwindDatepicker
    },
    setup() {
        const destroy$ =  new Subject()
        return {
            destroy$,
        }
    },
    data() {
        return {
            contentName: this.getContentName(),
            contentDate: this.getContentDate(),
            contentSponsor: this.getContentSponsor(),
            contentSearchText: this.getContentText(),
            contentStatuses: this.getContentStatuses(),
            contentStatusLast: this.getContentStatusLast(),
            contentUser: this.getContentUser(),
            statuses: [],
        }
    },
    methods: {
        ...mapActions(useHistoryContentsFilterStore, [
            'setContentName',
            'setContentDate',
            'setContentSponsor',
            'setContentText',
            'setContentStatuses',
            'setContentStatusLast',
            'setContentUser',
            'getContentName',
            'getContentDate',
            'getContentSponsor',
            'getContentText',
            'getContentStatuses',
            'getContentStatusLast',
            'getContentUser',
        ]),
        ...mapActions(useStatusStore, ['getStatuses']),
        getAllStatuses() {
            this.getStatuses().pipe(
                map(response => {
                    this.statuses = response.data.statuses
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
        this.getAllStatuses()
    },
    watch: {
        contentName(name) {
            if (name.length > 3) {
                this.setContentName(name)
            } else if (name == 0) {
                this.setContentName(name)
            }
        },
        contentDate(date) {       
             this.setContentDate(date)
        },
        contentSponsor(sponsor) {   
            if (sponsor.length > 3) {     
                this.setContentSponsor(sponsor)
            } else if (sponsor == 0) {
                this.setContentSponsor(sponsor)
            }
        },
        contentSearchText(text) {  
            if (text.length > 3) {        
                this.setContentText(text)
            } else if (text == 0) {
                this.setContentText(text)
            }
        },
        contentStatuses(status) {        
            this.setContentStatuses(status)
        },
        contentStatusLast(status) {       
             this.setContentStatusLast(status)
        },
        contentUser(user) {  
            if (user.length > 3) {      
                this.setContentUser(user)
            } else if (user == 0) {
                this.setContentUser(user)
            }
        },
    }
}

</script>
<style lang="">
    
</style>