export default [
    {path: '/products_aggregation', component: require('../views/products_aggregation/index.vue')},
    {path: '/products_aggregation/create', component: require('../views/products_aggregation/form.vue')},
    {path: '/products_aggregation/:id/edit', component: require('../views/products_aggregation/form.vue'), meta: {mode: 'edit'}},
    {path: '/products_aggregation/:id', component: require('../views/products_aggregation/show.vue')},
]
