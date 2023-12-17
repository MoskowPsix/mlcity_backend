import axios from 'axios';
import { defineStore } from 'pinia';
import { from } from 'rxjs';

export const useTypeStore = defineStore("useType",{
    actions:{
        getTypes(){
            return from(axios.get("sight-types"))
        }
    }
})