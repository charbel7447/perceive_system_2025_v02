<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Finished Products</span>
            <router-link slot="create" to="/payment_request/create" class="btn btn-primary">
                New Product
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.item_code }}</td>
                    <td v-html="props.item.generated_code"></td>
                    <td>{{ props.item.description | trim(80) }}</td>
                    <td><span class="red"><strong>({{ props.item.type.code}})</strong></span>{{ props.item.type.name}}
                    <td>
                        <li v-for="vend in props.item.items">{{vend.client.person}}</li>
                    </td>
                     <td>
                           Available Stock: <strong style="color:red;">{{props.item.qty_on_hand}} {{props.item.uom.unit}}</strong>
                    </td>
                    <td>
                        <li v-for="vend in props.item.items">{{vend.price | formatMoney(vend.currency)}}</li>
                    </td>
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
                resource: '/payment_request',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Item Code', name: 'item_code', sort: true},
                    {title: 'Code', name: 'item_code', sort: true},
                    {title: 'Description', name: 'description', sort: true},
                    {title: 'Product Type', name: 'description', sort: true},
                    {title: 'client', name: 'description', sort: true},
                    {title: 'Stock', name: 'description', sort: true},
                    {title: 'client Price', name: 'unit_price', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/payment_request', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/payment_request', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`payment_request`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
<style>
.red {color:red;}
</style>