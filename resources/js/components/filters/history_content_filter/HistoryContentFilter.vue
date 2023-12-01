<template lang="">
    <div class="border dark:bg-gray-800 bg-gray-200 border-gray-300 dark:border-gray-700 shadow rounded grid grid-cols-2 gap-6 p-6">
        <input type="text" name="name" id="name" placeholder="Имя пользователя" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <input type="text" name="name" id="name" placeholder="Имя пользователя" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <input type="text" name="name" id="name" placeholder="Имя пользователя" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <input type="text" name="name" id="name" placeholder="Имя пользователя" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">

    </div>
</template>
<script>
import { mapActions } from 'pinia'
import { useHistoryContentsFilterStore } from '../../../stores/HistoryContentFilterStore'


export default {
    name: 'HistoryContentFilter',
    data() {
        return {
            // contentName: ,
            // contentDate: ,
            // contentSponsor: ,
            // contentSearchText: ,
            // contentStatuses: ,
            // contentStatusLast: ,
            // contentUser: ,
        }
    },
    methods: {
        // ...mapActions(),
    },
    watch: {

    }
}

        // setContentName(name)
        // setContentDate(date)
        // setContentSponsor(sponsor)
        // setContentText(text)
        // setContentStatuses(status)
        // setContentStatusLast(status)
        // setContentUser(user)

        // getContentName() 
        // getContentDate()
        // getContentSponsor()
        // getContentText()
        // getContentStatuses()
        // getContentStatusLast()
        // getContentUser()

</script>
<style lang="">
    
</style>