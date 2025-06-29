<template>
    <form @submit.prevent="submitForm" class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="mb-4">
            <label for="nome" class="block text-sm font-medium text-gray-700">Nome da Tarefa</label>
            <input
                v-model="form.nome"
                type="text"
                id="nome"
                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-500': errors.nome }"
                required
            />
            <p v-if="errors.nome" class="text-sm text-red-600 mt-1">{{ errors.nome }}</p>
        </div>

        <div class="mb-4">
            <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
            <textarea
                v-model="form.descricao"
                id="descricao"
                rows="4"
                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            ></textarea>
        </div>

        <div class="mb-6">
            <label for="data_limite" class="block text-sm font-medium text-gray-700">Data Limite</label>
            <input
                v-model="form.data_limite"
                type="datetime-local"
                id="data_limite"
                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
        </div>

        <div class="flex items-center justify-end space-x-4">
            <button
                type="button"
                v-if="task"
                @click="$emit('cancel')"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
            >
                Cancelar
            </button>
            <button
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                {{ task ? 'Atualizar' : 'Adicionar' }} Tarefa
            </button>
        </div>
    </form>
</template>

<script>
export default {
    props: {
        task: {
            type: Object,
            default: null
        }
    },
    emits: ['save', 'cancel'],
    data() {
        return {
            form: {
                nome: '',
                descricao: '',
                data_limite: ''
            },
            errors: {}
        }
    },
    watch: {
        task: {
            handler(newTask) {
                if (newTask) {
                    // Preenchemos o formulário com os dados da tarefa existente para edição
                    this.form = {
                        id: newTask.id,
                        nome: newTask.nome || '',
                        descricao: newTask.descricao || '',
                        data_limite: newTask.data_limite ? newTask.data_limite.slice(0, 16) : ''
                    };
                } else {
                    // Se não há tarefa, resetamos o formulário
                    this.form = {
                        nome: '',
                        descricao: '',
                        data_limite: ''
                    };
                }
                this.errors = {};
            },
            immediate: true,
            deep: true
        }
    },
    methods: {
        validateForm() {
            this.errors = {};
            if (!this.form.nome || this.form.nome.trim() === '') {
                this.errors.nome = 'O nome da tarefa é obrigatório.';
                return false;
            }
            return true;
        },
        submitForm() {
            if (this.validateForm()) {
                const taskData = { ...this.form };

                // Garantir que a data_limite seja enviada como null se estiver vazia
                if (!taskData.data_limite) {
                    taskData.data_limite = null;
                }

                this.$emit('save', taskData);

                // Limpar o formulário apenas se não estamos editando
                if (!this.task) {
                    this.form = {
                        nome: '',
                        descricao: '',
                        data_limite: ''
                    };
                }
            }
        }
    }
}
</script>
