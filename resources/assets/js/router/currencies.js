export default [
    {path: '/currencies', component: require('../views/currencies/index.vue')},
    {path: '/currencies/create', component: require('../views/currencies/form.vue')},
    {path: '/currencies/:id/edit', component: require('../views/currencies/form.vue'), meta: {mode: 'edit'}},
    {path: '/currencies/:id', component: require('../views/currencies/show.vue')},
]
