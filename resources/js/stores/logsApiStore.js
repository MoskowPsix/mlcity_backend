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
        search_logs: {
            method: '',
            url: '',
            user_id: '',
            ip: '',
            dynamic: false,
            time: '5000',
            status_code: '',
            date: '',
        }
    }),
    actions: {
        async getLogs() {
            this.loader = true;
            const user = '';
            if (this.search_logs.user_id) {user = '&user_id=' + this.search_logs.user_id}
            await axios.get(
                'logs?method=' + this.search_logs.method + 
                '&url=' + this.search_logs.url + 
                // this.search_logs.date + // Пока нет аргумента в апи
                user +
                '&ip=' + this.search_logs.ip + 
                '&status_code=' + this.search_logs.status_code
                ).then(response => {
                this.logs = response.data.logs.data;
                this.perv_page = response.data.logs.prev_page_url;
                this.next_page = response.data.logs.next_page_url;
                this.current_url = response.data.current_url;
            }).catch(error => this.toast.error('При загрузке журнала произошла ошибка: ' + error.message))
            this.loader = false;
        },
        async getLogUrl(url) {

            await axios.get(url).then(response => {
                this.logs = response.data.logs.data;
                this.perv_page = response.data.logs.prev_page_url;
                this.next_page = response.data.logs.next_page_url;
                this.current_url = response.data.current_url;
            }).catch(error => {
                this.toast.error('При загрузке адреса журнала произошла ошибка: ' + error.message)
            })
        },
        async reloadPage() {
            await setInterval(() => {
                if (this.search_logs.dynamic) {
                    this.getLogUrl(this.current_url);
                }
             }, this.search_logs.time)
        },
        async clearSearch() {
            this.search_logs.method = '';
            this.search_logs.url = '';
            this.search_logs.user_id = '';
            this.search_logs.ip = '';
            this.search_logs.status_code = '';
            this.search_logs.date = '';
            await this.getLogs();
        },
    }
})