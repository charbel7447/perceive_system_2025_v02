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
            <th>City</th>
            <th>State</th>
            <th>Zip code</th>
            <th>balance greater than</th>
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
                <select class="form-control"  v-model="city" type="text" placeholder="city...">
                    <option v-for="city in city_array" v-value="city.name">{{city.name}}</option>
                </select>
            </td>
            <td>
                <select class="form-control"  v-model="state" >
                    <option v-for="state in state_array" ><span>{{ state.name }}</span></option>
                </select>
            </td>
            <td>
                <select class="form-control"  v-model="zip" >
                    <option v-for="zip in zip_array" ><span>{{ zip.name }}</span></option>
                </select>
            </td>
            <td>
                <input class="form-control"  v-model="balance" type="text" placeholder="balance...">
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
                <a class="btn btn-primary" href="/clients">Clear</a>
            </th>
        </tr>
    </tfoot>
</table>
</form>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Clients</span>
            <router-link slot="create" to="/clients/create" class="btn btn-primary">
                New Client
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${props.item.id}`)">
                <td class="width-1">{{ props.item.id }}</td>
                <td class="width-2">{{ props.item.name }}</td>
                <td class="width-3">{{ props.item.company }}</td>
                <td class="width-3">{{ props.item.email }}</td>
                <td class="width-2">{{ props.item.created_by }}</td>
                <td class="width-1">{{ props.item.created_at }}</td>
            </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
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
        components: { Panel },
        data() {
            return {
                showFilter: 0 ,
                state_array: [],
                city_array: [],
                zip_array:  [],
                client_dropdown_1_array: [],
                client_dropdown_2_array: [],
                client_dropdown1: 0,
                client_dropdown2: 0,
                client_seller_array: [],
                state: 0,
                city: 0,
                zip: 0,
                balance:0,
                client_name:0,
                resource: '/clients/filter',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'name', name: 'name', sort: true},
                    {title: 'Company', name: 'company', sort: true},
                    {title: 'Email', name: 'email', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true},
                    {title: 'Created At', name: 'created_at', sort: true}
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/clients/filter', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/clients/filter', to.query)
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
                get('/api/search/state') .then(state_array => {
                    this.state_array = (state_array.data);
                });
                get('/api/search/zip') .then(zip_array => {
                    this.zip_array = (zip_array.data);
                });
                get('/api/search/city') .then(city_array => {
                    this.city_array = (city_array.data);
                });
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
                get(`/api/clients/filter?client_name=${this.client_name}&city=${this.city}&state=${this.state}&zip=${this.zip}&balance=${this.balance}&client_dropdown1=${this.client_dropdown1}&client_dropdown2=${this.client_dropdown2}&client_seller=${this.client_seller}`);
                this.$router.push(`filter?client_name=${this.client_name}&city=${this.city}&state=${this.state}&zip=${this.zip}&balance=${this.balance}&client_dropdown1=${this.client_dropdown1}&client_dropdown2=${this.client_dropdown2}&client_seller=${this.client_seller}`);
            },
            setData(res) {
                this.$title.set('Clients')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
