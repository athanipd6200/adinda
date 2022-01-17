import Vue from 'vue';

import Vuetify from 'vuetify'

import VueYouTubeEmbed from 'vue-youtube-embed'
// import firebase from 'firebase/app'
// import 'firebase/firestore'

Vue.use(VueYouTubeEmbed)

import colors from 'vuetify/lib/util/colors'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

import DatetimePicker from 'vuetify-datetime-picker'
import 'vuetify-datetime-picker/src/stylus/main.styl'

Vue.use(DatetimePicker)

export default new Vuetify({
    theme: {
        themes: {
            light: {
                primary: '#D8C4EA',
                secondary: '#FDE395',
                tertiary: '#ABDAF8',
                quartiary: '#2F2F2F',
                accent: '#F4EEA9',
                base: '#FBFFE2',
                error: '#C2185B',
              },
              dark: {
                primary: '#EDC8A4',
              },
        },
    },
})

import { ValidationProvider, extend } from 'vee-validate'
Vue.use({ ValidationProvider, extend } )

import CKEditor from '@ckeditor/ckeditor5-vue2';
Vue.use( CKEditor );

import router from './router/router'
import store from './router/store'
import axios from 'axios'

Vue.component('app', require('./app.vue').default);

const app = new Vue({
    el: '#app',
    router,
    store,
    created () {
        const userInfo = localStorage.getItem('token')
        if (userInfo) {
          // const userData = JSON.parse(userInfo)
          // this.$store.commit('setUserData', userData)
          this.$store.commit('updateToken', userInfo)
        }
        axios.interceptors.response.use(
          response => response,
          error => {
            if (error.response.status === 401) {
              this.$store.dispatch('logout')
            }
            return Promise.reject(error)
          }
        )
      },
    vuetify: new Vuetify(),
});