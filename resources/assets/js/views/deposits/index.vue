<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Deposits</span>
            <router-link slot="create" to="/deposits/create" class="btn btn-primary">
                New 
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.number }}</td>
                    <td>{{ props.item.employee.name }}</td>
                    <td>{{ props.item.to_account.name }}</td>
                    <td v-if="props.item.to_account.id === 1"> {{ props.item.amount | formatMoney(props.item.currency) }}</td>
                    <td v-if="props.item.to_account.id === 2">LBP {{ props.item.amount | formatMoney(props.item.amount) }}</td>
                    <td>{{ props.item.exchangerate }}</td>
                    <td>{{ props.item.deposit_date }}</td>
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
                resource: '/deposits',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Number', name: 'date', sort: true},
                    {title: 'From Account', name: 'client', sort: false},
                    {title: 'To Account', name: 'total', sort: true},
                    {title: 'Amount', name: 'created_by', sort: true},
                    {title: 'Exchange Rate', name: 'status_id', sort: true},
                    {title: 'Date', name: 'number', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/deposits', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/deposits', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`deposits`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
