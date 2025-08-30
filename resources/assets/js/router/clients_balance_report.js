export default [
    {path: '/clients_balance_report', component: require('../views/clients_balance_report/index.vue')},
    {path: '/clients_balance_report/create', component: require('../views/clients_balance_report/form.vue')},
    {path: '/clients_balance_report/:id/edit', component: require('../views/clients_balance_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/clients_balance_report/:id', component: require('../views/clients_balance_report/show.vue')},
]
