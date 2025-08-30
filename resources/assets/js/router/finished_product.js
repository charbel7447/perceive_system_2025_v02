export default [
    {path: '/finished_product', component: require('../views/finished_product/index.vue')},
    {path: '/finished_product/create', component: require('../views/finished_product/form.vue')},
    {path: '/finished_product/:id/edit', component: require('../views/finished_product/form.vue'), meta: {mode: 'edit'}},
    {path: '/finished_product/:id', component: require('../views/finished_product/show.vue')},
    {path: '/finished_product/:id/finish_product_image', component: require('../views/finish_product_image/form.vue'), meta: {mode: 'finished_product'}},
]