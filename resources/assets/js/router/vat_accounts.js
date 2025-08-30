export default [
    {path: '/vat_accounts', component: require('../views/vat_accounts/index.vue')},
    {path: '/vat_accounts/create', component: require('../views/vat_accounts/form.vue')},
    {path: '/vat_accounts/:id/edit', component: require('../views/vat_accounts/form.vue'), meta: {mode: 'edit'}},
    {path: '/vat_accounts/:id/clone', component: require('../views/vat_accounts/form.vue'), meta: {mode: 'clone'}},
    {path: '/vat_accounts/:id', component: require('../views/vat_accounts/show.vue')},
]
