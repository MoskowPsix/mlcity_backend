<template>
  <!-- <div class="ml-64" @click="console.log(auth)">{{ auth }}</div> -->
  <LoaderFullPage v-if="loaderFullPageState"/>
  <div v-if="stateLeftBar">
    <LeftBar/>
    <div class="justify-items-center sm:ml-64 h-screen overflow-y-scroll" id="journal-scroll" >
      <RouterView/>
    </div>
  </div>
  <RouterView v-if="!stateLeftBar"/>
</template>

<script>
import LeftBar from './components/left_bar/LeftBar.vue'
import LoaderFullPage from './components/loaders/LoaderFullPage.vue';
import { mapState } from 'pinia'
import { useLocalStorageStore } from './stores/LocalStorageStore';
import { useLoaderStore} from './stores/LoaderStore'


export default {
  name: 'App',
  components: {
    LeftBar,
    LoaderFullPage,
  },
  computed: {
    ...mapState(useLocalStorageStore, {auth: 'auth'}),
    ...mapState(useLoaderStore, {
      loaderFullPageState: 'loaderFullPageState',
      loaderFullViewRouterState: 'loaderFullViewRouterState'
    }),
  },
  data () {
    return {
      stateLeftBar: false,
    }
  },
  mounted() {
    this.getStateLeftBar()
  },
  watch: {
    $route(to,from) {
      this.getStateLeftBar()
    }
  },
  methods: {
    getStateLeftBar() {
      this.stateLeftBar = this.auth
    }
  },
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
