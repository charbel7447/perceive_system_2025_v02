export default [
    {path: '/seller_payments_docs', component: require('../views/seller_payments_docs/index.vue')},
    {path: '/seller_payments_docs/create', component: require('../views/seller_payments_docs/form.vue')},
    // {path: '/seller_payments_docs/:id/edit', component: require('../views/seller_payments_docs/form.vue'), meta: {mode: 'edit'}},
    {path: '/seller_payments_docs/:id', component: require('../views/seller_payments_docs/show.vue')},
]
