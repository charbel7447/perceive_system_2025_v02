export default [
    {path: '/cost_changes_report', component: require('../views/cost_changes_report/index.vue')},
    {path: '/cost_changes_report/create', component: require('../views/cost_changes_report/form.vue')},
    {path: '/cost_changes_report/:id/edit', component: require('../views/cost_changes_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/cost_changes_report/:id', component: require('../views/cost_changes_report/show.vue')},
]
