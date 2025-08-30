export default [
    {path: '/seller_payments_docs_report', component: require('../views/seller_payments_docs_report/index.vue')},
    {path: '/seller_payments_docs_report/create', component: require('../views/seller_payments_docs_report/form.vue')},
    {path: '/seller_payments_docs_report/:id/edit', component: require('../views/seller_payments_docs_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/seller_payments_docs_report/:id', component: require('../views/seller_payments_docs_report/show.vue')},
]
