export default [
    {path: '/product_dropdown_1', component: require('../views/product_dropdown_1/index.vue')},
    {path: '/product_dropdown_1/create', component: require('../views/product_dropdown_1/form.vue')},
    {path: '/product_dropdown_1/:id/edit', component: require('../views/product_dropdown_1/form.vue'), meta: {mode: 'edit'}},
    {path: '/product_dropdown_1/:id', component: require('../views/product_dropdown_1/show.vue')},
]
