<template lang="">
    <div class="border dark:bg-gray-800 bg-gray-200 border-gray-300 dark:border-gray-700 shadow rounded grid grid-cols-2 gap-6 p-6">
        <input v-model="userName" type="text" name="name" id="name" placeholder="Имя пользователя" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <input v-model="userEmail" type="text" name="name" id="name" placeholder="Почта пользователя" class=" rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50">
        <VueDatePicker v-model="userCreated" range model-type="yyyy-MM-dd HH:mm:ss" :class="themeState ? 'w-full h-full mt-1 dp_theme_dark' : 'w-full h-full mt-1 dp_theme_light'" placeholder="Дата создания" />
        <VueDatePicker v-model="userUpdated" range model-type="yyyy-MM-dd HH:mm:ss" :class="themeState ? 'w-full h-full mt-1 dp_theme_dark' : 'w-full h-full mt-1 dp_theme_light'" placeholder="Дата обновления" />
    </div>
</template>
<script>
import { useUsersFilterStore } from '../../../stores/UsersFilterStore';
import { mapState, mapActions } from 'pinia';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { useDark } from '@vueuse/core'

export default {
    name: 'UsersFilter',
    setup() {
        const themeState = useDark()
        return {
            themeState
        }
    },
    data() {
        return {
            userName: this.getName(),
            userEmail: this.getEmail(),
            userCreated: [
                this.getCreatedDate().split('~')[0].slice(0,19).replace("T", ' '),
                this.getCreatedDate().split('~')[1].slice(0,19).replace("T", ' ')
            ],
            userUpdated: [
                this.getCreatedDate().split('~')[0].slice(0,19).replace("T", '  '),
                this.getCreatedDate().split('~')[1].slice(0,19).replace("T", '  ')
            ],
            userLocation: this.getLocation()
        }
    },
    components: {
        VueDatePicker
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
        userCreated(newDateCerated) {
            if(newDateCerated) {
                this.setCreatedDate(newDateCerated[0] + '~' + newDateCerated[1])
            } else {
                this.setCreatedDate(['~'])
            }
        },
        userUpdated(newDateUpdated) {
            if(newDateUpdated) {
                this.setUpdatedDate(newDateUpdated[0] + '~' + newDateUpdated[1])
            } else {
                this.setUpdatedDate(['~'])
            }
        },
        userLocation(newLocation, oldLocation) {
            this.setLocation(newLocation)
        },
    },
}
</script>
<style>
 /* Светлый стиль datepicker */
 .dp_theme_light {
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
    .dp_theme_dark {
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
    }
</style>