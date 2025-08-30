export default [
    {path: '/paymentcondition', component: require('../views/paymentcondition/index.vue')},
    {path: '/paymentcondition/create', component: require('../views/paymentcondition/form.vue')},
    {path: '/paymentcondition/:id/edit', component: require('../views/paymentcondition/form.vue'), meta: {mode: 'edit'}},
    {path: '/paymentcondition/:id', component: require('../views/paymentcondition/show.vue')},
]
