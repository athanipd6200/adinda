import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

axios.defaults.baseURL = 'http://localhost'
// axios.defaults.baseURL = 'https://sida.gaweyan.com'
// axios.defaults.baseURL = process.env.MIX_APP_URL+'/api'

export default new Vuex.Store({
  state: {
    user: null,
    url_base: 'http://localhost',
    // url_base: 'https://sida.gaweyan.com',
    token: localStorage.getItem('token') || '',
    rbac: localStorage.getItem('rbac') || '',
  },

  mutations: {
    setUserData (state, userData) {
      console.log(userData)
      localStorage.setItem('token', userData.token)
      state.token = userData.token
      axios.defaults.headers.common.Authorization = `Bearer ${userData.token}`
    },

    updateToken(state, value) {
      state.token = value
      localStorage.setItem('token', value)
    },

    updateRBAC(state, value) {
      state.rbac = value
      localStorage.setItem('rbac', value)
    },

    clearUserData () {
      localStorage.removeItem('token')
      localStorage.removeItem('rbac')
      // localStorage.setItem('token', '')
      // state.token = ''
      location.reload()
    }
  },

  actions: {
    login ({ commit }, credentials) {
      return axios
        .post('/api/login', credentials)
        .then(({ data }) => {
          console.log("Data dari web login : "+data)
          // commit('setUserData', data)
          commit('token', data)
        })
    },

    updateToken(context, value) { context.commit('updateToken', value) },

    updateRBAC(context, value) { context.commit('updateRBAC', value) },

    logout ({ commit }, credentials) {
      if(localStorage.getItem('token') && localStorage.getItem('token') != ''){
        // console.log('proses logout');
        return axios
        .post('/api/logout').then(response => {
            if(response.data.status == true){
              commit('clearUserData')
            }else{
              commit('clearUserData')
            }
        }).catch( errors => {
            console.log("Error aplikasi : " + errors.response.data);
            commit('clearUserData')
        })
      }else{
        console.log("Token tidak ada");
      }
    }
  },

  getters : {
    isLogged: state => state.token,
    url_base: state => state.url_base,
    user_data: state => state.user,
    token : state => state.token,
    rbac : state => state.rbac,
  }
})