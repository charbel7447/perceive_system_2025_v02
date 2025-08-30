<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Bill {{model.number}}</span>
                <div>
                    <div class="btn-group" v-if="model.posted == 1">
                        <router-link style="background:green;color: #fff;" :to="`/journal_vouchers/${model.journal_id}/`"
                          class="btn" title="Journal Document">
                            POSTED &nbsp;<i class="fa fa-arrow-right"></i>
                        </router-link>
                    </div>
                    <router-link :to="`/bills`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/bills/${model.id}/clone`" class="btn"
                        title="Clone">
                        <i class="fa fa-files-o"></i>
                    </router-link>
                    <div class="btn-group">
                        <a :href="`/docs/bills/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a :href="`/docs/bills/${model.id}?mode=download`" target="_blank" class="btn" title="Download PDF">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                    <router-link v-if="model.is_editable && model.status_id === 1" :to="`/bills/${model.id}/edit`"
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                     <dropdown title="More" ref="more">
                        <li v-if="model.status_id === 6">
                            <a @click.stop="markAs(3)">Mark as Closed - Paid</a>
                        </li>
                        <li v-if="model.status_id === 1">
                            <a @click.stop="markAs(2)">Mark as Sent</a>
                        </li>
                         <li v-if="model.status_id === 2">
                            <a @click.stop="markAs(6)">Mark as Confirmed</a>
                        </li>
                        <li v-if="model.status_id == 6 || model.status_id == 5">
                            <router-link :to="`/payment_vouchers/create?vendor_id=${model.vendor_id}`">
                                Add Payment PV
                            </router-link>
                        </li>
                        <li v-if="model.status_id > 1">
                            <router-link :to="`/expenses/create?vendor_id=${model.vendor_id}&bill_id=${model.id}`">
                                Add Purchase Overheads
                            </router-link>
                        </li>
                        <li v-if="model.status_id === 2">
                            <a @click.stop="markAs(7)">Adjust Bill</a>
                        </li>
                        <li>
                            <a @click.stop="deleteModel">Delete</a>
                        </li>
                    </dropdown>
                </div>
            </div>
            <div class="panel-body">
                <div class="document">
                    <div class="document-heading">
                        <div class="row">
                            <div class="col col-4">
                                <p><strong>Bill From:</strong></p>
                                <router-link :to="`/vendors/${model.vendor_id}`">
                                    <span>{{model.vendor.person}}</span><br>
                                    <span>{{model.vendor.company}}</span><br>
                                    <pre>{{model.vendor.billing_address}}</pre>
                                         <pre v-if="model.vendor.vat_number"><strong>Vat_Number:</strong>{{model.vendor.vat_number}}</pre>
                                      <label v-if="( model.vendor.vat_status == 1 ) && (display_vat_rate == 1)">
                                <label style="color:red;display: contents;"><strong>Note:</strong></label>  Vat Included<br>
                                  <label style="color:red;display: contents;" v-if=" (display_exchange_rate == 1)"><strong>Exchange Rate:</strong></label><strong v-if="base_currency == 1">1 {{first_currency}} = {{model.exchangerate}} {{second_currency}}</strong>
                                </label>
                                <label v-if="( model.vendor.vat_status == 0 ) && (display_vat_rate == 1)">
                                <label style="color:red;display: contents;"><strong>Note:</strong></label>  Vat Excluded
                                </label>
                                <label v-if="( model.vendor.vat_status == 2 ) && (display_vat_rate == 1)">
                                <label style="color:red;display: contents;"><strong>Note:</strong></label>  Vat Included 
                              
                                </label>
                                </router-link>
                            </div>
                            <div class="col col-2">
                                &nbsp;
                            </div>
                            <div class="col col-6">
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
                                            <td>Date:</td>
                                            <td>{{model.date}}</td>
                                        </tr>
                                        <tr v-if="model.reference">
                                            <td>Reference:</td>
                                            <td>{{model.reference}}</td>
                                        </tr>
                                        <tr v-if="model.purchase_order">
                                            <td>Purchase Order Number:</td>
                                            <td>
                                                <router-link :to="`/purchase_orders/${model.purchase_order_id}`">
                                                    {{model.purchase_order.number}}
                                                </router-link>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Currency:</td>
                                            <td>{{model.currency.text}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total:</td>
                                            <td>{{model.total | formatMoney(model.currency, false)}}</td>
                                        </tr>
                                        <tr class="btn-group" >
                                            <td>&nbsp;</td>
                                            <td> 
                                                <Posted
                                                :key="model.journal_id"
                                                :id="model.posted"
                                                :journal-id="model.journal_id"
                                                />
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
                                    <th>Vendor Ref</th>
                                    <th>Item Description</th>
                                    <th class="align-center">Unit Price</th>
                                    <th class="align-center">Qty</th>
                                    <th class="align-center">UOM</th>
                                    <th class="align-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="item in model.items">
                                <tr>
                                    <td class="width-2">
                                        {{item.vendor_reference}}
                                    </td>
                                    <td class="width-4">
                                        <router-link :to="`/products/${item.product_id}`">
                                            <!-- <pre>{{item.product.description}}</pre> -->
                                            <pre>{{item.product_name}}</pre>
                                            <small>({{item.product.code}})</small>
                                        </router-link>
                                    </td>
                                    <td class="width-2 align-right">
                                        {{item.unit_price | formatMoney(model.currency, false)}}
                                    </td>
                                    <td class="width-1 align-center">{{item.qty}}</td>
                                    <td class="width-1 align-center">
                                            {{item.uom_unit}}
                                     </td>
                                    <td class="width-2 align-right">
                                        {{(item.qty * item.unit_price) | formatMoney(model.currency, false)}}
                                    </td>
                                </tr>
                                 <tr class="item-tax-show"  v-if="item.taxes.length && (model.vendor.vat_status == 1)" v-for="tax in item.taxes">
                                         <td  colspan="2" v-if="display_vat == 1">Vat +{{(Number(item.qty * Number(item.unit_price)* Number(model.exchangerate)  ) * tax.rate / 100) | formatMoney(model.currency, false) }}{{second_currency}}</td>
                                        <td v-if="display_vat == 1" colspan="2"> {{tax.name}}</td>
                                        <td v-else colspan="3"> {{tax.name}}</td>
                                        <td>{{tax.rate}}%</td>
                                        <td>+{{(Number(item.qty * Number(item.unit_price) ) * tax.rate / 100) | formatMoney(model.currency, false)}}</td>
                                    </tr>
                                     <tr class="item-tax-show"  v-if="item.taxes.length && (model.vendor.vat_status == 2)" v-for="tax in item.taxes">
                                        <td colspan="2">{{first_currency}} {{tax.name}}</td>
                                        <td>{{tax.rate}}%</td>
                                        <td>+{{(Number(item.qty * Number(item.unit_price) ) * tax.rate / 100) | formatMoney(model.currency, false)}}</td>
                                    </tr>
                                    </template>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">Sub Total</td>
                                    <td class="align-right"  colspan="2">
                                        {{model.subtotal | formatMoney(model.currency, false)}}
                                    </td>
                                </tr>
                                <tr v-if="(model.vendor.vat_status == 1)"  class="item-selected-tax">
                                    <td colspan="2"></td>
                                    
                                    <td colspan="2" v-if="display_vat == 1">{{second_currency}} {{ Number(model.totaltax)  * (model.exchangerate) | formatMoney(model.currency, false)}}</td>
                                    <td colspan="1" v-else >&nbsp;</td>
                                    <td colspan="1">VAT</td>
                                    <td class="align-right" colspan="2">
                                        {{Number(model.totaltax) | formatMoney(model.currency, true)}}
                                    </td>
                                </tr>
                                <tr v-if="(model.vendor.vat_status == 0)"  class="item-selected-tax">
                                    <td colspan="2"></td>
                                    
                                    <td colspan="2"></td>
                                    <td colspan="1">
                                        <!-- No Vat -->
                                    </td>
                                    <td class="align-right" colspan="2">
                                      
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <strong>Total</strong>
                                    </td>
                                    <td class="align-right"  colspan="2">
                                        <strong>
                                            {{model.total | formatMoney(model.currency)}}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="document-footer row">
                        <div class="col col-9">
                            <strong>Terms and Conditions</strong>
                            <div class="document-terms">
                                <pre>{{model.terms}}</pre>
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
                    <img class="panel-image" :src="`/uploads/${model.document}`">
                </a>
            </div>
        </div>
        <div class="panel" v-if="model.vendor_payments.length">
            <div class="panel-heading">
                <span class="panel-title">Vendor Payments</span>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Payment Date</th>
                            <th>Payment Mode</th>
                            <th>Amount Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in model.vendor_payments">
                            <td class="width-3">
                                <router-link :to="`/vendor_payments/${item.vendor_payment_id}`">
                                    {{item.parent.number}}
                                </router-link>
                            </td>
                            <td class="width-3">{{item.parent.payment_date}}</td>
                            <td class="width-3">{{item.parent.payment_mode}}</td>
                            <td class="width-3">{{item.amount_applied | formatMoney(item.parent.currency)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, post, byMethod } from '../../lib/api'
    import Status from '../../components/status/Bill.vue'
    import {Dropdown} from '../../components/dropdown'
    import Posted from '../../components/status/Posted.vue'

    export default {
        computed: {
           base_currency() {
                return window.apex.base_currency
            },
            first_currency(){
                return window.apex.first_currency
            },
            display_vat_rate(){
                return window.apex.display_vat_rate
            },
            display_exchange_rate(){
                return window.apex.display_exchange_rate
            },
            second_currency(){
                return window.apex.second_currency
            },
            display_vat(){
                return window.apex.display_vat
            },
        },
        components: { Status, Dropdown, Posted },
        data() {
            return {
                show: false,
                model: {
                    items: [],
                    currency: {},
                    vendor: {},
                    vendor_payments: []
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/bills/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/bills/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            markAs(status) {
                this.$refs.more.close()
                this.$bar.start()
                post(`/api/bills/${this.model.id}/mark`, {status: status})
                    .then(({data}) => {
                        if(data.saved) {
                            Vue.set(this.model, 'status_id', data.status_id)
                            Vue.set(this.model, 'is_editable', data.is_editable)
                            Vue.set(this.model, 'posted', data.posted)
                            Vue.set(this.model, 'journal_id', data.journal_id)
                            this.$message.success(`You have successfully marked Bill!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
             deleteModel() {
                this.$refs.more.close()
                const r = confirm("Are you sure!")
                if(r != true) { return }
                this.$bar.start()
                byMethod('delete', `/api/bills/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/bills')
                            this.$message.success(`You have successfully deleted bill!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Bill - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
