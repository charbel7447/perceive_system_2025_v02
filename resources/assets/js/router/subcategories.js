export default [
    {path: '/subcategories', component: require('../views/subcategories/index.vue')},
    {path: '/subcategories/create', component: require('../views/subcategories/form.vue')},
    {path: '/subcategories/:id/edit', component: require('../views/subcategories/form.vue'), meta: {mode: 'edit'}},
    {path: '/subcategories/:id', component: require('../views/subcategories/show.vue')},
]
