export default [
    {path: '/attributes', component: require('../views/attributes/index.vue')},
    {path: '/attributes/create', component: require('../views/attributes/form.vue')},
    {path: '/attributes/:id/edit', component: require('../views/attributes/form.vue'), meta: {mode: 'edit'}},
    {path: '/attributes/:id/clone', component: require('../views/attributes/form.vue'), meta: {mode: 'clone'}},
    {path: '/attributes/:id', component: require('../views/attributes/show.vue')},
]
