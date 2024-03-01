<template>
<div class="mb-4 flex-col font-[Montserrat-Regular]">
    <form>
    <label for="status" class="mx-auto mb-2 text-gray-900 dark:text-white"><h1 class="text-center text-xl mb-2">Статус</h1></label>
    <select 
    id="statuses" 
    class=" mx-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg  focus:ring-blue-500 focus:border-blue-500 block w-1/10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"     
    v-bind:value="status"
    :disabled="!editButton"
    >
        <option value="Отказ">Отказ</option>
        <option value="Опубликовано">Опубликовано</option>
        <option value="Черновик">Черновик</option>
        <option value="На модерации">На модерации</option>
        <option value="В архиве">В архиве</option>
    </select>
    <div v-if="editButton" class="mt-2 flex justify-center">
        <button @click.prevent="changeStatus($event.target.form[0].value)" type="submit" class="rounded-lg text-[#fff] bg-green-500 w-36 p-2 z-50 text-center cursor-pointer">
            Применить
        </button>
    </div>
    </form>
</div>
</template>


<script>
export default{
    name:"ChangeStatus",
    props: {
        status: String,
        editButton: {
            type: Boolean,
            default: false
        }
    },
    methods:{
        changeStatus(status){
            this.$emit("statusChanged", status)
        }
    }

}
</script>