<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">shipper_bills</span>
            <router-link  slot="create" to="shipper_bills_report/create"
                        class="btn btn-primar"  title="Report" target="_blank">
                     Report By Criteria &nbsp;&nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i>
           </router-link>
           <router-link  slot="create" to="shipper_bills_report/show"
                        class="btn btn-primar"  title="Report" target="_blank">
                     Report By Criteria &nbsp;&nbsp;&nbsp;<i class="fa fa-file-excel-o"></i>
           </router-link>
            <router-link slot="create" to="/shipper_bills/create" class="btn btn-primary">
                New Bill
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                <td>{{ props.item.id }}</td>
                <td class="width-2">{{ props.item.date }}</td>
                <td class="width-2">{{ props.item.number }}</td>
                <td class="width-3" :title="props.item.shipper.text">
                    {{ props.item.shipper.text | trim(40) }}
                </td>
                <td class="width-2">{{ props.item.total | formatMoney(props.item.currency) }}</td>
                <td class="width-2">{{ props.item.created_by }}</td>
                <td class="width-2">
                    <status :id="props.item.status_id"></status>
                </td>
            </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import Status from '../../components/status/Bill.vue'

    import { get } from '../../lib/api'

    export default {
        components: { Panel, Status },
        data() {
            return {
                resource: '/shipper_bills',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Date', name: 'date', sort: true},
                    {title: 'Number', name: 'number', sort: true},
                    {title: 'Vendor', name: 'vendor', sort: false},
                    {title: 'Amount', name: 'total', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true},
                    {title: 'Status', name: 'status_id', sort: true}
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/shipper_bills', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/shipper_bills', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`shipper_bills`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
