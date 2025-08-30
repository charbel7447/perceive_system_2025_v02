export default [
    {path: '/price_changes_report', component: require('../views/price_changes_report/index.vue')},
    {path: '/price_changes_report/create', component: require('../views/price_changes_report/form.vue')},
    {path: '/price_changes_report/:id/edit', component: require('../views/price_changes_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/price_changes_report/:id', component: require('../views/price_changes_report/show.vue')},
]
