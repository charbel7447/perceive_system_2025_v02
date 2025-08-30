export default [
    {path: '/customer_returns_report', component: require('../views/customer_returns_report/index.vue')},
    {path: '/customer_returns_report/create', component: require('../views/customer_returns_report/form.vue')},
    {path: '/customer_returns_report/:id/edit', component: require('../views/customer_returns_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/customer_returns_report/:id', component: require('../views/customer_returns_report/show.vue')},
]
