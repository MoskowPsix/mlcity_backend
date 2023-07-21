import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';


export const useLogsApiStore = defineStore('LogsApiStore', {
    state: () => ({
        toast: useToastStore(),
        logs: [],
        perv_page: '',
        next_page_url: '',
        current_url: '',
        loader: false,
        time: '300000', 
    }),
    actions: {
        async getLogs() {
            await axios.get('logs').then(response => {
                this.logs = response.data.logs.data;
                this.perv_page = response.data.logs.prev_page_url;
                this.next_page = response.data.logs.next_page_url;
                this.current_url = response.data.current_url;
            }).catch(error => this.toast.error('При загрузке журнала произошла ошибка: ' + error.message))
            
        },
        async getLogUrl(url) {

            await axios.get(url).then(response => {
                this.logs = response.data.logs.data;
                this.perv_page = response.data.logs.prev_page_url;
                this.next_page = response.data.logs.next_page_url;
                this.current_url = response.data.current_url;
                console.log(url);
            }).catch(error => {
                this.toast.error('При загрузке адреса журнала произошла ошибка: ' + error.message)
            })
        },
        async reloadPage() {
            await setInterval(() => {
                this.getLogUrl(this.current_url);
             }, this.time)
        }
    }
})