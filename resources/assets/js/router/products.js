export default [
    {path: '/products', component: require('../views/products/index.vue')},
    {path: '/products/filter', component: require('../views/products/filter.vue')},
    {path: '/products/create', component: require('../views/products/form.vue')},
    {path: '/products/:id/edit', component: require('../views/products/form.vue'), meta: {mode: 'edit'}},
    {path: '/products/:id', component: require('../views/products/show.vue')},
    {path: '/products/:id/product_image', component: require('../views/product_image/form.vue'), meta: {mode: 'products'}},
]
