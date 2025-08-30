export default [
    {path: '/accounts', component: require('../views/accounts/index.vue')},
    {path: '/accounts/create', component: require('../views/accounts/form.vue')},
    {path: '/accounts/:id/edit', component: require('../views/accounts/form.vue'), meta: {mode: 'edit'}},
    {path: '/accounts/:id/clone', component: require('../views/accounts/form.vue'), meta: {mode: 'clone'}},
    {path: '/accounts/:id', component: require('../views/accounts/show.vue')},
]
