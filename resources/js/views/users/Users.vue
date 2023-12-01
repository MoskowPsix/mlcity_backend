<template lang="">
    <div>
        Users
    </div>
</template>
<script>
import { mapActions, mapState } from 'pinia'
import { useUsersStore } from './../../stores/UsersStore'
import {useUsersQueryBuilderStore} from './../../stores/UsersQueryBuilderStore'


export default {
    name: 'Users',
    data() {
        return {
            users: null,
        }
    },
    computed: {
        ...mapState(useUsersQueryBuilderStore, {
            queryUsers: 'users'
        })
    },
    methods: {
        ...mapActions(useUsersStore, {
            getAllUsers: 'getUsers'
        }),
        getUsers() {
            this.getAllUsers(this.queryUsers)
            .then(response => {
                console.log(response.data)
            })
            .catch()
        }

    },
    mounted() {
        this.getUsers()
    },

}
</script>
<style lang="">
    
</style>