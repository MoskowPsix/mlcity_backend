<template lang="">
    <div class="border dark:bg-gray-800 bg-gray-200 border-gray-300 dark:border-gray-700 shadow rounded grid grid-cols-2 gap-6 p-6">
        <input v-model="userName" type="text" name="name" id="name" placeholder="Имя пользователя" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <input v-model="userEmail" type="text" name="name" id="name" placeholder="Почта пользователя" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <VueTailwindDatepicker v-model="userCreated" placeholder="Когда был зарегистрирован" />
        <VueTailwindDatepicker v-model="userUpdated" placeholder="Когда был обновлён" />
    </div>
</template>
<script>
import { useUsersFilterStore } from '../../../stores/UsersFilterStore';
import { mapState, mapActions } from 'pinia';
import VueTailwindDatepicker from 'vue-tailwind-datepicker'

export default {
    name: 'UsersFilter',
    data() {
        return {
            userName: this.getName(),
            userEmail: this.getEmail(),
            userCreated: this.getCreatedDate(),
            userUpdated: this.getUpdatedDate(),
            userLocation: this.getLocation()
        }
    },
    components: {
        VueTailwindDatepicker
    },
    computed: {
        // ...mapState(useUsersFilterStore, ['name'])
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
            this.setCreatedDate(newDateCerated)
        },
        userUpdated(newDateUpdated, oldDate) {
            this.setUpdatedDate(newDateUpdated)

        },
        userLocation(newLocation, oldLocation) {
            this.setLocation(newLocation)
        },
    },
}
</script>
<style lang="">
    
</style>