export default [
    {path: '/import_products', component: require('../views/import_products/index.vue')},
    {path: '/import_products/create', component: require('../views/import_products/form.vue')},
    {path: '/import_products/:id/edit', component: require('../views/import_products/form.vue'), meta: {mode: 'edit'}},
    {path: '/import_products/:id', component: require('../views/import_products/show.vue')},
]
