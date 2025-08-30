export default [
    {path: '/transfer_accounts', component: require('../views/transfer_accounts/index.vue')},
    {path: '/transfer_accounts/create', component: require('../views/transfer_accounts/form.vue')},
    {path: '/transfer_accounts/:id/edit', component: require('../views/transfer_accounts/form.vue'), meta: {mode: 'edit'}},
    {path: '/transfer_accounts/:id/clone', component: require('../views/transfer_accounts/form.vue'), meta: {mode: 'clone'}},
    {path: '/transfer_accounts/:id', component: require('../views/transfer_accounts/show.vue')},
]
