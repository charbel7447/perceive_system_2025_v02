export default [
    {path: '/transfers', component: require('../views/transfers/index.vue')},
    {path: '/transfers/:id/', component: require('../views/transfers/index.vue')},
    {path: '/transfers/:id/edit', component: require('../views/transfers/form.vue'), meta: {mode: 'edit'}},
    {path: '/transfers/:id/transfer', component: require('../views/transfers/form.vue'), meta: {mode: 'transfer'}},
]
