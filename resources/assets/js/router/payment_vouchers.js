export default [
    {path: '/payment_vouchers', component: require('../views/payment_vouchers/index.vue')},
    {path: '/payment_vouchers/create', component: require('../views/payment_vouchers/form.vue')},
    {path: '/payment_vouchers/:id/edit', component: require('../views/payment_vouchers/form.vue'), meta: {mode: 'edit'}},
    {path: '/payment_vouchers/:id/clone', component: require('../views/payment_vouchers/form.vue'), meta: {mode: 'clone'}},
    {path: '/payment_vouchers/:id', component: require('../views/payment_vouchers/show.vue')}
]