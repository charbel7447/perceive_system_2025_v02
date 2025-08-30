export default [
    {path: '/raw_material_type', component: require('../views/raw_material_type/index.vue')},
    {path: '/raw_material_type/create', component: require('../views/raw_material_type/form.vue')},
    {path: '/raw_material_type/:id/edit', component: require('../views/raw_material_type/form.vue'), meta: {mode: 'edit'}},
    {path: '/raw_material_type/:id', component: require('../views/raw_material_type/show.vue')},
]
