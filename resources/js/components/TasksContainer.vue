<template>
    <div class="w-full max-w-4xl px-4 py-8">
        <h1 class="text-2xl font-bold mb-6 text-center">Gerenciador de Tarefas</h1>
        <TaskForm @save="addTask" />
        <div class="mt-8">
            <TaskList :tasks="tasks" :loading="loading" :error="error">
                <template v-slot:default="{ task }">
                    <TaskItem
                        :key="task.id"
                        :task="task"
                        @toggle="toggleTask"
                        @edit="openEditModal"
                        @delete="deleteTask"
                    />
                </template>
            </TaskList>
        </div>
        <TaskModal
            v-if="showModal"
            :task="selectedTask"
            @close="closeModal"
            @save="updateTask"
        />
    </div>
</template>

<script>
import TaskList from './TaskList.vue';
import TaskForm from './TaskForm.vue';
import TaskItem from './TaskItem.vue';
import TaskModal from './TaskModal.vue';

export default {
    components: {
        TaskList,
        TaskForm,
        TaskItem,
        TaskModal
    },
    data() {
        return {
            tasks: [],
            loading: false,
            error: '',
            showModal: false,
            selectedTask: null
        }
    },
    mounted() {
        this.fetchTasks();
    },
    methods: {
        fetchTasks() {
            this.loading = true;
            this.error = '';

            axios.get('/api/tasks')
                .then(response => {
                    this.tasks = response.data;
                })
                .catch(error => {
                    console.error('Erro ao buscar tarefas:', error);
                    this.error = 'Falha ao carregar as tarefas. Por favor, tente novamente.';
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        addTask(taskData) {
            axios.post('/api/tasks', taskData)
                .then(() => {
                    this.fetchTasks();
                })
                .catch(error => {
                    console.error('Erro ao adicionar tarefa:', error);
                });
        },

        toggleTask(taskId) {
            axios.patch(`/api/tasks/${taskId}/toggle`)
                .then(() => this.fetchTasks())
                .catch(error => console.error('Erro ao alternar status da tarefa:', error));
        },

        openEditModal(task) {
            this.selectedTask = task;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.selectedTask = null;
        },

        updateTask(taskData) {
            axios.put(`/api/tasks/${taskData.id}`, taskData)
                .then(() => {
                    this.fetchTasks();
                    this.closeModal();
                })
                .catch(error => {
                    console.error('Erro ao atualizar tarefa:', error);
                });
        },

        deleteTask(taskId) {
            if(confirm('Tem certeza que deseja excluir esta tarefa?')) {
                axios.delete(`/api/tasks/${taskId}`)
                    .then(() => this.fetchTasks())
                    .catch(error => console.error('Erro ao excluir tarefa:', error));
            }
        }
    }
}
</script>
