export default [
    {path: '/journal_vouchers', component: require('../views/journal_vouchers/index.vue')},
    {path: '/journal_vouchers/create', component: require('../views/journal_vouchers/form.vue')},
    {path: '/journal_vouchers/:id/edit', component: require('../views/journal_vouchers/form.vue'), meta: {mode: 'edit'}},
    {path: '/journal_vouchers/:id/clone', component: require('../views/journal_vouchers/form.vue'), meta: {mode: 'clone'}},
    {path: '/journal_vouchers/:id', component: require('../views/journal_vouchers/show.vue')}
]
