import { defineStore } from 'pinia'


export const useBarStore = defineStore('useBar', {
    actions: {
        async showBar() {
            this.leftBar = true;
        },
        async closeBar() {
            this.leftBar = false;
        }
    },
    state: () => ({
        leftBar: true,
    })
})