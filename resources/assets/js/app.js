require('./bootstrap');
window.Vue = require('vue');

// Import components
import EmployeesTable from './components/EmployeesTable.vue';

// Register components
Vue.component('employees-table', EmployeesTable);

const app = new Vue({
    el: '#app'
});
