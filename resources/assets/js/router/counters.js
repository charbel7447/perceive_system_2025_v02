export default [
    {path: '/counters', component: require('../views/counters/index.vue')},
    {path: '/counters/create', component: require('../views/counters/form.vue')},
    {path: '/counters/:id/edit', component: require('../views/counters/form.vue'), meta: {mode: 'edit'}},
    {path: '/counters/:id', component: require('../views/counters/show.vue')},
]
