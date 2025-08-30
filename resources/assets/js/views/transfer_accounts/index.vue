<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Transfer Accounts</span>
            <router-link slot="create" to="/transfer_accounts/create" class="btn btn-primary">
                New 
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td>
                        <i v-if="props.item.from_account.id == 1" class="fa fa-arrow-left green"></i>
                        <i v-if="props.item.from_account.id == 2" class="fa fa-arrow-right red"></i>
                    </td>
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.number }}</td>
                    <td>{{ props.item.from_account.name }}</td>
                    <td>{{ props.item.to_account.name }}</td>
                    <td>{{ props.item.amount | formatMoney(props.item.currency) }}</td>
                    <td>{{ props.item.exchangerate }}</td>
                    <td>{{ props.item.transfer_date }}</td>
                </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import Status from '../../components/status/Invoice.vue'

    import { get } from '../../lib/api'

    export default {
        components: { Panel, Status },
        data() {
            return {
                resource: '/transfer_accounts',
                heading: [
                    {title: '', name: '', sort: true},
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Number', name: 'date', sort: true},
                    {title: 'From Employee', name: 'client', sort: false},
                    {title: 'To Account', name: 'total', sort: true},
                    {title: 'Amount', name: 'created_by', sort: true},
                    {title: 'Exchange Rate', name: 'status_id', sort: true},
                    {title: 'Date', name: 'number', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/transfer_accounts', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/transfer_accounts', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`transfer_accounts`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
