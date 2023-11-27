<template lang="">
    <div>
        <input v-model.lazy="userName" type="text" name="name" id="name" placeholder="Имя пользователя">
    </div>
</template>
<script>
import { useUsersFilterStore } from '../../../stores/UsersFilterStore';
import { mapState, mapActions } from 'pinia';
export default {
    name: 'UsersFilter',
    data() {
        return {
            userName: null
        }
    },
    computed: {
        ...mapState(useUsersFilterStore, ['name'])
    },
    methods: {
        ...mapActions(useUsersFilterStore, ['setName'])
    },
    watch: {
        userName(newName, oldName) {
            if (newName.length > 3) {
                console.log(newName, oldName)
                this.setName(newName)
            }
        }
    },
}
</script>
<style lang="">
    
</style>