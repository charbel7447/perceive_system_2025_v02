export default [
    {path: '/product_dropdown_2', component: require('../views/product_dropdown_2/index.vue')},
    {path: '/product_dropdown_2/create', component: require('../views/product_dropdown_2/form.vue')},
    {path: '/product_dropdown_2/:id/edit', component: require('../views/product_dropdown_2/form.vue'), meta: {mode: 'edit'}},
    {path: '/product_dropdown_2/:id', component: require('../views/product_dropdown_2/show.vue')},
]
