export default [
    {path: '/container_orders_report', component: require('../views/container_orders_report/index.vue')},
    {path: '/container_orders_report/create', component: require('../views/container_orders_report/form.vue')},
    {path: '/container_orders_report/:id/edit', component: require('../views/container_orders_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/container_orders_report/:id', component: require('../views/container_orders_report/show.vue')},
]
