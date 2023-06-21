import { defineStore } from 'pinia'


export const useThemeStore = defineStore('ThemeStore', {
    state: () => ({
       mode: true
    }),
    actions: ({
        DarckOn() {
            this.mode = true;
        },
        DarckOff() {
            this.mode = false;
        }
       
    })
})