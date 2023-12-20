<template lang="">
    <div :data-active="active" @dragenter.prevent="setActive" @dragover.prevent="setActive" @dragleave.prevent="setInactive" @drop.prevent="onDrop">
        <slot></slot>
    </div>
</template>
<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
const emit = defineEmits(['files-dropped', 'state'])


let active = ref(false) // Переменная определяет состояние дроп зоны
let inActiveTimeout = null // таймаут для функций определения положения файла

// Присваеваем тру если файл в дроп зоне
function setActive() {
    active.value = true
    clearTimeout(inActiveTimeout) 
    emit('state', true)
}
// Присваеваем фолс если файл покидает дроп зону
function setInactive() {
    inActiveTimeout = setTimeout(() => {
        active.value = false
        emit('state', false)
    }, 50)
}
// Передаём файл из дроп зоны во вне
function onDrop(e) {
    setInactive() 
    emit('files-dropped', [...e.dataTransfer.files])
}

</script>
<style lang="">
    
</style>