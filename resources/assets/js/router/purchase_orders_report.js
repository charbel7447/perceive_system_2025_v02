export default [
    {path: '/purchase_orders_report', component: require('../views/purchase_orders_report/index.vue')},
    {path: '/purchase_orders_report/create', component: require('../views/purchase_orders_report/form.vue')},
    {path: '/purchase_orders_report/:id/edit', component: require('../views/purchase_orders_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/purchase_orders_report/:id', component: require('../views/purchase_orders_report/show.vue')},
]
