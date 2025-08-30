<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">List Of Aggregated Products</span>
            <router-link slot="create" to="/products_aggregation/create" class="btn btn-primary">
                New 
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.description | trim(80) }}</td>
                    <td><span class="red"><strong>({{ props.item.category.number}})</strong></span>{{ props.item.category.name}}
                     - 
                     <span class="red"><strong>({{ props.item.sub_category.number}})</strong></span>{{ props.item.sub_category.name}}</td>
                    <td v-if="props.item.minimum_stock >= props.item.current_stock">
                         <strong style="color:red;">{{props.item.current_stock}} {{props.item.uom}}</strong>
                    </td>
                     <td v-if="props.item.minimum_stock < props.item.current_stock">
                           <strong style="color:red;">{{props.item.current_stock}} {{props.item.uom}}</strong>
                    </td>
                    <td>
                         <p v-for="i in props.item.items">
                            <span style="color:red;">{{i.product_code}} - {{i.product_name}}</span> 
                             || 
                            <span style="color:darkblue;">{{i.current_stock}}- {{i.uom}}</span>
                        </p>
                    </td>
                    <!-- <td>
                        <p v-for="i in props.item.items">
                            <span style="color:red;">{{i.product.code}} - {{i.product.description}}</span> 
                             || 
                            <span style="color:darkblue;">{{i.current_stock}}- {{i.uom}}</span>
                        </p>
                    </td> -->
                    <td>{{ props.item.created_by }}</td>
                </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import { get } from '../../lib/api'

    export default {
        computed: {
            user() {
                return window.apex.user
            },
        },
        components: { Panel },
        data() {
            return {
                resource: '/products_aggregation',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Barcode/Item Code', name: 'code', sort: true},
                    {title: 'Description', name: 'description', sort: true},
                    {title: 'Category', name: 'description', sort: true},
                    {title: 'Stock', name: 'description', sort: true},
                    {title: 'From Products - Qty', name: 'description', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/products_aggregation', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/products_aggregation', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`products_aggregation`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
<style>
.red {color:red;}
</style>