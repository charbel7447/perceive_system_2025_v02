export default [
    {path: '/notifications', component: require('../views/notifications/index.vue')},
    {path: '/notifications/create', component: require('../views/notifications/form.vue')},
    {path: '/notifications/:id/edit', component: require('../views/notifications/form.vue'), meta: {mode: 'edit'}},
    {path: '/notifications/:id/clone', component: require('../views/notifications/form.vue'), meta: {mode: 'clone'}},
    {path: '/notifications/:id', component: require('../views/notifications/show.vue')},
]
