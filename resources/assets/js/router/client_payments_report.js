export default [
    {path: '/client_payments_report', component: require('../views/client_payments_report/index.vue')},
    {path: '/client_payments_report/create', component: require('../views/client_payments_report/form.vue')},
    {path: '/client_payments_report/:id/edit', component: require('../views/client_payments_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/client_payments_report/:id', component: require('../views/client_payments_report/show.vue')},
]
