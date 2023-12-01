<template lang="">
        <div class="border border-gray-700 rounded-lg grid grid-cols-2 p-2 dark:bg-gray-800 bg-gray-50 mt-1">
            <div class="flex justify-evenly">
                <button v-if="backPage" @click.prevent="onBackPage()" class="mr-2 border bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-gray-700 border-gray-400 dark:hover:text-color-300/70 dark:text-gray-400/70 dark:border-gray-600 dark:bg-gray-900/40 dark:hover:bg-gray-700  pt-2 pb-2 pl-4 pr-4 shadow-lg rounded-lg">Назад</button>
            </div>
            <div class="flex justify-evenly">
                <button v-if="nextPage" @click.prevent="onNextPage()" class=" border bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-gray-700 border-gray-400 dark:hover:text-color-300/70 dark:text-gray-400/70 dark:border-gray-600 dark:bg-gray-900/40 dark:hover:bg-gray-700  pt-2 pb-2 pl-4 pr-4 shadow-lg rounded-lg">Далее</button>
            </div>
        </div>
</template>
<script>
export default {
    name: 'PaginateBar',
    props: ['nextPage', 'backPage'],
    methods: {
        onBackPage() {
            this.$emit('onBackPage', this.backPage)
        },
        onNextPage() {
            this.$emit('onNextPage', this.nextPage)
        }
    }
}
</script>
<style lang="">
    
</style>