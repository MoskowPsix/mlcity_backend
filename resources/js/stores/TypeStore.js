import axios from 'axios';
import { defineStore } from 'pinia';
import { from } from 'rxjs';

export const useTypeStore = defineStore("useType",{
    actions:{
        getTypes(){
            
            return from(axios.get("sight-types"))
        },
        getTypeByText(params,type){
          
            if (type=="Event"){
                
                return from(axios.get('event-types',{params}))
            }
            else if(type=="Sight"){
                
                return from(axios.get(`sight-types`, {params}))
            }
        }
    }
})