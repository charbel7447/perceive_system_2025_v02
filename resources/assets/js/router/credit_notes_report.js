export default [
    {path: '/credit_notes_report', component: require('../views/credit_notes_report/index.vue')},
    {path: '/credit_notes_report/create', component: require('../views/credit_notes_report/form.vue')},
    {path: '/credit_notes_report/:id/edit', component: require('../views/credit_notes_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/credit_notes_report/:id', component: require('../views/credit_notes_report/show.vue')},
]
