

<template>
  <div>
    <button @click="isExpanded = !isExpanded"  type="button" class="bg-green-300" v-if="!checkChild()">{{ currentTypes.name }}</button>
    <button @click="isExpanded = !isExpanded"  type="button" class="bg-red-300" v-if="checkChild()">{{ currentTypes.name }}</button>
    <input type="checkbox" class="ml-2">
    <div v-if="currentTypes.stypes" :class="{'ml-6': checkChild()}">
      <Collapse :when="isExpanded" v-for="stypes in currentTypes.stypes" v-bind:key="stypes.id" >
       <TypeList :currentTypes="stypes" class="mb-2"></TypeList>
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
    currentTypes: Object
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
    if (this.currentTypes.stypes.length>0){
      return true
    }
    else if (this.currentTypes.stypes.length == 0){
      return false
    }
  }    

    

  }
  

  
  
}
</script>