export default [
    {path: '/clients', component: require('../views/clients/index.vue')},
    {path: '/clients/filter', component: require('../views/clients/filter.vue')},
    {path: '/clients/create', component: require('../views/clients/form.vue')},
    {path: '/clients/:id/edit', component: require('../views/clients/form.vue'), meta: {mode: 'edit'}},
    {path: '/clients/:id', component: require('../views/clients/show.vue')},
]
