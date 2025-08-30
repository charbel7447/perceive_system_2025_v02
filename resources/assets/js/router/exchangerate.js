export default [
    {path: '/exchangerate', component: require('../views/exchangerate/index.vue')},
    {path: '/exchangerate/create', component: require('../views/exchangerate/form.vue')},
    {path: '/exchangerate/:id/edit', component: require('../views/exchangerate/form.vue'), meta: {mode: 'edit'}},
    {path: '/exchangerate/:id/clone', component: require('../views/exchangerate/form.vue'), meta: {mode: 'clone'}},
    {path: '/exchangerate/:id', component: require('../views/exchangerate/show.vue')},
]
