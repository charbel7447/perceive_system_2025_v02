export default [
    {path: '/quotations_report', component: require('../views/quotations_report/index.vue')},
    {path: '/quotations_report/create', component: require('../views/quotations_report/form.vue')},
    {path: '/quotations_report/:id/edit', component: require('../views/quotations_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/quotations_report/:id', component: require('../views/quotations_report/show.vue')},
]
