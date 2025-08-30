export default [
    {path: '/vendor_bills_report', component: require('../views/vendor_bills_report/index.vue')},
    {path: '/vendor_bills_report/create', component: require('../views/vendor_bills_report/form.vue')},
    {path: '/vendor_bills_report/:id/edit', component: require('../views/vendor_bills_report/form.vue'), meta: {mode: 'edit'}},
    {path: '/vendor_bills_report/:id', component: require('../views/vendor_bills_report/show.vue')},
]
