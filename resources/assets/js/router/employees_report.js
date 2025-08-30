export default [
    {path: '/employees_report', component: require('../views/employees_report/index.vue')},
    {path: '/employees_report/create', component: require('../views/employees_report/form.vue')},
    {path: '/employees_report/:id/edit', component: require('../views/employees_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/employees_report/:id', component: require('../views/employees_report/show.vue')},
]
