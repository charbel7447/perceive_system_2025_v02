export default [
    {path: '/damaged_deteriorate', component: require('../views/damaged_deteriorate/index.vue')},
    {path: '/damaged_deteriorate/create', component: require('../views/damaged_deteriorate/form.vue')},
    {path: '/damaged_deteriorate/:id/edit', component: require('../views/damaged_deteriorate/form.vue'), meta: {mode: 'edit'}},
    {path: '/damaged_deteriorate/:id/clone', component: require('../views/damaged_deteriorate/form.vue'), meta: {mode: 'clone'}},
    {path: '/damaged_deteriorate/:id/bill', component: require('../views/bills/form.vue'), meta: {mode: 'purchase_order'}},
    {path: '/damaged_deteriorate/:id/receive_order', component: require('../views/receive_orders/form.vue'), meta: {mode: 'purchase_order'}},
    {path: '/damaged_deteriorate/:id', component: require('../views/damaged_deteriorate/show.vue')},
]
