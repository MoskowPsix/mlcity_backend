

<template>
  <div :id="'sight-'+sightId+'-stype-'+allSTypes.id" class="border p-4 ml-2 mt-2 max-w-[600px]" :name="'stype-'+allSTypes.stype_id">
    <a ></a>
    <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
      <input type="checkbox" class="relative float-left ml-[1.5rem] mr-[6px] mt-[6px] rounded h-5 w-5"  :checked="checkType(currentStypes)" @change="selectedType(allSTypes)" :disabled="!enableState" v-bind:class="{'opacity-30': !enableState}">

      <div v-if="!checkChild()" :id="'stype-'+allSTypes.stype_id">
        <button @click="isExpanded = !isExpanded"  type="button" class="text-lg" >{{ allSTypes.name }}</button>
      </div>
    
      <div v-else class="flex space-x-2 items-center" :id="'stype-'+allSTypes.stype_id">
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
    sightId: Number
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
    // console.log(this.allSTypes.stypes.length)
    if (this.allSTypes.stypes.length>0){
      
      // console.log(this.allSTypes.stypes.length)
      return true
    }
    else if (this.allSTypes.stypes.length == 0){
      return false
    }
  },
  checkType(type){
    let status = this.currentStypes.some(obj => obj.id == this.allSTypes.id)
    if(status){
      console.log(status)
    }
    return status
  },
  selectedType(type){
    console.log("Вызов")
    this.$emit("checked",type)
    
  },
  childsLength(type){
    let count = 0
    
    type.stypes.forEach(element => {
      if(element.stypes.length>0){
        count += this.childsLength(element)
      }
      count ++
    });

    
    return count
  }
  
  }
  

  
  
}
</script>