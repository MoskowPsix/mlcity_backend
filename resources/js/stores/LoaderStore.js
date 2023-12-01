import { defineStore } from 'pinia';


export const useLoaderStore = defineStore('LoaderStore', {
    state: () =>({
        loaderFullPageState: true,
        loaderFullViewRouterState: true
    }),
    actions: {
        openLoaderFullPage() {
            this.loaderFullPageState = true
        },
        closeLoaderFullPage() {
            this.loaderFullPageState = false
        },
        openLoaderFullRouterView() {
            this.loaderFullViewRouterState = true
        },
        closeLoaderFullRouterView() {
            this.loaderFullViewRouterState = false
        }
    },
})