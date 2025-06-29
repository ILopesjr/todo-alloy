import { defineStore } from 'pinia';
import taskService from '@/services/taskService';

export const useTaskStore = defineStore('tasks', {
    state: () => ({
        tasks: [],
        loading: false,
        error: null,
    }),

    actions: {
        async fetchTasks() {
            this.loading = true;
            this.error = null;
            try {
                const response = await taskService.getTasks();
                this.tasks = response.data;
            } catch (error) {
                this.error = 'Falha ao carregar as tarefas.';
                console.error(error);
            } finally {
                this.loading = false;
            }
        },

        async createTask(task) {
            this.loading = true;
            try {
                const response = await taskService.createTask(task);
                this.tasks.unshift(response.data); // Adiciona no início da lista para UX
            } catch (error) {
                this.error = 'Falha ao criar a tarefa.';
                console.error(error);
                throw error; // Propaga o erro para o componente
            } finally {
                this.loading = false;
            }
        },

        async updateTask(id, taskData) {
            this.loading = true;
            try {
                const response = await taskService.updateTask(id, taskData);
                const index = this.tasks.findIndex(t => t.id === id);
                if (index !== -1) {
                    this.tasks[index] = response.data;
                }
            } catch (error) {
                this.error = 'Falha ao atualizar a tarefa.';
                console.error(error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteTask(id) {
            try {
                await taskService.deleteTask(id);
                this.tasks = this.tasks.filter(t => t.id !== id);
            } catch (error) {
                this.error = 'Falha ao excluir a tarefa.';
                console.error(error);
            }
        },

        async toggleTask(id) {
            try {
                const response = await taskService.toggleTask(id);
                const index = this.tasks.findIndex(t => t.id === id);
                if (index !== -1) {
                    this.tasks[index].finalizado = response.data.finalizado;
                }
            } catch (error) {
                this.error = 'Falha ao alterar o status da tarefa.';
                console.error(error);
            }
        },
    }
});
