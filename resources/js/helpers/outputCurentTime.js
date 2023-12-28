import { useLocalStorage } from "@vueuse/core";


export default {
    // Функция вывода времени с учётом временной зоны пользователя
    outputCurentTime(time) {
        return new Date(time).toLocaleString("ru-RU", {timeZone: useLocalStorage().timeZone});
    }
}