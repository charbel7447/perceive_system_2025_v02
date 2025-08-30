export default [
    {path: '/shipper_statement', component: require('../views/shipper_statement/index.vue')},
    {path: '/shipper_statement/create', component: require('../views/shipper_statement/form.vue')},
    {path: '/shipper_statement/:id/edit', component: require('../views/shipper_statement/form.vue'), meta: {mode: 'edit'}},
    {path: '/shipper_statement/:id', component: require('../views/shipper_statement/show.vue')},
]
