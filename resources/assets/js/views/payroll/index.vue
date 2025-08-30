<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Payroll</span>
            <router-link slot="create" to="/payroll/create" class="btn btn-primary">
                New Payroll
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td>{{ props.item.id }}</td>
                    <td class="width-2">{{ props.item.payment_date }}</td>
                    <td class="width-2">{{ props.item.number }}</td>
                    <td class="width-4" :title="props.item.employee.text">
                        {{ props.item.employee.text | trim(60) }}
                    </td>
                    <td class="width-2">{{ props.item.amount_paid || props.item.amount_paid_lbp  | formatMoney(props.item.currency) }}</td>
                    <td class="width-2">{{ props.item.created_by }}</td>
                </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import { get } from '../../lib/api'

    export default {
        components: { Panel },
        data() {
            return {
                resource: '/payroll',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Date', name: 'date', sort: true},
                    {title: 'Number', name: 'number', sort: true},
                    {title: 'Client', name: 'vendor', sort: false},
                    {title: 'Amount Paid', name: 'amount_paid', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/payroll', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/payroll', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`Payroll`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
