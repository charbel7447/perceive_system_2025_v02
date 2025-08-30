<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">shipper Payment {{model.number}}</span>
                <div>
                    <router-link :to="`/shipper_payments`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <div class="btn-group">
                        <a :href="`/docs/shipper_payments/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a :href="`/docs/shipper_payments/${model.id}?mode=download`" target="_blank"
                            class="btn" title="Download PDF">
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
                                <p><strong>Payment From:</strong></p>
                                <router-link :to="`/shippers/${model.shipper_id}`">
                                    <span>{{model.shipper.person}}</span><br>
                                    <span>{{model.shipper.company}}</span><br>
                                    <pre>{{model.shipper.billing_address}}</pre>
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
                                            <td>{{model.payment_date}}</td>
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
                                            <td>{{model.currency.text}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total:</td>
                                            <td>{{model.amount_paid | formatMoney(model.currency, false)}}</td>
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
                                    <th>Bill Date</th>
                                    <th>Bill Number</th>
                                    <th class="align-center">Comment</th>
                                    <th class="align-center">Total</th>
                                    <th class="align-center">Amount Applied</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in model.items">
                                    <td class="width-2">
                                        <router-link :to="`/shipper_bills/${item.shipper_bill_id}`">
                                            {{item.shipper_bill.number}}
                                        </router-link>
                                    </td>
                                    <td class="width-4">
                                        {{item.shipper_bill.date}}
                                    </td>
                                    <td class="width-2 align-right">
                                        {{item.comment}}
                                    </td>
                                    <td class="width-2 align-right">
                                        {{item.shipper_bill.total | formatMoney(model.currency, false)}}
                                    </td>
                                    <td class="width-2 align-right">
                                        {{item.amount_applied + (item.amount_applied_lbp / item.amount_applied_lbp_rate) | formatMoney(model.currency, false)}}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <strong>Amount Paid</strong>
                                    </td>
                                    <td class="align-right">
                                        <strong>
                                            {{model.amount_paid | formatMoney(model.currency)}}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
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
                    <img class="panel-image" :src="`/uploads/${model.document}`">
                </a>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get } from '../../lib/api'
    import Status from '../../components/status/VendorPayment.vue'
    export default {
        components: { Status },
        data() {
            return {
                show: false,
                model: {
                    items: [],
                    currency: {},
                    shipper: {}
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/shipper_payments/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/shipper_payments/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`shipper Payment - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
