export default [
    {path: '/statement', component: require('../views/statement/index.vue')},
    {path: '/statement/create', component: require('../views/statement/form.vue')},
    {path: '/statement/:id/edit', component: require('../views/statement/form.vue'), meta: {mode: 'edit'}},
    {path: '/statement/:id', component: require('../views/statement/show.vue')},
]
