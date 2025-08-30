export default [
    {path: '/payroll', component: require('../views/payroll/index.vue')},
    {path: '/payroll/create', component: require('../views/payroll/form.vue')},
    // {path: '/payroll/:id/edit', component: require('../views/payroll/form.vue'), meta: {mode: 'edit'}},
    {path: '/payroll/:id', component: require('../views/payroll/show.vue')},
]
