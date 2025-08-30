export default [
    {path: '/products_division', component: require('../views/products_division/index.vue')},
    {path: '/products_division/:id/', component: require('../views/products_division/index.vue')},
    {path: '/products_division/:id/edit', component: require('../views/products_division/form.vue'), meta: {mode: 'edit'}},
    {path: '/products_division/:id/division', component: require('../views/products_division/form.vue'), meta: {mode: 'division'}},
]
