export default [
    {path: '/sellers', component: require('../views/sellers/index.vue')},
    {path: '/sellers/create', component: require('../views/sellers/form.vue')},
    {path: '/sellers/:id/edit', component: require('../views/sellers/form.vue'), meta: {mode: 'edit'}},
    {path: '/sellers/:id', component: require('../views/sellers/show.vue')},
]
