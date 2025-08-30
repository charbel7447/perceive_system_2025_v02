export default [
    {path: '/brands', component: require('../views/brands/index.vue')},
    {path: '/brands/create', component: require('../views/brands/form.vue')},
    {path: '/brands/:id/edit', component: require('../views/brands/form.vue'), meta: {mode: 'edit'}},
    {path: '/brands/:id/clone', component: require('../views/brands/form.vue'), meta: {mode: 'clone'}},
    {path: '/brands/:id', component: require('../views/brands/show.vue')},
]
