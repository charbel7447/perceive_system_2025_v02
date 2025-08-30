export default [
    {path: '/machines', component: require('../views/machines/index.vue')},
    {path: '/machines/create', component: require('../views/machines/form.vue')},
    {path: '/machines/:id/edit', component: require('../views/machines/form.vue'), meta: {mode: 'edit'}},
    {path: '/machines/:id', component: require('../views/machines/show.vue')},
]
