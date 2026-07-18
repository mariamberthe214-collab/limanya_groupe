import { createApp } from 'vue'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap-icons/font/bootstrap-icons.css'
import './style.css'
import App from './App.vue'
import router from './router'
import { revealDirective } from './directives/reveal'

const app = createApp(App)
app.directive('reveal', revealDirective)
app.use(router).mount('#app')
