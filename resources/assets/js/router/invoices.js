export default [
    {path: '/invoices', component: require('../views/invoices/index.vue')},
    {path: '/invoices/filter', component: require('../views/invoices/filter.vue')},
    {path: '/invoices/create', component: require('../views/invoices/form.vue')},
    {path: '/invoices/:id/edit', component: require('../views/invoices/form.vue'), meta: {mode: 'edit'}},
    {path: '/invoices/:id/clone', component: require('../views/invoices/form.vue'), meta: {mode: 'clone'}},
    {path: '/invoices/:id', component: require('../views/invoices/show.vue')},
    {path: '/invoices/:id/customer_returns', component: require('../views/customer_returns/form.vue'), meta: {mode: 'invoices'}},

]
