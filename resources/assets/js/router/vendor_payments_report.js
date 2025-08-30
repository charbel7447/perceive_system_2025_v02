export default [
    {path: '/vendor_payments_report', component: require('../views/vendor_payments_report/index.vue')},
    {path: '/vendor_payments_report/create', component: require('../views/vendor_payments_report/form.vue')},
    {path: '/vendor_payments_report/:id/edit', component: require('../views/vendor_payments_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/vendor_payments_report/:id', component: require('../views/vendor_payments_report/show.vue')},
]
