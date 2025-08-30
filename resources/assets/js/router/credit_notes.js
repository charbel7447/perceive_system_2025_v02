export default [
    {path: '/credit_notes', component: require('../views/credit_notes/index.vue')},
    {path: '/credit_notes/create', component: require('../views/credit_notes/form.vue')},
    // {path: '/advance_payments/:id/edit', component: require('../views/advance_payments/form.vue'), meta: {mode: 'edit'}},
    {path: '/credit_notes/:id', component: require('../views/credit_notes/show.vue')},
]
