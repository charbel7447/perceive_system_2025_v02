export default [
    {path: '/payment_options_report', component: require('../views/payment_options_report/index.vue')},
    {path: '/payment_options_report/create', component: require('../views/payment_options_report/form.vue')},
    {path: '/payment_options_report/:id/edit', component: require('../views/payment_options_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/payment_options_report/:id', component: require('../views/payment_options_report/show.vue')},
]
