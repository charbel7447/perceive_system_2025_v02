export default [
    {path: '/employees', component: require('../views/employees/index.vue')},
    {path: '/employees/create', component: require('../views/employees/form.vue')},
    {path: '/employees/:id/edit', component: require('../views/employees/form.vue'), meta: {mode: 'edit'}},
    {path: '/employees/:id', component: require('../views/employees/show.vue')},
]
