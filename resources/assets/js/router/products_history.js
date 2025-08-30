export default [
    {path: '/products_history', component: require('../views/products_history/index.vue')},
    {path: '/products_history/create', component: require('../views/products_history/form.vue')},
    {path: '/products_history/:id/edit', component: require('../views/products_history/form.vue'), meta: {mode: 'edit'}},
    {path: '/products_history/:id/clone', component: require('../views/products_history/form.vue'), meta: {mode: 'clone'}},
    {path: '/products_history/:id', component: require('../views/products_history/show.vue')},
]
