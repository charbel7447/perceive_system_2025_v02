export default [
    {path: '/deliverycondition', component: require('../views/deliverycondition/index.vue')},
    {path: '/deliverycondition/create', component: require('../views/deliverycondition/form.vue')},
    {path: '/deliverycondition/:id/edit', component: require('../views/deliverycondition/form.vue'), meta: {mode: 'edit'}},
    {path: '/deliverycondition/:id', component: require('../views/deliverycondition/show.vue')},
]
