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

const apiDomain = process.env.VUE_APP_API_DOMAIN
const wsConnection = process.env.VUE_APP_WS_CONNECTION

const socketIO = new VueSocketIO({
    debug: true,
    connection: wsConnection,
})    

let toastOptions = {
    position: 'top',
    timeout: 3000,
}    
axios.defaults.baseURL = `${apiDomain}/api`

//axios.defaults.baseURL = 'http://laravelapi.test/api'

const app = createApp(App)
app.config.globalProperties.$axios = axios
app.use(store).use(router).use(Chartkick.use(Chart)).use(Toaster, toastOptions).use(socketIO).mount('#app')

app.config.globalProperties.$serverURL = process.env.VUE_APP_API_DOMAIN