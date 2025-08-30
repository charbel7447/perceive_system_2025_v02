export default [
    {path: '/payment_options', component: require('../views/payment_options/index.vue')},
    {path: '/payment_options/create', component: require('../views/payment_options/form.vue')},
    {path: '/payment_options/:id/edit', component: require('../views/payment_options/form.vue'), meta: {mode: 'edit'}},
    {path: '/payment_options/:id', component: require('../views/payment_options/show.vue')},
]
