export default [
    {path: '/expenses_report', component: require('../views/expenses_report/index.vue')},
    {path: '/expenses_report/create', component: require('../views/expenses_report/form.vue')},
    {path: '/expenses_report/:id/edit', component: require('../views/expenses_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/expenses_report/:id', component: require('../views/expenses_report/show.vue')},
]
