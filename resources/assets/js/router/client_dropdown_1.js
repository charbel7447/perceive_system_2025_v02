export default [
    {path: '/client_dropdown_1', component: require('../views/client_dropdown_1/index.vue')},
    {path: '/client_dropdown_1/create', component: require('../views/client_dropdown_1/form.vue')},
    {path: '/client_dropdown_1/:id/edit', component: require('../views/client_dropdown_1/form.vue'), meta: {mode: 'edit'}},
    {path: '/client_dropdown_1/:id', component: require('../views/client_dropdown_1/show.vue')},
]
