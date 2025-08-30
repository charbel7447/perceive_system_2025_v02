export default [
    {path: '/warehouses_report_criteria', component: require('../views/warehouses_report_criteria/index.vue')},
    {path: '/warehouses_report_criteria/create', component: require('../views/warehouses_report_criteria/form.vue')},
    {path: '/warehouses_report_criteria/:id/edit', component: require('../views/warehouses_report_criteria/form.vue'), meta: {mode: 'edit'}},
    {path: '/warehouses_report_criteria/:id', component: require('../views/warehouses_report_criteria/show.vue')},
]
