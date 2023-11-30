import axios from 'axios';
import { defineStore } from 'pinia';
import { from } from 'rxjs';



export const useContentStore = defineStore('useContent', {
    actions: {
        getContents(params)  {
            return from(axios.get('admin/users/', {params}))
        },
    },
})