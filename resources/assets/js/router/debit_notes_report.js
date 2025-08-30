export default [
    {path: '/debit_notes_report', component: require('../views/debit_notes_report/index.vue')},
    {path: '/debit_notes_report/create', component: require('../views/debit_notes_report/form.vue')},
    {path: '/debit_notes_report/:id/edit', component: require('../views/debit_notes_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/debit_notes_report/:id', component: require('../views/debit_notes_report/show.vue')},
]
