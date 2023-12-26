<template lang="">
    <div v-if="price">
        <div v-if="state">
            <div class=" w-[120px]">
                <input class=" text-md  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700 rounded-lg p-2 pl-1 border-1 m-0"
                type="number"
                @input="setPrice({cost_rub: $event.target.value})"
                :value="price.cost_rub">руб
                
            </div>
            <div  class="w-[200px]">
                <textarea class=" text-sm  leading-tight text-neutral-800 dark:text-neutral-50 w-full border-sky-400/30 bg-indigo-50 dark:bg-gray-700 rounded-lg p-2 pl-1 border-1 m-0"
                type="text"
                @input="setPrice({descriptions: $event.target.value})"
                :value="price.descriptions"
                ></textarea>
            </div>
            <div  class="flex space-x-2 m-2 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 my-auto text-rose-500"
                v-on:click="delPrice(price)">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>
        </div>
        <div v-if="!state">
            <div class=" w-[120px]">
                <input class=" text-md  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700 rounded-lg p-2 pl-1 border-1 m-0"
                type="number"
                @input="setPrice({cost_rub: $event.target.value})"
                :value="price.cost_rub" readonly>руб
                
            </div>
            <div class="w-[200px]">
                <textarea class=" text-sm  leading-tight text-neutral-800 dark:text-neutral-50 w-full border-sky-400/30 bg-indigo-50 dark:bg-gray-700 rounded-lg p-2 pl-1 border-1 m-0"
                type="text"
                @input="setPrice({descriptions: $event.target.value})"
                :value="price.descriptions"
                readonly
                ></textarea>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'PriceSegment',
    props: {
        price: Object,
        index: {
            type: Number,
            default: null
        },
        state: {
            type: Boolean,
            default: false
        },
    },
    emits: ['onUpdPrice', "onDelPrice"],
    methods: {
        setPrice(text) {
            text.id = this.price.id
            this.$emit('onUpdPrice', text)
        },
        delPrice(price){
            let data = {
                id: price.id,
                on_delete: true
            }
            this.$emit("onDelPrice",price)
        }
        
    }
}
</script>
<style lang="">
    
</style>