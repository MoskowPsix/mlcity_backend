<template>
  <LeftBar v-if="bar_store.leftBar === true"/>
  <RouterView v-if="bar_store.leftBar === false"/>
  <div class="justify-items-center sm:ml-64 h-screen overflow-y-scroll" id="journal-scroll" v-if="bar_store.leftBar === true">
    <RouterView />
  </div>
</template>

<script>
import LeftBar from './components/LeftBar.vue'
import { useBarStore } from './stores/barStore'


export default {
  name: 'App',
  components: {LeftBar},
  setup() {
    const bar_store = useBarStore();
    return {bar_store }
  }
}

</script>

<style>
  #journal-scroll::-webkit-scrollbar {
            width: 4px;
            height: 4px;
            cursor: pointer;
            /*background-color: rgba(229, 231, 235, var(--bg-opacity));*/

        }
        #journal-scroll::-webkit-scrollbar-track {
            background-color: rgba(229, 231, 235, var(--bg-opacity));
            cursor: pointer;
            
            /*background: red;*/
        }
        #journal-scroll::-webkit-scrollbar-thumb {
            cursor: pointer;
            background-color: #6c7786;
            border-radius: 25px;
            /*outline: 1px solid slategrey;*/
        }
</style> 
