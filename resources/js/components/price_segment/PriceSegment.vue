<template lang="">
    <div v-if="price" class="font-[Montserrat-Regular] m-8">
       

        <div class="flex flex-col items-center" v-if="state">

            <div class="flex  items-center justify-center w-[100%]">

                <div  class="flex mt-[-1rem] mr-4 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 my-auto text-rose-500"
                    v-on:click="delPrice(price)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </div>

                <div class=" mb-4">
                    <input class="text-sm text-center text-md bg-transparent  leading-tight text-neutral-800 dark:text-neutral-50 w-3/4 border-sky-400/30 bg-indigo-50 dark:bg-gray-700 rounded-lg p-2 pl-1 border-1 m-0"
                    type="number"
                    pattern = ""
                    placeholder = "Введите цену"
                    @input="setPrice(price)"
                    v-model="price.cost_rub">

                </div>

            </div>
               
        
               

            <div  class="w-[200px] bg-transparent">
                <textarea class=" bg-transparent text-sm  leading-tight text-neutral-800 dark:text-neutral-50 w-full border-sky-400/30 bg-indigo-50 dark:bg-gray-700 rounded-lg p-2 pl-1 border-1 m-0 bg-transparent"
                type="text"
                @input="setPrice(price)"
                v-model="price.descriptions"
                placeholder = "Введите описание билета"
                ></textarea>
            </div>

            

        </div>

        <div v-if="!state">
            <div class="flex justify-center text-center items-center   mb-4 border-b-2 border-[#F3F3F3] ">

                   
                    <div class=""> 
                        <input class="border-none text-center w-[10rem] bg-transparent"
                        type="number"
                        @input="setPrice({cost_rub: $event.target.value})"
                        :value="price.cost_rub" readonly>
                    </div>
                   ₽
                
            
            </div>

            <div class="bg-transparent">
                <textarea class=" border-none bg-transparent"
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


            this.$emit('onUpdPrice', text)
        },
        delPrice(price){
            let data = {
                id: price.id,
                new_id: price.new_id,
                on_delete: true
            }
            this.$emit("onDelPrice",price)
        }

    }
}
</script>
<style lang="">

</style>
