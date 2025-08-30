export default [
    {path: '/client_dropdown_2', component: require('../views/client_dropdown_2/index.vue')},
    {path: '/client_dropdown_2/create', component: require('../views/client_dropdown_2/form.vue')},
    {path: '/client_dropdown_2/:id/edit', component: require('../views/client_dropdown_2/form.vue'), meta: {mode: 'edit'}},
    {path: '/client_dropdown_2/:id', component: require('../views/client_dropdown_2/show.vue')},
]
