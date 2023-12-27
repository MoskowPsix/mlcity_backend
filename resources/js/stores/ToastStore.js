import { defineStore } from 'pinia';
import { useToast } from "vue-toastification";


export const useToastStore = defineStore('useToast', {
    actions: {
       showToast(message, type) {
        let options = {
            position: "top-right",
            timeout: 4984,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 1,
            showCloseButtonOnHover: true,
            hideProgressBar: true,
            closeButton: false,
            icon: true,
            rtl: false
          }
        switch(type){
            // case(''):
            //     this.$toast(message, options)
            // break;
            case 'success':
                useToast().success(message, options)
            break;
            case 'info':
                useToast().info(message, options)
            break;
            case 'warning':
                useToast().warning(message, options)
            break;
            case 'error':
                useToast().error(message, options)
            break;
        }
        let toast = useToast()
       }
    },
})