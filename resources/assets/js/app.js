require('./bootstrap')
window.Vue = require('vue')

// Import packages
import ElementUI from 'element-ui'
import locale from 'element-ui/lib/locale/lang/en'
import 'element-ui/lib/theme-chalk/index.css'

// Import custom components
import EmployeesTable from './components/EmployeesTable.vue'
import Loader from './components/Loader.vue'

// Use packages
Vue.use(ElementUI, { locale })

// Register components
Vue.component('employees-table', EmployeesTable)
Vue.component('loader', Loader)

const app = new Vue({
    el: '#app'
})
