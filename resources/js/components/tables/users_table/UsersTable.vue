<template lang="">
    <!-- <div v-for="user in users">
        {{user.name}}
    </div> -->
    <div class="relative overflow-x-auto border border-gray-300 dark:border-gray-700 dark:border-gra rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    id
                </th>
                <th scope="col" class="px-6 py-3">
                    Имя
                </th>
                <th scope="col" class="px-6 py-3">
                    Роль
                </th>
                <th scope="col" class="px-6 py-3">
                    Создан
                </th>
                <th scope="col" class="px-6 py-3">
                    Обновлён
                </th>
                <th scope="col" class="px-6 py-3">
                    <span class="sr-only">Edit</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" v-for="user in users">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{user.id}}
                </th>
                <td class="px-6 py-4">
                    <label>
                        <h1 class="dark:text-gray-200 text-base">{{user.name}}</h1>
                        <p class="dark:text-gray-400 text-ml">{{user.email}}</p>
                    </label>
                </td>
                <td v-if="user.roles.length" class="px-6 py-4">
                    {{user.roles[0].name}}
                </td>
                <td v-if="!user.roles.length" class="px-6 py-4">
                    Нету
                </td>
                <td class="px-6 py-4">
                    {{user.created_at.slice(0,19).replace("T", '  ')}}
                </td>
                <td class="px-6 py-4">
                    {{user.updated_at.slice(0,19).replace("T", '  ')}}
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline">Удалить</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</template>
<script>
export default {
    name: 'UsersTable',
    props: ['users']
}
</script>
<style lang="">
    
</style>