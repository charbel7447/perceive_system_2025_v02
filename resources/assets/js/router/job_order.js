export default [
    {path: '/job_order', component: require('../views/job_order/index.vue')},
    {path: '/job_order/create', component: require('../views/job_order/form.vue')},
    {path: '/job_order/:id/edit', component: require('../views/job_order/form.vue'), meta: {mode: 'edit'}},
    {path: '/job_order/:id', component: require('../views/job_order/show.vue')},
]
