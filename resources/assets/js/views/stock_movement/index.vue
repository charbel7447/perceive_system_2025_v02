<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <router-link slot="create" to="/stock_movement/list"  class="btn btn-primar"  title="Report" >
                Report &nbsp;&nbsp;&nbsp;<i class="fa fa-file-excel-o"></i>
           </router-link>
            <span slot="title">List Of Stock Movement</span>
              <tr  style="text-align:center;" slot-scope="props" @click="">
                    <td>
                        <i v-if="props.item.type == 'Initial Stock' " class="fa fa-arrow-right green"></i>
                        <i v-if="props.item.type == 'Deleted Stock' " class="fa fa-arrow-left red"></i>
                        <i v-if="props.item.type == 'Edited Stock' " class="fa fa-arrow-down red"></i>
                        <i v-if="props.item.type == 'Manually Product Division Stock' " class="fa fa-arrow-up green"></i>
                        <i v-if="props.item.type == 'Partially Transfer Stock' " class="fa fa-arrow-up green"></i>
                        <i v-if="props.item.type == 'Division/Addition U.O.M Changed Stock' " class="fa fa-arrow-right blue"></i>
                        <i v-if="props.item.type == 'Manually Transfer Stock' " class="fa fa-arrow-up green"></i>
                        <i v-if="props.item.type == 'Aggregation Product Stock' " class="fa fa-arrow-down blue"></i>
                        <i v-if="props.item.type == 'Receive Order Changed Stock' " class="fa fa-arrow-right green"></i>
                        <i v-if="props.item.type == 'Invoiced Deleted' " class="fa fa-arrow-right green"></i>
                        <i v-if="props.item.type == 'Invoiced Updated' " class="fa fa-arrow-left red"></i>
                        <i v-if="props.item.type == 'Invoiced Draft' " class="fa fa-arrow-left blue"></i>
                        <i v-if="props.item.type == 'Invoiced Confirmed' " class="fa fa-arrow-left red"></i>
                        <i v-if="props.item.type == 'Invoiced' " class="fa fa-arrow-left red"></i>
                        
                        <!-- <i v-else class="fa fa-arrow-up green"></i> -->
                    </td>
                    <td >{{ props.item.product_code }}</td>
                    <td >{{ props.item.product_name | trim(80) }}</td>
                    <td >{{props.item.qty}} {{props.item.uom}}</td>
                    <td >{{ props.item.price | formatMoney(props.item.currency) }}</td>
                    <td >{{ props.item.warehouse.name }}</td>
                    <td >{{ props.item.category.name }}</td>
                    <td >{{ props.item.type }}</td>
                    <td >{{ props.item.purchase_order || '-' }}</td>
                    <td >{{ moment(props.item.created_at).format("DD-MM-YYYY") }}</td>
                </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/transferPanel.vue'
    import { get } from '../../lib/api'
    import moment from "moment";

    export default {
        components: { Panel },
        data() {
            return {
               moment: moment,
               resource: '/stock_movement',
               heading: [
                    {title: '#', name: 'id', sort: true},
                    {title: 'Item Code', name: 'code', sort: true},
                    {title: 'Product Name', name: 'description', sort: true},
                    {title: 'Quantity', name: 'qty', sort: true},
                    {title: 'Unit', name: 'uom', sort: true},
                    {title: 'Warehouse', name: 'unit_price', sort: true},
                    {title: 'Category', name: 'unit_price', sort: true},
                    {title: 'Type', name: 'unit_price', sort: true},
                    {title: 'Document', name: 'unit_price', sort: true},
                    {title: 'Date', name: 'unit_price', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/stock_movement', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/stock_movement', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422 {{ props.item.id }} ${props.item.id}
        },
        methods: {
            setData(res) {
                this.$title.set('Stock Movement')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
