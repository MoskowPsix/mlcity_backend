<template>
<div class="mb-4">
                <label for="status" class="block mb-2   text-gray-900 dark:text-white">Статус</label>
                <select 
                id="statuses" 
                class=" bg-gray-50 border  border-gray-300  text-gray-900 text-sm rounded-lg  focus:ring-blue-500 focus:border-blue-500 block w-1/10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                "
                
                v-bind:value="status"
                @change="changeStatus($event.target.value)">
                    <option value="Отказ">Отказ</option>
                    <option value="Опубликовано">Опубликовано</option>
                    <option value="Черновик">Черновик</option>
                    <option value="На модерации">На модерации</option>
                    <option value="В архиве">В архиве</option>
                </select>
            </div>
</template>


<script>
export default{
    name:"ChangeStatus",
    props: {
        status: String
    },
    methods:{
        changeStatus(status){
            this.$emit("statusChanged", status)
        }
    }

}
</script>