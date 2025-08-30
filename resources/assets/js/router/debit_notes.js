export default [
    {path: '/debit_notes', component: require('../views/debit_notes/index.vue')},
    {path: '/debit_notes/create', component: require('../views/debit_notes/form.vue')},
    // {path: '/advance_payments/:id/edit', component: require('../views/advance_payments/form.vue'), meta: {mode: 'edit'}},
    {path: '/debit_notes/:id', component: require('../views/debit_notes/show.vue')},
]
