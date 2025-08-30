export default [
    {path: '/shipper_bills', component: require('../views/shipper_bills/index.vue')},
    {path: '/shipper_bills/create', component: require('../views/shipper_bills/form.vue')},
    {path: '/shipper_bills/:id/edit', component: require('../views/shipper_bills/form.vue'), meta: {mode: 'edit'}},
    {path: '/shipper_bills/:id/clone', component: require('../views/shipper_bills/form.vue'), meta: {mode: 'clone'}},
    {path: '/shipper_bills/:id', component: require('../views/shipper_bills/show.vue')},
]
