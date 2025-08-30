export default [
    {path: '/uom', component: require('../views/uom/index.vue')},
    {path: '/uom/create', component: require('../views/uom/form.vue')},
    {path: '/uom/:id/edit', component: require('../views/uom/form.vue'), meta: {mode: 'edit'}},
    {path: '/uom/:id/clone', component: require('../views/uom/form.vue'), meta: {mode: 'clone'}},
    {path: '/uom/:id', component: require('../views/uom/show.vue')},
]
