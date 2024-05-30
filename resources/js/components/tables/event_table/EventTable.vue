<template lang="">
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
                        Тип
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Статус
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
                        Автор
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Дата начала
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Дата окончания
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Изменено
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Обновлено
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="event in events"
                    :key="event.id"
                    class="transition active:scale-95 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer"
                    @click.prevent="emitEvent(event)"
                >
                    <th
                        scope="row"
                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                    >
                        {{ event.id }}
                    </th>
                    <td class="px-6 py-4">
                        <div class="flex justify-between">
                            <!-- <svg v-if="event.history_contentable_type === 'App\\Models\\Event'" aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white enable-background:new 0 0 24 24" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.53 11.06L15.47 10l-4.88 4.88-2.12-2.12-1.06 1.06L10.59 17l5.94-5.94zM19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"></path></svg>

                        <svg v-if="event.history_contentable_type === 'App\\Models\\Sight'" aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 500 600" xmlns="http://www.w3.org/2000/svg">
                        <path d="M 501.62 92.11 L 267.24 2.04 a 31.958 31.958 0 0 0 -22.47 0 L 10.38 92.11 A 16.001 16.001 0 0 0 0 107.09 V 144 c 0 8.84 7.16 16 16 16 h 480 c 8.84 0 16 -7.16 16 -16 v -36.91 c 0 -6.67 -4.14 -12.64 -10.38 -14.98 Z M 64 192 v 160 H 48 c -8.84 0 -16 7.16 -16 16 v 48 h 448 v -48 c 0 -8.84 -7.16 -16 -16 -16 h -16 V 192 h -64 v 160 h -96 V 192 h -64 v 160 h -96 V 192 H 64 Z m 432 256 H 16 c -8.84 0 -16 7.16 -16 16 v 32 c 0 8.84 7.16 16 16 16 h 480 c 8.84 0 16 -7.16 16 -16 v -32 c 0 -8.84 -7.16 -16 -16 -16 Z"></path></svg> -->

                            <svg
                                v-if="event.cult_id"
                                viewBox="0 0 128 128"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                fill="currentColor"
                                aria-hidden="true"
                                class="flex-shrink-0 w-6 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            >
                                <path
                                    d="M110.55 117.41L76.92 63.69l29.73-44.82c.45-.69.5-1.57.1-2.3a2.222 2.222 0 0 0-1.97-1.18H80.34c-.74 0-1.45.38-1.86 1L51.49 57.08V17.64c0-1.24-1.01-2.24-2.24-2.24h-21.9c-1.24 0-2.24 1-2.24 2.24V118.6c0 1.24 1 2.24 2.24 2.24h21.9c1.23 0 2.24-1 2.24-2.24V70.55l30.82 49.24c.41.65 1.13 1.05 1.9 1.05h24.44c.81 0 1.57-.44 1.96-1.16c.39-.71.37-1.58-.06-2.27z"
                                ></path>
                            </svg>
                            <img
                                v-for="etype in event.types"
                                :key="etype.id"
                                class="text-gray-100 w-5 h-5 ml-1"
                                :src="etype.ico"
                            />
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p v-if="event.statuses.length">
                        {{event.statuses[0].name}}
                        </p>

                        <p v-else>Неизвестен</p>
                    </td>
                    <td class="px-6 py-4">
                        {{ event.name }}
                    </td>
                    <td class="px-6 py-4">
                        <h1 class="dark:text-gray-200 text-base"
                            >{{ event.author.id }} / {{ event.author.name }}</h1
                        >
                        <p class="dark:text-gray-400 text-ml">{{
                            event.author.email
                        }}</p>
                    </td>
                    <td class="px-6 py-4">
                        {{ event.date_start }}
                    </td>
                    <td class="px-6 py-4">
                        {{ event.date_end }}
                    </td>
                    <td class="px-6 py-4">
                        {{ getCurrentTime(event.updated_at.slice(0, 19)) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ getCurrentTime(event.created_at.slice(0, 19)) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        name: 'EventTable',
        props: {
            events: {
                type: Object,
                default() {
                    return {}
                },
            },
        },
        emits: ['event'],
        methods: {
            emitEvent(event) {
                this.$emit('event', event)
            },
            getCurrentTime(time) {
                return this.$helpers.DateHelp.getCurrentFormate(time)
            },
        },
    }
</script>
<style lang=""></style>
