export default [
    {path: '/warehouses', component: require('../views/warehouses/index.vue')},
    {path: '/warehouses/create', component: require('../views/warehouses/form.vue')},
    {path: '/warehouses/:id/edit', component: require('../views/warehouses/form.vue'), meta: {mode: 'edit'}},
    {path: '/warehouses/:id', component: require('../views/warehouses/show.vue')},
]
