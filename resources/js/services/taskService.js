import axios from 'axios';

const apiClient = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

// Interceptor para tratamento de erros (Diferencial)
apiClient.interceptors.response.use(
    response => response,
    error => {
        // Lógica de tratamento de erro global pode ser adicionada aqui
        console.error('API Error:', error.response?.data || error.message);
        return Promise.reject(error);
    }
);


export default {
    getTasks() {
        return apiClient.get('/tasks');
    },
    createTask(task) {
        return apiClient.post('/tasks', task);
    },
    updateTask(id, task) {
        return apiClient.put(`/tasks/${id}`, task);
    },
    deleteTask(id) {
        return apiClient.delete(`/tasks/${id}`);
    },
    toggleTask(id) {
        return apiClient.patch(`/tasks/${id}/toggle`);
    }
};
