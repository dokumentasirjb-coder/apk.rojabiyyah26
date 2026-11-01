import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';
import './style.css';
import { useNotificationStore } from './stores/notification';

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);

// Initialize notification store and global modern dialog handlers
const notificationStore = useNotificationStore(pinia);

// Seamlessly replace standard browser alert across entire application
window.alert = (message) => {
  return notificationStore.alert(message);
};

// Global modern dialog & toast shortcuts
window.$alert = (msg, title, type) => notificationStore.alert(msg, title, type);
window.$confirm = (msg, title) => notificationStore.confirm(msg, title);
window.$toast = (msg, type, duration) => notificationStore.toast(msg, type, duration);
window.$notify = notificationStore;

app.mount('#app');
