import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from "axios";

axios.defaults.baseURL = 'http://laravelapi.test/api'

const app = createApp(App)
app.config.globalProperties.$axios = axios
app.use(store).use(router).mount('#app')
