import { useLocalStorage } from "@vueuse/core";
import moment from 'moment'
import 'moment-timezone'


export default {
    // Функция вывода времени с учётом временной зоны пользователя
    outputCurentTime(time, timeZone) {
        let data = moment(time, "YYYY-MM-DD HH:mm:ss").tz(timeZone)
        console.log(time)
        return data.format("YYYY-MM-DD HH:mm:ss")
    }
}