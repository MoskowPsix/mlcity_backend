<template lang="">
    <div class="border-none flex justify-between items-center border rounded-lg w-full h-full dark:bg-transparent dark:border-none p-2 xl:p-8">
        <img class="mt-2 border w-24 h-24 rounded-3xl dark:border-gray-700/80 " :src="author.avatar" :alt="author.name"/>
        <label class="flex flex-col items-center p-2">
            <h1 class="mb-2 ">Имя: {{author.name}}</h1>
                <div class="flex  flex-col">

          
                    <p class="flex flex-row border rounded-lg dark:border-gray-700/80 p-2 mb-2 text-ms dark:text-gray-400">
                        Почта: {{author.email}}
                        <svg v-if="author.email_verified_at" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-2 w-5 h-5 text-green-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <svg v-if="!author.email_verified_at"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-2 w-5 h-5 text-red-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>

                    </p>
                    <p class="flex flex-row border rounded-lg dark:border-gray-700/80 p-2 mb-1 text-ms dark:text-gray-400">
                        Номер: +7 {{author.number}}
                        <svg v-if="author.number_verified_at" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-2 w-5 h-5 text-green-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <svg v-if="!author.number_verified_at" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-2 w-5 h-5 text-red-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </p>
             </div>
        </label>
    </div>
</template>
<script>
export default {
    name: 'AuthorMiniCard',
    props: [
        'author'
    ],
}
</script>
<style lang="">
    
</style>