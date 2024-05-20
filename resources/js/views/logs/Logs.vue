<template lang="">
    <div>
        <iframe
            id="iframe"
            :src="`/telescope?mode=${isDark}`"
            style="width: 100%; height: 100vh; border: none"
        ></iframe>
    </div>
    <!--  :src="`api/telescopes?token=${this.token}&mode=${this.themeState}`"
            :headers="header" -->
</template>
<script>
    import { mapState } from 'pinia'
    import { useLocalStorageStore } from '../../stores/LocalStorageStore'
    import { useDark } from '@vueuse/core'

    export default {
        name: 'Logs',
        setup() {
            const isDark = useDark()
            return {
                isDark,
            }
        },
    }
</script>
