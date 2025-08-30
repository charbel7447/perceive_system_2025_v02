export default [
    {path: '/receive_orders_report', component: require('../views/receive_orders_report/index.vue')},
    {path: '/receive_orders_report/create', component: require('../views/receive_orders_report/form.vue')},
    {path: '/receive_orders_report/:id/edit', component: require('../views/receive_orders_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/receive_orders_report/:id', component: require('../views/receive_orders_report/show.vue')},
]
