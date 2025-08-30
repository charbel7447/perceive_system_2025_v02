<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Seller Payment Docs {{model.number}}</span>
                <div>
                    <router-link :to="`/seller_payments_docs`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <!-- <router-link :to="`/email/${model.id}/client_payment`" class="btn btn-primary" title="Sent Email">
                        <i class="fa fa-envelope-o"></i>
                    </router-link> -->
                    <dropdown title="More" ref="more">
                        <li>
                            <a @click.stop="deleteModel">
                                Delete
                            </a>
                        </li>
                    </dropdown>
                    <div class="btn-group">
                        <a :href="`/docs/seller_payments_docs/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a :href="`/docs/seller_payments_docs/${model.id}?mode=download`" target="_blank" class="btn" title="Download PDF">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="document">
                    <div class="document-heading">
                        <div class="row">
                            <div class="col col-4">
                                <p><strong>Payment For:</strong></p>
                                <router-link :to="`/sellers/${model.seller_id}`">
                                    <span>{{model.seller.name}}</span><br>
                                    <span>{{model.seller.phone}}</span><br>
                                </router-link>
                            </div>
                            <div class="col col-4">
                                &nbsp;
                            </div>
                            <div class="col col-4">
                                <table class="document-summary">
                                    <tbody>
                                        <tr>
                                            <td>Status</td>
                                            <td><status :id="model.status_id" /></td>
                                        </tr>
                                        <tr>
                                            <td>Number:</td>
                                            <td>{{model.number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Payment Date:</td>
                                            <td>{{model.date}}</td>
                                        </tr>
                                        <tr>
                                            <td>Payment Mode:</td>
                                            <td>{{model.payment_mode}}</td>
                                        </tr>
                                        <tr v-if="model.payment_reference">
                                            <td>Payment Reference:</td>
                                            <td>{{model.payment_reference}}</td>
                                        </tr>
                                        <tr>
                                            <td>Currency:</td>
                                            <td>{{model.currency.code}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total:</td>
                                            <td>
                                                {{model.total_amount_received | formatMoney(model.currency)}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="document-body">
                        <table class="document-table">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Total Amount</th>
                                    <th>Amount Received</th>
                                    <th class="align-center">Amount Pending</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in model.items">
                                    <td class="width-4">
                                        <router-link :to="`/invoices/${item.sales_order.id}`">
                                            {{item.sales_order.number}}
                                        </router-link>
                                    </td>
                                    <td class="width-2">
                                            {{item.total_amount}}
                                    </td>
                                    <td class="width-2">
                                        {{item.amount_received}}
                                    </td>
                                    <td class="width-2 align-right">
                                        {{item.amount_pending | formatMoney(model.currency, false)}}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="1"></td>
                                    <td colspan="2">
                                        <strong>Amount Received</strong>
                                    </td>
                                    <td class="align-right">
                                        <strong>
                                            {{model.total_amount_received | formatMoney(model.currency)}}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="document-footer row">
                        <div class="col col-9">
                            <strong>Note</strong>
                            <div class="document-terms"><br>
                              <pre> <strong>{{model.note}}</strong></pre>
                            </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel" v-if="model.document">
            <div class="panel-heading">
                <span class="panel-title">Uploaded Document</span>
            </div>
            <div class="panel-body">
                <a :href="`/uploads/${model.document}`" target="_blank">
                    <iframe style="width: 100%;min-height: 500px;" class="panel-image" :src="`/uploads/${model.document}`">
                        </iframe>

                </a>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, post, byMethod  } from '../../lib/api'
    import { Dropdown } from '../../components/dropdown'
    import Status from '../../components/status/SellerPayment.vue'
    export default {
        components: { Status, Dropdown },
        data() {
            return {
                show: false,
                model: {
                    items: [],
                    currency: {},
                    client: {}
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/seller_payments_docs/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/seller_payments_docs/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Seller Payment - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            },
        deleteModel() {
                this.$refs.more.close()
                const r = confirm("Are you sure!")
                if(r != true) { return }
                this.$bar.start()
                byMethod('delete', `/api/seller_payments_docs/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/seller_payments_docs')
                            this.$message.success(`You have successfully deleted clientpayment!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            }}
    }
</script>
