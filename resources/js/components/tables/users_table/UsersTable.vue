<template lang="">
    <!-- <div v-for="user in users">
        {{user.name}}
    </div> -->
    <div
        class="relative z-10 overflow-x-auto border border-gray-300 dark:border-gray-700 dark:border-gra rounded-lg"
    >
        <table
            class="border border-gray-300 dark:border-gray-700 rounded-lg w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
        >
            <thead
                class="border border-gray-300 dark:border-gray-700 rounded-lg text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-800 dark:text-gray-400"
            >
                <tr>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        id
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Имя
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Роль
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Создан
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Обновлён
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="user in users"
                    :key="user.id"
                    class="transition active:scale-95 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer"
                    @click.prevent="emitUser(user)"
                >
                    <th
                        scope="row"
                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                    >
                        {{ user.id }}
                    </th>
                    <td class="px-6 py-4">
                        <label>
                            <h1 class="dark:text-gray-200 text-base">{{
                                user.name
                            }}</h1>
                            <p class="dark:text-gray-400 text-ml">{{
                                user.email
                            }}</p>
                        </label>
                    </td>
                    <td
                        v-if="user.roles.length"
                        class="px-6 py-4"
                    >
                        {{ user.roles[0].name }}
                    </td>
                    <td
                        v-if="!user.roles.length"
                        class="px-6 py-4"
                    >
                        Нету
                    </td>
                    <td class="px-6 py-4">
                        {{ getCurrentTime(user.created_at) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ getCurrentTime(user.updated_at) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        name: 'UsersTable',
        props: {
            users: {
                type: Object,
                default() {
                    return {}
                },
            },
        },
        emits: ['user'],
        methods: {
            emitUser(user) {
                this.$emit('user', user)
            },
            getCurrentTime(time) {
                return this.$helpers.DateHelp.getCurrentFormate(time)
            },
        },
    }
</script>
<style lang=""></style>
