export default [
    {path: '/third_parties_extras', component: require('../views/third_parties_extras/index.vue')},
    {path: '/third_parties_extras/create', component: require('../views/third_parties_extras/form.vue')},
    {path: '/third_parties_extras/:id/edit', component: require('../views/third_parties_extras/form.vue'), meta: {mode: 'edit'}},
    {path: '/third_parties_extras/:id', component: require('../views/third_parties_extras/show.vue')},
]
