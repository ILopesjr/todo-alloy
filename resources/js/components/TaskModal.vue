<template>
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">{{ task ? 'Editar Tarefa' : 'Nova Tarefa' }}</h2>

            <TaskForm :task="task" @save="onSave" @cancel="close" />

        </div>
    </div>
</template>

<script>
import TaskForm from './TaskForm.vue';

export default {
    components: {
        TaskForm
    },
    props: {
        task: {
            type: Object,
            default: null
        }
    },
    emits: ['close', 'save'],
    methods: {
        onSave(taskData) {
            this.$emit('save', taskData);
        },
        close() {
            this.$emit('close');
        }
    }
}
</script>
