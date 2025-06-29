<template>
    <div>
        <div v-if="loading">Carregando tarefas...</div>
        <div v-if="error" class="text-red-500">{{ error }}</div>

        <div v-for="task in tasks" :key="task.id">
            <slot :task="task"></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        tasks: {
            type: Array,
            required: true,
            default: () => []
        },
        loading: {
            type: Boolean,
            default: false
        },
        error: {
            type: String,
            default: ''
        }
    }
}
</script>
