export default [
    {path: '/categories', component: require('../views/categories/index.vue')},
    {path: '/categories/create', component: require('../views/categories/form.vue')},
    {path: '/categories/:id/edit', component: require('../views/categories/form.vue'), meta: {mode: 'edit'}},
    {path: '/categories/:id', component: require('../views/categories/show.vue')},
]
