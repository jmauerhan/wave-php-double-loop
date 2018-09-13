import Vue from 'vue'
import Vuetify from 'vuetify'
import App from './App.vue'
import 'vuetify/dist/vuetify.min.css'
import axios from "axios"

Vue.use(Vuetify)
Vue.config.productionTip = false

axios.defaults.baseURL = process.env.VUE_APP_API

new Vue({
    render: h => h(App)
}).$mount('#app')
