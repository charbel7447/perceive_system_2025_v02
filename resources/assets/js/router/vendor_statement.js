export default [
    {path: '/vendor_statement', component: require('../views/vendor_statement/index.vue')},
    {path: '/vendor_statement/create', component: require('../views/vendor_statement/form.vue')},
    {path: '/vendor_statement/:id/edit', component: require('../views/vendor_statement/form.vue'), meta: {mode: 'edit'}},
    {path: '/vendor_statement/:id', component: require('../views/vendor_statement/show.vue')},
]
