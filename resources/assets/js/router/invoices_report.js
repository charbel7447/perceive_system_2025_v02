export default [
    {path: '/invoices_report', component: require('../views/invoices_report/index.vue')},
    {path: '/invoices_report/create', component: require('../views/invoices_report/form.vue')},
    {path: '/invoices_report/:id/edit', component: require('../views/invoices_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/invoices_report/:id', component: require('../views/invoices_report/show.vue')},
]
