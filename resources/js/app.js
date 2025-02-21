import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import App from './App.vue';
import router from './router'
import { TailwindPagination } from 'laravel-vue-pagination';

const app = createApp(App);
app.use(router)
app.component('TailwindPagination', TailwindPagination)

app.mount("#app");
