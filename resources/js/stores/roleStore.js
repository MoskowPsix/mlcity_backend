import { defineStore } from 'pinia'
import axios from 'axios';
import { useToastStore } from './toastStore';


export const useRoleStore = defineStore('RoleStore', {
    state: () => ({
        config: {
            headers: { Authorization: `Bearer ${localStorage.token}` }
        },
        bodyParameters: {
            key: "value"
        },
        toast: useToastStore(),
        role: [],
        role_id: '',
        ModalCreate: false,
        ModalUpdate: false,
        ModalDelete: false,
        lader: false
    }),
    actions: {
        async getRole() {
            this.loader = true;
            await axios.get('http://localhost:8000/api/allRole', this.config)
            .then(response => this.role = response)
            .catch(error => this.toast.error('Ошибка, роль не получена!'));
            this.loader = false;
        
        },
        async updateRole(role_id, user_id) {
            await axios.put('http://localhost:8000/api/updateRoleUser/' + user_id + '/' + role_id, this.bodyParameters, this.config)
            .then(response => console.log(response))
            .catch(error => this.toast.error('Ошибка, роль не обновлена!'));
        },
        async deleteRole() {
            this.loader = true;
            await axios.delete('http://localhost:8000/api/deleteRole/' + this.role_id, this.bodyParameters, this.config)
            .then(response => this.toast.success('Роль с ID: ' + this.role_id + ', удалена!'))
            .catch(error => this.toast.error('Ошибка, роль не обновлена!'));
            this.getRole();
            this.closeDeleteRole();
            this.loader = false;
        },
        async updateRoleName(name) {
            this.loader = true;
            await axios.put('http://localhost:8000/api/updateRole/' + this.role_id + '?name=' + name, this.bodyParameters, this.config)
            .then(response => this.toast.success('Роль переименована на ' + response.data.role.name))
            .catch(error => this.toast.error('Ошибка, имя не обновлена!'));
            this.getRole();
            this.closeUpdateModal();
            this.loader = false;
        },
        async createRole(name) {
            this.loader = true;
            await axios.post('http://localhost:8000/api/addRole/', {
                name: name,
            }, this.config)
            .then(response => this.toast.success('Роль ' + response.data.role.name + ' создана!'))
            .catch(error => this.toast.error('Ошибка, роль не создана!'));
            this.getRole();
            this.closeCreateModal();
            this.loader = false;
        },
        async showCreateModal() {
            this.ModalCreate = true;
        },
        async showUpdateModal(id) {
            this.role_id = id;
            this.ModalUpdate =true;
        },
        async showDeleteRole(id) {
            this.role_id = id;
            this.ModalDelete =true;
        }, 
        async closeCreateModal() {
            this.ModalCreate = false;
        }, 
        async closeUpdateModal() {
            this.role_id = '';
            this.ModalUpdate = false;
        },
        async closeDeleteRole() {
            this.ModalDelete =false;
            this.role_id = '';
        }, 
    }
    
})