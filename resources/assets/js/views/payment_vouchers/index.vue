<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Payment Voucher</span>
            <router-link slot="create" to="/payment_vouchers/create" class="btn btn-primary">
                New PV
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                <td>{{ props.item.id }}</td>
                <td>{{ props.item.date }}</td>
                <td>{{ props.item.number }}</td>
                <td  :title="props.item.vendor.text">
                    {{ props.item.vendor.text | trim(40) }}
                </td>
                <td>{{ props.item.total_debit | formatMoney(props.item.currency) }}</td>
                <td>{{ props.item.total_debit_usd | formatMoney(props.item.currency) }}</td>
                <td>{{ props.item.created_by }}</td>
                <td>
                    <status :id="props.item.status_id"></status>
                </td>
            </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import Status from '../../components/status/PaymentVoucher.vue'
    import { get } from '../../lib/api'

    export default {
        components: { Panel, Status },
        data() {
            return {
                resource: '/payment_vouchers',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Date', name: 'date', sort: true},
                    {title: 'Number', name: 'number', sort: true},
                    {title: 'Vendor', name: 'vendor', sort: false},
                    {title: 'Amount Applied', name: 'total_debit', sort: true},
                    {title: 'Amount Applied USD', name: 'total_debit_usd', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true},
                    {title: 'Status', name: 'status_id', sort: true}
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/payment_vouchers', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/payment_vouchers', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`Payment Voucher`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
