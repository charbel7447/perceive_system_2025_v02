<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">List Of Products</span>
              <tr  style="text-align:center;" slot-scope="props" @click="$router.push(`${resource}/${props.item.id}/transfer`)">
                    <!-- <td class="width-1">{{ props.item.id }}</td> -->
                    <td class="width-1">{{ props.item.code }}</td>
                    <!-- <td class="width-2" v-html="props.item.barcode"></td> -->
                    <td class="width-3">{{ props.item.description | trim(80) }}</td>
                    <td class="width-1" v-html="props.item.current_stock" ></td>
                    <td class="width-1">{{props.item.uom.unit}}</td>
                    <td class="width-2">{{ props.item.unit_price | formatMoney(props.item.currency) }}</td>
                </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/transferPanel.vue'
    import { get } from '../../lib/api'

    export default {
        components: { Panel },
        data() {
            return {
               resource: '/transfers',
               heading: [
                    // {title: 'ID', name: 'id', sort: true},
                    {title: 'Item Code', name: 'code', sort: true},
                    // {title: 'BarCode', name: 'barcode', sort: true},
                    {title: 'Product Name', name: 'description', sort: true},
                    {title: 'Quantity', name: 'qty', sort: true},
                     {title: 'Unit', name: 'uom', sort: true},
                    {title: 'Unit Price', name: 'unit_price', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/transfers', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/transfers', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422 {{ props.item.id }} ${props.item.id}
        },
        methods: {
            setData(res) {
                this.$title.set('Transfer Products')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
