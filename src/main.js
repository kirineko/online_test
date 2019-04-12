import Vue from 'vue'
import App from './App'
import 'mp-weui/lib/style.css'

Vue.config.productionTip = false
App.mpType = 'app'

const app = new Vue(App)
app.$mount()
