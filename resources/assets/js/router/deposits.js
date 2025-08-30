export default [
    {path: '/deposits', component: require('../views/deposits/index.vue')},
    {path: '/deposits/create', component: require('../views/deposits/form.vue')},
    {path: '/deposits/:id/edit', component: require('../views/deposits/form.vue'), meta: {mode: 'edit'}},
    {path: '/deposits/:id/clone', component: require('../views/deposits/form.vue'), meta: {mode: 'clone'}},
    {path: '/deposits/:id', component: require('../views/deposits/show.vue')},
]
