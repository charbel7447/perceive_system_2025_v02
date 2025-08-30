export default [
    {path: '/seller_statement', component: require('../views/seller_statement/index.vue')},
    {path: '/seller_statement/create', component: require('../views/seller_statement/form.vue')},
    {path: '/seller_statement/:id/edit', component: require('../views/seller_statement/form.vue'), meta: {mode: 'edit'}},
    {path: '/seller_statement/:id', component: require('../views/seller_statement/show.vue')},
]
