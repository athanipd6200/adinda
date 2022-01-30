import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

axios.defaults.baseURL = 'http://localhost:8000'
// axios.defaults.baseURL = 'https://adinda.gaweyan.com'
// axios.defaults.baseURL = process.env.MIX_APP_URL+'/api'

export default new Vuex.Store({
  state: {
    user: localStorage.getItem('user') || '',
    url_base: 'http://localhost:8000',
    // url_base: 'https://adinda.gaweyan.com',
    token: localStorage.getItem('token') || '',
    rbac: localStorage.getItem('rbac') || '',
    roles: localStorage.getItem('roles') || '',
    permissions: localStorage.getItem('permissions') || '',
  },

  mutations: {
    setUserData (state, userData) {
      // console.log(userData)
      localStorage.setItem('token', userData.token)
      // localStorage.setItem('roles', userData.roles)
      // localStorage.setItem('permissions', userData.permissions)
      state.token = userData.token
      // state.roles = userData.roles
      // state.permissions = userData.permissions
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

    updateRoles(state, value) {
      state.roles = value
      localStorage.setItem('roles', value)
    },

    updatePermissions(state, value) {
      state.permissions = value
      localStorage.setItem('permissions', value)
    },

    clearUserData () {
      localStorage.removeItem('token')
      localStorage.removeItem('rbac')
      localStorage.removeItem('roles')
      localStorage.removeItem('permissions')
      // localStorage.setItem('token', '')
      // state.token = ''
      location.reload()
    }
  },

  actions: {
    async login ({ commit }, credentials) {
      return await axios
        .post('/api/login', credentials)
        .then(({ data }) => {
          console.log("Data dari web login : "+data)
          // commit('setUserData', data)
          commit('token', data)
        })
    },

    updateToken(context, value) { context.commit('updateToken', value) },

    updateRBAC(context, value) { context.commit('updateRBAC', value) },

    updateRoles(context, value) { context.commit('updateRoles', value) },

    updatePermissions(context, value) { context.commit('updatePermissions', value) },

    async logout ({ commit }, credentials) {
      if(localStorage.getItem('token') && localStorage.getItem('token') != ''){
        // console.log('proses logout');
        return await axios
        .post('/api/logout').then(response => {
            if(response.data.status == true){
              commit('clearUserData')
            }else{
              commit('clearUserData')
            }
        }).catch( errors => {
            console.log("Error aplikasi : " + errors);
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
    roles : state => state.roles,
    permissions : state => state.permissions,
  }
})