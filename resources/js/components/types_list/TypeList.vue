

<template>
  <div :id="type + '-'+ sightId +'-type-'+ allSTypes.id" class="border rounded-lg dark:border-gray-600/70 dark:bg-gray-800/50 p-4 m-2 max-w-full" >
    
    <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
      <input type="checkbox" class="relative float-left ml-[1.5rem] mr-[6px] mt-[6px] rounded h-5 w-5"  :checked="checkType(currentStypes)" @change="selectedType(allSTypes)" :disabled="!enableState" v-bind:class="{'opacity-30': !enableState}">

      <div v-if="!checkChild()">
        <button @click="isExpanded = !isExpanded"  type="button" class="text-lg" >{{ allSTypes.name }}</button>
      </div>
    
      <div v-else class="flex space-x-2 items-center" :id="type + '-'+ sightId +'-type-'+ allSTypes.id">
        <button @click="isExpanded = !isExpanded"  type="button" class="text-lg">{{ allSTypes.name }}</button>
        <div class="flex">
          <p>{{ childsLength(allSTypes) }}</p>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0-3.75-3.75M17.25 21 21 17.25" />
          </svg>
        </div>
        
        
      </div>

    </div>
    
    <div v-if="allSTypes.stypes" :class="{'ml-6': checkChild()}">
      <Collapse :when="isExpanded" v-for="stypes in allSTypes.stypes" v-bind:key="stypes.id">
       <TypeList :enableState="enableState" :allSTypes="stypes" :currentStypes="currentStypes" class="mb-2" @checked="selectedType"></TypeList>
    </Collapse>
    </div>
  </div>

  
  
</template>

<script>
import { mapActions} from 'pinia'
import { Collapse } from 'vue-collapsed'

import { catchError, map, retry, delay, takeUntil} from 'rxjs/operators'


export default{
  name: "TypeList",
  props: {
    allSTypes: Object,
    currentStypes: Array,
    enableState: Boolean,
    sightId: Number,
    type: String,
  },
  components:{
    Collapse
  },
  data(){
    return{
      isExpanded: false,
      allTypes: null,
      test: this.allSTypes
    }
  },
  methods:{
  checkChild(){
    if (this.type == 'sight') {
      if (this.allSTypes.stypes.length>0){
        return true
      }
      else if (this.allSTypes.stypes.length == 0){
        return false
      }
    } else if(this.type == 'event') {
      if (this.allSTypes.etypes.length>0){
        return true
      }
      else if (this.allSTypes.etypes.length == 0){
        return false
      }
    }
  },
  checkType(type){
    let status = this.currentStypes.some(obj => obj.id == this.allSTypes.id)
    return status
  },
  selectedType(type){
    this.$emit("checked", type)
    
  },
  childsLength(type){
    if (this.type == 'sight') {
      let count = 0
      type.stypes.forEach(element => {
        if(element.stypes.length>0){
          count += this.childsLength(element)
        }
        count ++
      });
      return count
    } else if(this.type == 'event') {
      let count = 0
      type.etypes.forEach(element => {
        if(element.etypes.length>0){
          count += this.childsLength(element)
        }
        count ++
      });
      return count
    }
  }
  
  }
  

  
  
}
</script>