import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from "axios";
import VueSocketIO from 'vue-3-socket.io'
import Toaster from "@meforma/vue-toaster"
import Chartkick from 'vue-chartkick'
import Chart from 'chart.js'


const socketIO = new VueSocketIO({
    debug: true,
    connection: 'http://localhost:8080',
})    
let toastOptions = {
    position: 'top',
    timeout: 3000,
}    

axios.defaults.baseURL = 'http://laravelapi.test/api'

const app = createApp(App)
app.config.globalProperties.$axios = axios
app.use(store).use(router).use(Toaster, toastOptions).use(socketIO).mount('#app')
