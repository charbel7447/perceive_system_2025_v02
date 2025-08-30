export default [
    {path: '/subsubcategories', component: require('../views/subsubcategories/index.vue')},
    {path: '/subsubcategories/create', component: require('../views/subsubcategories/form.vue')},
    {path: '/subsubcategories/:id/edit', component: require('../views/subsubcategories/form.vue'), meta: {mode: 'edit'}},
    {path: '/subsubcategories/:id', component: require('../views/subsubcategories/show.vue')},
]
