<template>
    <div class="mb-4 font-[Montserrat-Regular]">
        <form class="flex">
            <select
                id="statuses"
                class="mx-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-2 mr-2"
                :value="status"
                :disabled="!editButton"
            >
                <option value="Отказ">Отказ</option>
                <option value="Опубликовано">Опубликовано</option>
                <option value="Черновик">Черновик</option>
                <option value="Изменено">Изменено</option>
                <option value="В архиве">В архиве</option>
                <option value="Новое">Новое</option>
            </select>
            <div
                v-if="editButton"
                class="mt-2 flex justify-center"
            >
                <button
                    type="submit"
                    class="rounded-lg text-[#fff] bg-green-500 w-36 p-2 z-50 text-center cursor-pointer"
                    @click.prevent="changeStatus($event.target.form[0].value)"
                >
                    Применить
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'ChangeStatus',
        props: {
            // eslint-disable-next-line vue/require-default-prop
            status: String,
            editButton: {
                type: Boolean,
                default: false,
            },
        },
        emits: ['statusChanged'],
        methods: {
            changeStatus(status) {
                this.$emit('statusChanged', status)
            },
        },
    }
</script>
