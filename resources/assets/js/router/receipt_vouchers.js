export default [
    {path: '/receipt_vouchers', component: require('../views/receipt_vouchers/index.vue')},
    {path: '/receipt_vouchers/create', component: require('../views/receipt_vouchers/form.vue')},
    {path: '/receipt_vouchers/:id/edit', component: require('../views/receipt_vouchers/form.vue'), meta: {mode: 'edit'}},
    {path: '/receipt_vouchers/:id/clone', component: require('../views/receipt_vouchers/form.vue'), meta: {mode: 'clone'}},
    {path: '/receipt_vouchers/:id', component: require('../views/receipt_vouchers/show.vue')}
]
