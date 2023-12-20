import axios from 'axios';
import { defineStore } from 'pinia';
import { from } from 'rxjs';



export const useStatusStore = defineStore('useStatus', {
    actions: {
        getStatuses() {
            return from(axios.get('statuses'))
        }
    },
})