export default [
    {path: '/return_deposits', component: require('../views/return_deposits/index.vue')},
    {path: '/return_deposits/create', component: require('../views/return_deposits/form.vue')},
    {path: '/return_deposits/:id/edit', component: require('../views/return_deposits/form.vue'), meta: {mode: 'edit'}},
    {path: '/return_deposits/:id/clone', component: require('../views/return_deposits/form.vue'), meta: {mode: 'clone'}},
    {path: '/return_deposits/:id', component: require('../views/return_deposits/show.vue')},
]
