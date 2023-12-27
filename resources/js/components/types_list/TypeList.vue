

<template>
  <div :id="'sight-'+sightId+'-stype-'+allSTypes.id">
    <button @click="isExpanded = !isExpanded"  type="button" class="bg-green-300" v-if="!checkChild()">{{ allSTypes.name }}</button>
    <button @click="isExpanded = !isExpanded"  type="button" class="bg-red-300" v-if="checkChild()">{{ allSTypes.name }}</button>
    <input type="checkbox" class="ml-2"  :checked="checkType(currentStypes)" @change="selectedType(allSTypes)" :disabled="!enableState" v-bind:class="{'opacity-30': !enableState}">
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

  mounted(){
    // console.log(this.currentTypes)
  },

  data(){
    return{
      isExpanded: false,
      allTypes: null
    }
  },

  methods:{
  checkChild(){
    if (this.allSTypes.stypes.length>0){
      return true
    }
    else if (this.allSTypes.stypes.length == 0){
      return false
    }
  },
  checkType(type){
  
    // console.log("Наши типы: ",this.currentStypes)
    // console.log("Тип",this.allSTypes)
    let status = this.currentStypes.some(obj => obj.id == this.allSTypes.id)
    if(status){
      console.log(status)
    }
    return status
  },
  selectedType(type){
    console.log("Вызов")
    this.$emit("checked",type)
    
  } 

    

  }
  

  
  
}
</script>