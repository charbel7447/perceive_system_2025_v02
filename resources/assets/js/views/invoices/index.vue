<template>
    <div>
        <div class="col-md-12">
            <strong class="form-controlz" style="float: left;width: 7%;padding: 2px  0;;">Show Filter</strong>
            <input type="checkbox" :value="1" v-model="showFilter" style="width: 10px;top: 5px;
    position: relative;
    left: -25px;"><br> <br>
        </div>
<form @submit.prevent="search">
<table class="table table-item table-items item items" v-if="showFilter == 1">
    <thead>
        <tr>
            <th>Client Name</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Order Amount ></th>
            <th  v-if="client_dropdown_1">{{client_dropdown_1}}</th>
            <th  v-if="client_dropdown_2">{{client_dropdown_2}}</th>
            <th>Seller</th>
        </tr>
    </thead>
    <tbody>
        
        <tr>
            <td>
                <input class="form-control"  v-model="client_name" type="text" placeholder="client name...">
            </td>
            
            <td>
                <input class="form-control"  v-model="from_date" type="date" placeholder="client name...">
            </td>
            <td>
                <input class="form-control"  v-model="to_date" type="date" placeholder="client name...">
            </td>
            <td>
                <input class="form-control"  v-model="amount" type="text" placeholder="amount...">
            </td>
            <td v-if="client_dropdown_1">
                <select class="form-control"  v-model="client_dropdown1" >
                    <option v-for="client_dropdown1 in client_dropdown_1_array" ><span>{{ client_dropdown1.name }}</span></option>
                </select>
            </td>
            <td v-if="client_dropdown_2">
                <select class="form-control"  v-model="client_dropdown2" >
                    <option v-for="client_dropdown2 in client_dropdown_2_array" ><span>{{ client_dropdown2.name }}</span></option>
                </select>
            </td>
            <td>
                <select class="form-control"  v-model="client_seller" >
                    <option v-for="client_seller in client_seller_array" ><span>{{ client_seller.name }}</span></option>
                </select>
            </td>
            
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" style="text-align: right;">
                <button class="btn btn-primary" type="submit">Filter</button>
            </th>
            <th style="text-align: left;">
                <a class="btn btn-primary" href="/invoices">Clear</a>
            </th>
        </tr>
    </tfoot>
</table>
</form>


        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Invoices</span>
            <router-link slot="create" to="invoices_report/create"
                        class="btn btn-primar"  title="Report" target="_blank">
                     Report &nbsp;&nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i>
           </router-link>
            <router-link slot="create" to="/invoices/create" class="btn btn-primary">
                New 
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td>{{ props.item.id }}</td>
                    <td class="width-2">{{ props.item.date }}</td>
                    <td class="width-2">{{ props.item.number }}</td>
                    <td class="width-4" :title="props.item.client.text">
                        {{ props.item.client.text | trim(40) }}
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
    import Status from '../../components/status/Invoice.vue'

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
                resource: '/invoices',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Date', name: 'date', sort: true},
                    {title: 'Number', name: 'number', sort: true},
                    {title: 'Client', name: 'client', sort: false},
                    {title: 'Amount', name: 'total', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true},
                    {title: 'Status', name: 'status_id', sort: true}
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/invoices', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/invoices', to.query)
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
                get(`/api/invoices/filter?client_name=${this.client_name}&from_date=${this.from_date}&to_date=${this.to_date}&amount=${this.amount}&client_dropdown1=${this.client_dropdown1}&client_dropdown2=${this.client_dropdown2}&client_seller=${this.client_seller}`);
                this.$router.push(`invoices/filter?client_name=${this.client_name}&from_date=${this.from_date}&to_date=${this.to_date}&amount=${this.amount}&client_dropdown1=${this.client_dropdown1}&client_dropdown2=${this.client_dropdown2}&client_seller=${this.client_seller}`);
            },
            setData(res) {
                this.$title.set(`Invoices`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
