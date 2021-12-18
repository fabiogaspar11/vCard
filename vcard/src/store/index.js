import { createStore } from 'vuex'
import axios from "axios";

export default createStore({
  state: {
    username: null,
    status: false,
    newTransacion:null,
    vcardStatus:null,
    updatedPhoto:null,
    userType:null,
    changesListOfVcards:null,
    changesAdminDefaultCategories:null
  },
  getters: {
    username: state => state.username,
    newTransacion: state => state.newTransacion,
    vcardStatus: state => state.vcardStatus,
    updatedPhoto: state => state.updatedPhoto,
    userType: state =>state.userType,
    changesListOfVcards: state => state.changesListOfVcards,
    changesAdminDefaultCategories: state => state.changesAdminDefaultCategories,
    changesListOfAdmins: state => state.changesListOfAdmins
  },
  mutations: {
    toggleUpdatedPhoto(state, value){
      state.updatedPhoto = value
    },
    toggleNewTransacion(state, value){
      state.newTransacion = value
    },
    toggleVcardStatus(state, value){
      state.vcardStatus = value
    },
    toggleChangesListOfVcards(state, value){
      state.changesListOfVcards = value
    },
    toggleChangesAdminDefaultCategories(state, value){
      state.changesAdminDefaultCategories = value
    },
    toggleChangesListOfAdmins(state, value){
      state.changesListOfAdmins = value
    },
    mutationAuthOk(state) {
      state.status = true
      state.username = localStorage.getItem("username")
      state.newTransacion = null
      state.vcardStatus = null
      state.userType =  localStorage.getItem("user_type")
    },
    mutationAuthReset(state) {
      state.status = false,
      state.username = null,
      state.newTransacion = null
      state.vcardStatus = null
      state.userType = null
    }
  },

  actions: {
    fillStore(context) {
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
            axios.defaults.headers.common.Authorization =
            "Bearer " + response.data.access_token;
            localStorage.setItem("access_token", response.data.access_token);    
            localStorage.setItem("username",  credentials.username);  
            localStorage.setItem("user_type", response.data.user_type);       
            context.commit('mutationAuthOk', response.data)
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
            localStorage.removeItem("access_token");
            localStorage.removeItem("username");
            localStorage.removeItem("user_type");
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
