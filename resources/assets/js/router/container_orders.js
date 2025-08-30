export default [
    {path: '/container_orders', component: require('../views/container_orders/index.vue')},
    {path: '/container_orders/create', component: require('../views/container_orders/form.vue')},
    {path: '/container_orders/:id/edit', component: require('../views/container_orders/form.vue'), meta: {mode: 'edit'}},
    {path: '/container_orders/:id/clone', component: require('../views/container_orders/form.vue'), meta: {mode: 'clone'}},
    {path: '/container_orders/:id/bill', component: require('../views/shipper_bills/form.vue'), meta: {mode: 'container_order'}},
    {path: '/container_orders/:id/receive_order', component: require('../views/container_receive_orders/form.vue'), meta: {mode: 'container_order'}},
    {path: '/container_orders/:id', component: require('../views/container_orders/show.vue')},
]
