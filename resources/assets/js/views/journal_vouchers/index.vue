<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Journal Vouchers</span>
           <router-link style="margin: 0 5px;"  slot="create" to="/trial_balance_report"
                        class="btn btn-secondary btn-full"  title="Trial Balance" >
                     Trial Balance
           </router-link>
            <router-link slot="create" to="/journal_vouchers/create" class="btn btn-primary">
                New
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.date }}</td>
                    <td>{{ props.item.number }}</td>

                    <td>
                                    <span v-if="props.item.document_type == 1"> &nbsp;&nbsp;Sales Invoice</span>
                                    <span v-if="props.item.document_type == 2"> &nbsp;&nbsp;Purchase Invoice (Vendor Bill)</span>
                                    <span v-if="props.item.document_type == 3"> &nbsp;&nbsp;Purchase Order</span>
                                    <span v-if="props.item.document_type == 4"> &nbsp;&nbsp;Sales Order</span>
                                    <span v-if="props.item.document_type == 5"> &nbsp;&nbsp;Manual Journal Entry (No linked doc)</span>
                   </td>
                    <td>
                                    <span v-if="props.item.document_type == 1">
                                        <a style="color: red;" :href="'/invoices/' + props.item.document_id"><b>--> {{ props.item.document_number }}</b> </a>
                                    </span>
                                    <span v-if="props.item.document_type == 2">
                                        <a style="color: red;" :href="'/bills/' + props.item.document_id"><b>--> {{ props.item.document_number }}</b> </a>
                                    </span>
                                    <span v-if="props.item.document_type == 3">
                                        <a style="color: red;" :href="'/purchase_orders/' + props.item.document_id"><b>--> {{ props.item.document_number }}</b> </a>
                                    </span>
                                    <span v-if="props.item.document_type == 4">
                                        <a style="color: red;" :href="'/sales_orders/' + props.item.document_id"><b>--> {{ props.item.document_number }}</b> </a>
                                    </span>
                                    <span v-if="props.item.document_type == 5">
                                        <a style="color: red;" :href="'/' + props.item.manual_type + '/' + props.item.document_id"><b>--> {{ props.item.document_number }}</b> </a>
                                    </span>
                    </td>

                    <td v-if="props.item.total_credit > 0">{{ props.item.total_credit | formatMoney(props.item.currency) }}</td>
                    <td v-else>{{ props.item.total_debit | formatMoney(props.item.currency) }}</td>
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
    import Status from '../../components/status/Journal.vue'

    import { get } from '../../lib/api'

    export default {
        computed: {
            user() {
                return window.apex.user
            },
            company_type() {
                return window.apex.company_type
            },
            client_dropdown_1(){
                return window.apex.client_dropdown_1
            },
            client_dropdown_2(){
                return window.apex.client_dropdown_2
            },
        },
        components: { Panel, Status },
        data() {
            return {
                showFilter: 0 ,
                state_array: [],
                city_array: [],
                zip_array:  [],
                from_date: 0,
                to_date: 0,
                client_dropdown_1_array: [],
                client_dropdown_2_array: [],
                client_dropdown1: 0,
                client_dropdown2: 0,
                client_seller_array: [],
                amount: 0,
                client_name:0,
                client_seller:0,
                resource: '/journal_vouchers',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Date', name: 'date', sort: true},
                    {title: 'Number', name: 'number', sort: true},
                    {title: 'Document Type', name: 'document_type', sort: false},
                    {title: 'Document Number', name: 'document_id', sort: false},
                    {title: 'Amount', name: 'total_credit', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true},
                    {title: 'Status', name: 'status_id', sort: true}
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/journal_vouchers', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/journal_vouchers', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        mounted() {
            this.loadOptions();
        },
        methods: {
            loadOptions() {
                get('/api/search/client_dropdown1') .then(client_dropdown_1_array => {
                    this.client_dropdown_1_array = (client_dropdown_1_array.data);
                });
                get('/api/search/client_dropdown2') .then(client_dropdown_2_array => {
                    this.client_dropdown_2_array = (client_dropdown_2_array.data);
                });
                get('/api/search/client_seller') .then(client_seller_array => {
                    this.client_seller_array = (client_seller_array.data);
                });
            },
            search() {
                get(`/api/journal_vouchers/filter?client_name=${this.client_name}&from_date=${this.from_date}&to_date=${this.to_date}&amount=${this.amount}&client_dropdown1=${this.client_dropdown1}&client_dropdown2=${this.client_dropdown2}&client_seller=${this.client_seller}`);
                this.$router.push(`journal_vouchers/filter?client_name=${this.client_name}&from_date=${this.from_date}&to_date=${this.to_date}&amount=${this.amount}&client_dropdown1=${this.client_dropdown1}&client_dropdown2=${this.client_dropdown2}&client_seller=${this.client_seller}`);
            },
            setData(res) {
                this.$title.set(`Journal Vouchers`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
