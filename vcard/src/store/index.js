import { createStore } from 'vuex'
import axios from "axios";

export default createStore({
  state: {
    username: null,
    status: false,
    type:null
  },
  getters: {
    username: state => state.username,
    type: state => state.type
  },
  mutations: {
    mutationAuthOk(state) {
      state.status = true
      state.username = localStorage.getItem('username')
      state.type = isNaN(parseInt(localStorage.getItem('username'))) ? 'A' : 'V'
    },
    mutationAuthReset(state) {
      state.status = false,
      state.username = null,
      state.type = null
    },
  },

  actions: {
    changeState(context) {
      context.commit('mutationAuthOk')
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
