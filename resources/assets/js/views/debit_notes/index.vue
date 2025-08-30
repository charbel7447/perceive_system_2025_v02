<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Debit Notes</span>
            <router-link slot="create" to="debit_notes_report/create"
                            class="btn btn-primar"  title="Report" target="_blank">
                        Report &nbsp;&nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i>
            </router-link>
            <router-link slot="create" to="/debit_notes/create" class="btn btn-primary">
                New
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                <td>{{ props.item.id }}</td>
                <td class="width-2">{{ props.item.payment_date }}</td>
                <td class="width-2">{{ props.item.number }}</td>
                <td class="width-4" :title="props.item.client.text">
                    {{ props.item.client.text | trim(40) }}
                </td>
                <td class="width-2">{{ props.item.amount_received + (props.item.amount_received_lbp / props.item.exchangerate) | formatMoney(props.item.currency) }}</td>
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
    import Status from '../../components/status/DebitNotes.vue'
    import { get } from '../../lib/api'

    export default {
        components: { Panel, Status },
        data() {
            return {
                resource: '/debit_notes',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Date', name: 'date', sort: true},
                    {title: 'Number', name: 'number', sort: true},
                    {title: 'Client', name: 'client', sort: false},
                    {title: 'Debit Amount', name: 'amount_received', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true},
                    {title: 'Status', name: 'status_id', sort: true}
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/debit_notes', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/debit_notes', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('Debit Notes')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
