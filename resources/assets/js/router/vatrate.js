export default [
    {path: '/vatrate', component: require('../views/vatrate/index.vue')},
    {path: '/vatrate/create', component: require('../views/vatrate/form.vue')},
    {path: '/vatrate/:id/edit', component: require('../views/vatrate/form.vue'), meta: {mode: 'edit'}},
    {path: '/vatrate/:id/clone', component: require('../views/vatrate/form.vue'), meta: {mode: 'clone'}},
    {path: '/vatrate/:id', component: require('../views/vatrate/show.vue')},
]
