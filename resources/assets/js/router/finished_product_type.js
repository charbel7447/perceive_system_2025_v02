export default [
    {path: '/finished_product_type', component: require('../views/finished_product_type/index.vue')},
    {path: '/finished_product_type/create', component: require('../views/finished_product_type/form.vue')},
    {path: '/finished_product_type/:id/edit', component: require('../views/finished_product_type/form.vue'), meta: {mode: 'edit'}},
    {path: '/finished_product_type/:id', component: require('../views/finished_product_type/show.vue')},
]
