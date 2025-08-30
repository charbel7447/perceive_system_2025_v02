export default [
    {path: '/sales_orders_report', component: require('../views/sales_orders_report/index.vue')},
    {path: '/sales_orders_report/create', component: require('../views/sales_orders_report/form.vue')},
    {path: '/sales_orders_report/:id/edit', component: require('../views/sales_orders_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/sales_orders_report/:id', component: require('../views/sales_orders_report/show.vue')},
]
