export default [
    {path: '/seller_payments', component: require('../views/seller_payments/index.vue')},
    {path: '/seller_payments/create', component: require('../views/seller_payments/form.vue')},
    // {path: '/seller_payments/:id/edit', component: require('../views/seller_payments/form.vue'), meta: {mode: 'edit'}},
    {path: '/seller_payments/:id', component: require('../views/seller_payments/show.vue')},
]
