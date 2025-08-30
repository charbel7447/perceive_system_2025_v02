export default [
    {path: '/shipper_payments', component: require('../views/shipper_payments/index.vue')},
    {path: '/shipper_payments/create', component: require('../views/shipper_payments/form.vue')},
    // {path: '/shipper_payments/:id/edit', component: require('../views/shipper_payments/form.vue'), meta: {mode: 'edit'}},
    {path: '/shipper_payments/:id', component: require('../views/shipper_payments/show.vue')},
]
