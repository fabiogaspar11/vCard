import { createStore } from 'vuex'
import axios from "axios";

export default createStore({
  state: {
    username: null,
    status: false,
    newTransacion:null,
    vcardStatus:null
  },
  getters: {
    username: state => state.username,
    newTransacion : state => state.newTransacion,
    vcardStatus: state => state.vcardStatus,
  },
  mutations: {
    toggleNewTransacion(state, value){
      state.newTransacion = value
    },
    toggleVcardStatus(state, value){
      state.vcardStatus = value
    },
    mutationAuthOk(state) {
      state.status = true
      state.username = localStorage.getItem('username')
      state.newTransacion = null
      state.vcardStatus = null
    },
    mutationAuthReset(state) {
      state.status = false,
      state.username = null,
      state.newTransacion = null
      state.vcardStatus = null
    },
  },

  actions: {
    changeState(context) {
      context.commit('mutationAuthOk')
    },
    logOutDeleteUser(context) {
      context.commit('mutationAuthReset')
    },
    authRequest(context, credentials) {
      return new Promise((resolve, reject) => {
        axios.post('/login', {
          username: credentials.username,
          password: credentials.password
        })
          .then(response => {
            context.commit('mutationAuthOk')
            resolve(response)
          })
          .catch(error => {
            context.commit('mutationAuthReset')
            reject(error)
          })
      })
    },

    authLogout(context) {
      return new Promise((resolve, reject) => {
        axios.post('/logout')
          .then(response => {
            context.commit('mutationAuthReset')
            resolve(response)
          })
          .catch(error => {
            context.commit('mutationAuthReset')
            reject(error)
          })
      })
    },
  },
})
