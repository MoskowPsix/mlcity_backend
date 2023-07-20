import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';


export const useLogsApiStore = defineStore('LogsApiStore', {
    state: () => ({
        toast: useToastStore(),
        logs: [],
        links: [],
        first_page_url: '',
        loader: false,
        time: '300000', 
    }),
    actions: {
        async getLogs() {
            await axios.get('logs?limit=10').then(response => {
                this.logs = response.data.logs.data;
                this.links = response.data.logs.links;
                this.first_page_url = response.data.logs.first_page_url;
                // console.log(this.logs);
                // console.log(this.links);
                console.log(this.first_page_url);
            }).catch(error => this.toast.error('При загрузке журнала произошла ошибка: ' + error.message))
            
        },
        async getLogUrl(url) {
            await axios.get(url).then(response => {
                this.toast.info('Отработал!!!')
                this.logs = response.data.logs.data;
                this.links = response.data.logs.links;
                this.first_page_url = response.data.logs.first_page_url;
            }).catch(error => {
                this.toast.error('При загрузке адреса журнала произошла ошибка: ' + error.message)
                console.log(url)
            })
        },
        async reloadPage() {
            await setInterval(() => {
                this.getLogUrl(this.first_page_url);
             }, this.time)
        }
    }
})