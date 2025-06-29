<template>
    <div
        class="flex items-center justify-between p-4 mb-3 bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300"
        :class="{ 'bg-gray-50 text-gray-400': task.finalizado }"
    >
        <div class="flex items-center">
            <button
                @click="$emit('toggle', task.id)"
                class="flex-shrink-0 w-6 h-6 mr-4 border-2 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                :class="{
          'border-gray-300': !task.finalizado,
          'bg-indigo-600 border-indigo-600': task.finalizado,
        }"
                aria-label="Marcar como finalizado"
            >
                <svg v-if="task.finalizado" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-white">
                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" />
                </svg>
            </button>

            <div class="flex-grow">
                <p
                    class="font-semibold text-lg"
                    :class="{ 'line-through': task.finalizado, 'text-gray-800': !task.finalizado }"
                >
                    {{ task.nome }}
                </p>
                <p v-if="task.descricao" class="text-sm" :class="{'text-gray-400': task.finalizado, 'text-gray-600': !task.finalizado}">
                    {{ task.descricao }}
                </p>
                <p v-if="task.data_limite" class="text-xs mt-1" :class="{'text-red-400': !task.finalizado, 'text-gray-400': task.finalizado}">
                    <span class="font-semibold">Prazo:</span> {{ formattedDate }}
                </p>
            </div>
        </div>

        <div class="flex items-center space-x-2 flex-shrink-0">
            <button
                @click="$emit('edit', task)"
                class="p-2 text-gray-500 rounded-full hover:bg-gray-200 hover:text-gray-700 focus:outline-none"
                aria-label="Editar tarefa"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                </svg>
            </button>
            <button
                @click="$emit('delete', task.id)"
                class="p-2 text-red-500 rounded-full hover:bg-red-100 hover:text-red-700 focus:outline-none"
                aria-label="Excluir tarefa"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.134-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.067-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

// Define a propriedade 'task' que o componente espera receber de seu pai.
// É marcada como um objeto e obrigatória.
const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
});

// Declara os eventos que este componente pode emitir.
// Isso melhora a clareza e o desempenho.
defineEmits(['toggle', 'edit', 'delete']);

// Uma propriedade computada para formatar a data de forma amigável.
// Isso mantém a lógica de formatação fora do template e garante reatividade.
const formattedDate = computed(() => {
    if (!props.task.data_limite) {
        return '';
    }
    // Converte a string de data do backend para um objeto Date
    const date = new Date(props.task.data_limite);
    // Formata para o padrão brasileiro (dd/mm/aaaa, hh:mm)
    return date.toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
});
</script>
