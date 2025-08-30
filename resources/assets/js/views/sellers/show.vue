<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{model.text}}</span>
                <div>
                    <router-link :to="`/sellers`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/sellers/${model.id}/edit`"
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                    <button class="btn btn-primary" 
                        @click="showModal = true">Add Payment</button>
                    <!-- <a @click.stop="deleteModel" class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                    </a> -->
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-4">
                        <div class="bg-grey">
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-building"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.name}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.email}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-percent"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.phone || '-'}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-money"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.commission}}</p>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="col col-8">
                        <div class="stats">
                            <div class="stat">
                                <div class="stat-inner">
                                    <h2 class="stat-title">commission</h2>
                                    <p class="stat-value">{{stats.commission | formatMoney(model.currency, false)}}</p>
                                </div>
                            </div>
                            <div class="stat">
                                <div class="stat-inner">
                                    <h2 class="stat-title">commission_balance</h2>
                                    <p class="stat-value">{{stats.commission_balance | formatMoney(model.currency, false)}}</p>
                                </div>
                            </div>
                            <div class="stat">
                                <div class="stat-inner stat-last">
                                    <h2 class="stat-title">total_orders_commission</h2>
                                    <p class="stat-value">{{stats.total_orders_commission}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="stats">
                            <div class="stat">
                                <div class="stat-inner">
                                    <h2 class="stat-title">total_orders_count</h2>
                                    <p class="stat-value">{{stats.total_orders_count}}</p>
                                </div>
                            </div>
                            <div class="stat">
                                <div class="stat-inner">
                                    <h2 class="stat-title">Payments Count</h2>
                                    <p class="stat-value">0</p>
                                </div>
                            </div>
                            <div class="stat">
                                <div class="stat-inner">
                                    <h2 class="stat-title">Remaining Balance</h2>
                                    <p class="stat-value">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <mini-panel :resource="salesOrderURL"
            :heading="salesOrderColumns">
            <div slot="title">
                Sales Orders
            </div>
            <router-link :to="`/sales_orders/create?client_id=${model.id}`"
                class="btn btn-secondary btn-sm" slot="create">Create</router-link>
            <tr slot-scope="props" @click="$router.push(`/sales_orders/${props.item.id}`)">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.number }}</td>
                    <td>{{ props.item.date }}</td>
                    <td>{{ props.item.name || '-' }} {{ props.item.email || '-' }}</td>
                    <td>{{ props.item.total_amount | formatMoney(props.item.currency)}}</td>
                    <td><sales-order :id="props.item.status_id" /></td>
                </tr>
        </mini-panel>
     

        <mini-panel :resource="paymentDocsURL"
            :heading="paymentDocsColumns">
            <div slot="title">
                Seller Payments
            </div>
            <router-link :to="`/seller_payments/create?client_id=${model.id}`"
                class="btn btn-secondary btn-sm" slot="create">Create</router-link>
            <tr slot-scope="props" @click="$router.push(`/seller_payments_docs/${props.item.id}`)">
                    <td>{{ props.item.number }}</td>
                    <td>{{ props.item.payment_date }}</td>
                    <td>{{ props.item.payment_mode || '-' }}</td>
                    <td>{{ props.item.payment_reference || '-' }}</td>
                    <td>{{ props.item.total_amount_received | formatMoney(props.item.currency)}}</td>
                    <td><seller-payment :id="props.item.status_id" /></td>
                </tr>
        </mini-panel>

        <mini-panel :resource="paymentURL"
            :heading="paymentColumns">
            <div slot="title">
                Seller Payments Reference
            </div>
          
            <tr slot-scope="props">
                    <td>{{ props.item.number }}</td>
                    <td>{{ props.item.payment_date }}</td>
                    <td>{{ props.item.reference || '-' }}</td>
                    <td>{{ props.item.amount_received | formatMoney(props.item.currency)}}</td>
                    <td><seller-payment :id="props.item.status_id" /></td>
                </tr>
        </mini-panel>
        
        <apply-seller-payment v-if="showModal" @close="showModal = false"></apply-seller-payment>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, byMethod } from '../../lib/api'
    import {Dropdown} from '../../components/dropdown'
    import MiniPanel from '../../components/search/MiniPanel.vue'
    import SalesOrder from '../../components/status/SalesOrder.vue'
    import ApplySellerPayment from '../../components/modals/ApplySellerPayment.vue'
    import SellerPayment from '../../components/status/SellerPayment.vue'
    export default {
        components: {MiniPanel, SalesOrder,Dropdown,ApplySellerPayment,SellerPayment},
        data() {
            return {
                show: false,
                showModal: false,
                salesOrderURL: `/api/mini/sellers/sales_orders_seller/${this.$route.params.id}`,
                salesOrderColumns: ['Number', 'Reference', 'Date', 'client', 'Amount','Status'],
                paymentURL: `/api/mini/sellers/seller_payments/${this.$route.params.id}`,
                paymentColumns: ['Number', 'Date', 'Reference','Toral Amount', 'Amount Received', 'Status'],
                paymentDocsURL: `/api/mini/sellers/seller_payments_docs/${this.$route.params.id}`,
                paymentDocsColumns: ['Number', 'Date','Mode', 'Reference','Total Amount', 'Status'],
                model: {
                    currency: {}
                },
                stats: {}
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/sellers/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/sellers/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            deleteModel() {
                const r = confirm("Are you sure!")
                if(r != true) { return }
                this.$bar.start()
                byMethod('delete', `/api/sellers/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/sellers')
                            this.$message.success(`You have successfully deleted client!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                Vue.set(this.$data, 'stats', res.data.stats)
                this.$title.set(`sellers - ${this.model.text}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
