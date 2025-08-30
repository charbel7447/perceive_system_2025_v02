export default [
    {path: '/products_report', component: require('../views/products_report/index.vue')},
    {path: '/products_report/create', component: require('../views/products_report/form.vue')},
    {path: '/products_report/:id/edit', component: require('../views/products_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/products_report/:id', component: require('../views/products_report/show.vue')},
]
