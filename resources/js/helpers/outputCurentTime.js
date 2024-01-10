import { useLocalStorage } from "@vueuse/core";
import moment from 'moment'
import 'moment-timezone'


export default {
    // Функция вывода времени с учётом временной зоны пользователя
    outputCurentTime(time, timeZone) {
        let data = moment.tz(time, timeZone)
        
        console.log(timeZone,moment.tz.guess())
        return data.clone().tz(moment.tz.guess()).format("YYYY-MM-DD HH:mm:ss")
    }
}