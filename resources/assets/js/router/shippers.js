export default [
    {path: '/shippers', component: require('../views/shippers/index.vue')},
    {path: '/shippers/create', component: require('../views/shippers/form.vue')},
    {path: '/shippers/:id/edit', component: require('../views/shippers/form.vue'), meta: {mode: 'edit'}},
    {path: '/shippers/:id', component: require('../views/shippers/show.vue')},
]
