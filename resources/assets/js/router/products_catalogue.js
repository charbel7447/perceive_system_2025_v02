export default [
    {path: '/products_catalogue', component: require('../views/products_catalogue/index.vue')},
    {path: '/products_catalogue/create', component: require('../views/products_catalogue/form.vue')},
    {path: '/products_catalogue/:id/edit', component: require('../views/products_catalogue/form.vue'), meta: {mode: 'edit'}},
    {path: '/products_catalogue/:id', component: require('../views/products_catalogue/show.vue')},
]
