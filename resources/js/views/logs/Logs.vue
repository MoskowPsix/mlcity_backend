<template lang="">
    <div>
        <iframe
            :src="`/telescope?token=${this.token}&mode=${this.themeState}`"
            style="width: 100%; height: 100vh; border: none;"
    ></iframe>
    </div>
</template>
<script>
import { mapState } from 'pinia';
import { useLocalStorageStore } from '../../stores/LocalStorageStore';
import { useDark } from '@vueuse/core'


export default {
    name: 'Logs',
    computed: {
        ...mapState(useLocalStorageStore, ['token']),
    },
    setup() {
        const themeState = useDark()
        return {
            themeState
        }
    },
}
</script>