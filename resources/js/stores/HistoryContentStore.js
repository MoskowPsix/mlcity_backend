import axios from 'axios';
import { defineStore } from 'pinia';
import { from } from 'rxjs';



export const useHistoryContentStore = defineStore('useContent', {
    actions: {
        getContents(params)  {
            return from(axios.get('history-content', {params}))
        },
        saveHistory(data) {
            return from(axios.post("history-content",data, {headers:{
                "Content-Type": "multipart/form-data"
            }}))
        }
    },
})