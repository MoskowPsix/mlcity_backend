<template lang="">
    <div class="border dark:bg-gray-800 bg-gray-200 border-gray-300 dark:border-gray-700 shadow rounded grid grid-cols-2 gap-6 p-6">
        <input v-model="userName" type="text" name="name" id="name" placeholder="Имя пользователя" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <input v-model="userEmail" type="text" name="name" id="name" placeholder="Почта пользователя" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <VueTailwindDatepicker v-model="userCreated" :formatter="formatter" placeholder="Когда был зарегистрирован" />
        <VueTailwindDatepicker v-model="userUpdated" :formatter="formatter" placeholder="Когда был обновлён" />
    </div>
</template>
<script>
import { useUsersFilterStore } from '../../../stores/UsersFilterStore';
import { mapState, mapActions } from 'pinia';
import VueTailwindDatepicker from 'vue-tailwind-datepicker'

export default {
    name: 'UsersFilter',
    setup() {
        const formatter = {
            date: 'YYYY-MM-DD hh:mm:ss',
            month: 'MM',
        }
        return {
            formatter
        }
    },
    data() {
        return {
            userName: this.getName(),
            userEmail: this.getEmail(),
            userCreated: {
                    startDate: this.getCreatedDate().split('~')[0].slice(0,19).replace("T", ' '),
                    endDate: this.getCreatedDate().split('~')[1].slice(0,19).replace("T", ' ')
                },
            userUpdated: {
                    startDate: this.getCreatedDate().split('~')[0].slice(0,19).replace("T", '  '),
                    endDate: this.getCreatedDate().split('~')[1].slice(0,19).replace("T", '  ')
                },
            userLocation: this.getLocation()
        }
    },
    components: {
        VueTailwindDatepicker
    },
    methods: {
        ...mapActions(useUsersFilterStore, [
            'setName',
            'setEmail',
            'setCreatedDate',
            'setUpdatedDate',
            'setLocation',
            'getName',
            'getEmail',
            'getCreatedDate',
            'getUpdatedDate',
            'getLocation',
        ]),
    },
    watch: {
        userName(newName, oldName) {
            if (newName.length > 3) {
                this.setName(newName)
            }
            if(newName.length == 0) {
                this.setName('')
            }
        },
        userEmail(newEmail, oldEmail) {
            if (newEmail.length > 3) {
                this.setEmail(newEmail)
            }
            if(newName.length == 0) {
                this.setEmail('')
            }
        },
        userCreated(newDateCerated, oldDate) {
            this.setCreatedDate(newDateCerated.startDate + '~' + newDateCerated.endDate)
        },
        userUpdated(newDateUpdated, oldDate) {
                this.setUpdatedDate(newDateUpdated.startDate + '~' + newDateUpdated.endDate)
        },
        userLocation(newLocation, oldLocation) {
            this.setLocation(newLocation)
        },
    },
}
</script>
<style lang="">
    
</style>