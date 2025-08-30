export default [
    {path: '/advance_payments_report', component: require('../views/advance_payments_report/index.vue')},
    {path: '/advance_payments_report/create', component: require('../views/advance_payments_report/form.vue')},
    {path: '/advance_payments_report/:id/edit', component: require('../views/advance_payments_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/advance_payments_report/:id', component: require('../views/advance_payments_report/show.vue')},
]
