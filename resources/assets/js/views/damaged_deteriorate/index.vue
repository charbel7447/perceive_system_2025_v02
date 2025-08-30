<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
             
            <span slot="title">Damaged Lists</span>
           
            <router-link  slot="create" to="/damaged_deteriorate/create" class="btn btn-primary">
                New List
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.number }}</td>
                     <td>
                      <li v-for="pro in props.item.items">{{pro.product.code}} - {{pro.product.description}}</li>
                    </td>
                    <td>
                      <li v-for="pro in props.item.items">{{pro.transfer_qty}}</li>
                    </td>
                    <td>
                      <li v-for="pro in props.item.items">{{pro.uom1.unit}}</li>
                    </td>
                    <td>{{ props.item.created_by }}</td>
                  <td>{{ props.item.date }}</td>
                 
                </tr>
        </panel>
    </div>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import Status from '../../components/status/PurchaseRequest.vue'

    import { get } from '../../lib/api'

    export default {
        computed: {
            user() {
                return window.apex.user
            },
        },
        components: { Panel, Status },
        data() {
            return {
                resource: '/damaged_deteriorate',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Number', name: 'number', sort: true},
                    {title: 'Product', name: 'vendor', sort: false},
                    {title: 'Quantity', name: 'total', sort: true},
                    {title: 'U.O.M', name: 'total', sort: true},
                    {title: 'End User', name: 'created_by', sort: true},
                    {title: 'Date', name: 'date', sort: true}
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/damaged_deteriorate', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/damaged_deteriorate', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`Purchase Request`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
