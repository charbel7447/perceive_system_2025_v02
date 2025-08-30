export default [
    {path: '/chart_of_accounts', component: require('../views/chart_of_accounts/index.vue')},
    {path: '/chart_of_accounts/list', component: require('../views/chart_of_accounts/list.vue')},
    {path: '/chart_of_accounts/create', component: require('../views/chart_of_accounts/form.vue')},
    {path: '/chart_of_accounts/:id/edit', component: require('../views/chart_of_accounts/form.vue'), meta: {mode: 'edit'}},
    {path: '/chart_of_accounts/:id/clone', component: require('../views/chart_of_accounts/form.vue'), meta: {mode: 'clone'}},
    {path: '/chart_of_accounts/:id', component: require('../views/chart_of_accounts/show.vue')},
]
