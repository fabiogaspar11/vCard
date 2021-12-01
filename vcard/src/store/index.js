import { createStore } from 'vuex'
import axios from "axios";

export default createStore({
  state: {
    username: localStorage.getItem('username') || null,
    status : false
  },
  getters: {
    username: state => state.username
  },
  mutations: {
    mutationAuthOk(state) {
      state.status = true
    },
    mutationAuthReset(state) {
      state.status = false,
      state.phoneNumber = null
    },
  },

  actions: {
    changeState(context){
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
            localStorage.setItem('username', credentials.username)
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
