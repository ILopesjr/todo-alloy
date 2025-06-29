import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import TasksContainer from './components/TasksContainer.vue';

const app = createApp(App);
app.use(createPinia());
app.component('tasks-container', TasksContainer);
app.mount('#app');
