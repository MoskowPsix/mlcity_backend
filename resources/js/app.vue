<template>
  <LeftBar v-if="bar_store.leftBar === true"/>
  <RouterView v-if="bar_store.leftBar === false"/>
  <div class="justify-items-center sm:ml-64" v-if="bar_store.leftBar === true">
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