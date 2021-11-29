import { createStore } from 'vuex'
import axios from "axios";

export default createStore({
  state: {
    phoneNumber: localStorage.getItem('phone_number') || null,
    status : false
  },
  getters: {
    phoneNumber: state => state.phoneNumber
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
            localStorage.setItem('phone_number', credentials.username)
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
