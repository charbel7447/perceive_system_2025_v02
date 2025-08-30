<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Invoice {{model.number}}</span>
                <div>
                    <router-link :to="`/invoices`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/email/${model.id}/invoice`"
                        :class="['btn', model.status_id === 1 ? 'btn-primary' : '']"
                        title="Sent Email">
                        <i class="fa fa-envelope-o"></i>
                    </router-link>
                    <router-link :to="`/invoices/${model.id}/clone`" class="btn"
                        title="Clone">
                        <i class="fa fa-files-o"></i>
                    </router-link>
                    <div class="btn-group">
                        <a :href="`/docs/invoices/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a :href="`/docs/invoices/${model.id}?mode=download`" target="_blank" class="btn" title="Download PDF">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                    <router-link v-if="model.is_editable" :to="`/invoices/${model.id}/edit`"
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                     <dropdown title="More" ref="more">
                        <li v-if="model.status_id === 1  && (user.id == model.manager_id)">
                            <a @click.stop="markAs(2)">Mark as Confirmed</a>
                        </li>
                        <li v-if="[2, 3].indexOf(model.status_id) >= 0">
                             <a @click.stop="markAs(9)">ReOpen</a>
                        </li>
                        <li v-if="[2, 3].indexOf(model.status_id) >= 0">
                            <router-link :to="`/client_payments/create?client_id=${model.client_id}`">
                                Receive Payment
                            </router-link>
                        </li>
                        <li v-if="[3].indexOf(model.status_id) >= 0">
                              <a @click.stop="markAs(7)">Adjust Invoice Balance</a>
                        </li>
                        <li v-if="[1, 2, 3, 4].indexOf(model.status_id) >= 0">
                            <a :href="`/docs/invoices/${model.id}?mode=delivery`" target="_blank">
                                Print Delivery Note
                            </a>
                        </li>
                        <li>
                            <a @click.stop="deleteModel">
                                Delete
                            </a>
                        </li>
                    </dropdown>
                </div>
            </div>
            <div class="panel-body">
                <div class="document">
                    <div class="document-heading">
                        <div class="row">
                            <div class="col col-4">
                                <p><strong>To:</strong></p>
                                <router-link :to="`/clients/${model.client_id}`">
                                    <span>{{model.client.person}}</span><br>
                                    <span>{{model.client.company}}</span><br>
                                    <pre>{{model.client.billing_address}}</pre>

                                    <label v-if="( model.client.vat_status == 1 ) && (display_vat_rate == 1)">
                                  <label style="color:red;display: contents;"><strong>Vat Rate:</strong></label><strong v-if="(display_vat_rate == 1)">{{model.vatrate}} </strong>
                                </label>
                                <br>

                                      <label v-if="( model.client.vat_status === 1 ) && (display_vat_rate == 1)">
                                <label style="color:red;display: contents;"><strong>Note:</strong></label>  Vat Included {{first_currency}} <br>
                                  <label style="color:red;display: contents;"><strong>Exchange Rate:</strong></label><strong v-if="base_currency == 1">1 {{first_currency}} = {{model.exchangerate}} {{second_currency}}</strong>
                                </label>
                                <label v-if="( model.client.vat_status === 0 ) && (display_vat_rate == 1)">
                                <label style="color:red;display: contents;"><strong>Note:</strong></label>  Vat Excluded
                                </label>
                                <label v-if="( model.client.vat_status === 2 ) && (display_vat_rate == 1)">
                                <label style="color:red;display: contents;"><strong>Note:</strong></label>  Vat Included {{first_currency}} Only
                                </label>

                                 <br>
                                <label v-if="(display_vat == 1)" style="display: inherit;">
                               <strong> Display Second Currency Vat: </strong>
                               <span v-if="display_vat == 1"> True</span>
                                <span v-if="display_vat == 0"> False</span>
                                    </label>
                                </router-link>
                            </div>
                            <div class="col col-3">
                                &nbsp;
                            </div>
                            <div class="col col-5">
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
                                        <tr v-if="model.due_date">
                                            <td>Due Date:</td>
                                            <td>{{model.due_date}}</td>
                                        </tr>
                                        <tr v-if="model.reference">
                                            <td>Reference:</td>
                                            <td>{{model.reference}}</td>
                                        </tr>
                                        <tr v-if="model.invoiceable_id && model.parent_type == 'SalesOrder'">
                                            <td>Sales Order Number:</td>
                                            <td>
                                                 <router-link :to="`/sales_orders/${model.invoiceable_id}`">
                                                    {{model.invoiceable.number}}
                                                </router-link>
                                            </td>
                                        </tr>
                                        <tr v-if="model.invoiceable_id && model.parent_type == 'Quotation'">
                                            <td>Quotation Number:</td>
                                            <td>
                                                <router-link :to="`/quotations/${model.invoiceable_id}`">
                                                    {{model.invoiceable.number}}
                                                </router-link>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Currency:</td>
                                            <td>{{model.currency.text}}</td>
                                        </tr>
                                      <tr>
                                            <td>Total:</td>
                                            <td>{{model.total - model.discount + model.shipping  | formatMoney(model.currency, false)}}</td>
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
                                    <th>Item Code</th>
                                    <th>Item Description</th>
                                   <th class="align-center">Unit Price</th>
                                    <th class="align-center">Qty</th>
                                    <th class="align-center">Discount</th>
                                    <th class="align-center">UOM</th>
                                    <th class="align-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="item in model.items">
                                    <tr>
                                        <td class="width-2">
                                            <router-link :to="`/products/${item.product.code}`">
                                               <span v-if="company_type == 0">{{item.product.code}}</span>
                                               <span v-if="company_type == 1">{{item.product.generated_code}}</span>
                                            </router-link>
                                        </td>
                                        <td class="width-4">
                                            <router-link :to="`/products/${item.product_id}`">
                                                <pre>{{item.product.description}}</pre>
                                            </router-link>
                                        </td>
                                        <td class="width-2 align-right">
                                            {{item.price | formatMoney(model.currency, false)}}
                                        </td>
                                        <td class="width-1 align-center">{{item.quantity}}</td>
                                        <td class="width-1 align-right">
                                            <span v-if="item.discount_usd > 0">{{item.discount_usd}} $</span>
                                            <span v-if="item.discount_per > 0">{{item.discount_per}} %</span>
                                        </td>
                                        <td class="width-1 align-right">
                                            {{item.uom.unit}}
                                        </td>
                                        <td class="width-2 align-right">
                                            <span v-if="item.discount_usd > 0">{{(item.quantity * item.price) - (item.discount_usd) | formatMoney(model.currency, false)}}</span>
                                            <span v-if="item.discount_per > 0">{{(item.quantity * item.price) - ((item.quantity * item.price) * (item.discount_per/100)) | formatMoney(model.currency, false)}}</span>
                                        </td>
                                    </tr>
                                     <tr class="item-tax-show"  v-if="item.taxes.length && (model.client.vat_status == 1)" v-for="tax in item.taxes">
                                         <td  colspan="3" v-if="display_vat == 1">Vat +{{(Number(item.quantity * Number(item.price)* Number(model.vatrate)  ) * tax.rate / 100) | formatMoney(model.currency, false) }}{{second_currency}}</td>
                                        <td v-if="display_vat == 1" colspan="2"> {{tax.name}}</td>
                                        <td v-else colspan="4"> {{tax.name}}</td>
                                        <td>{{tax.rate}}%</td>
                                        <td>+{{(Number(item.quantity * Number(item.price) ) * tax.rate / 100) | formatMoney(model.currency, false)}}</td>
                                    </tr>
                                     <tr class="item-tax-show"  v-if="item.taxes.length && (model.client.vat_status == 2)" v-for="tax in item.taxes">
                                        <td colspan="4">{{first_currency}} {{tax.name}}</td>
                                        <td>{{tax.rate}}%</td>
                                        <td>+{{(Number(item.quantity * Number(item.price) ) * tax.rate / 100) | formatMoney(model.currency, false)}}</td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"></td>
                                    <td colspan="2">Sub Total</td>
                                    <td class="align-right">
                                        {{model.sub_total | formatMoney(model.currency, false)}}
                                    </td>
                                </tr>
                                <tr v-if="(model.client.vat_status == 1)" v-for="(value, key) in selectedTaxes" class="item-selected-tax">
                                    <td colspan="4" class="align-right" v-if="display_vat == 1">{{second_currency}} {{value * model.vatrate | formatMoney(model.currency, false)}}</td>
                                     <td colspan="4" v-else></td>
                                    <td colspan="1">{{key}}</td>
                                    <td class="align-right" colspan="2">
                                       {{first_currency}} {{value| formatMoney(model.currency, false)}}
                                    </td>
                                </tr>
                                 <tr v-if="(model.client.vat_status == 2)" v-for="(value, key) in selectedTaxes" class="item-selected-tax">
                                    <td colspan="2"></td>
                                    
                                    <td colspan="2">{{value| formatMoney(model.currency, true)}}</td>
                                    <td colspan="1">{{key}}</td>
                                    <td class="align-right" colspan="2">
                                       {{first_currency}} {{value| formatMoney(model.currency, false)}}
                                    </td>
                                </tr>
                                 <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">
                                        <strong>Discount</strong>
                                    </td>
                                    <td colspan="2"  class="align-right">
                                        <strong>
                                            {{model.discount | formatMoney(model.currency)}}
                                        </strong>
                                    </td>
                                </tr>
                                  <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">
                                        <strong>Shipping</strong>
                                    </td>
                                    <td colspan="2"  class="align-right">
                                        <strong>
                                            {{model.shipping | formatMoney(model.currency)}}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">
                                        <strong>Total</strong>
                                    </td>
                                    <td colspan="2" class="align-right">
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
                            <div class="document-terms"><br>
                              <pre> <strong>Delivery Time</strong>:&nbsp;&nbsp;{{model.delivery_date}}</pre>
                              <br> <pre> <strong>Validity Date</strong>:&nbsp;&nbsp;{{model.due_date}}</pre>
                                <br><pre> <strong style="float: left;margin: 0 5px;">Payment Condition:</strong>&nbsp;&nbsp;<span style="float: left;margin: 0 5px;" v-html="model.paymentcondition.name"></span></pre>
                               <br><pre> <strong style="float: left;margin: 0 5px;">Delivery Terms:</strong>&nbsp;&nbsp;<span style="float: left;margin: 0 5px;" v-html="model.deliverycondition.name"></span></pre>
                            </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel" v-if="model.advance_payments.length">
            <div class="panel-heading">
                <span class="panel-title">Advance Payments</span>
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
                        <tr v-for="item in model.advance_payments">
                            <td class="width-3">
                                <router-link :to="`/advance_payments/${item.advance_payment_id}`">
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
        <div class="panel" v-if="model.client_payments.length">
            <div class="panel-heading">
                <span class="panel-title">Client Payments</span>
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
                        <tr v-for="item in model.client_payments">
                            <td class="width-3">
                                <router-link :to="`/client_payments/${item.client_payment_id}`">
                                    {{item.parent.number}}
                                </router-link>
                            </td>
                            <td class="width-3">{{item.parent.payment_date}}</td>
                            <td class="width-3">{{item.parent.payment_mode}}</td> 
                             {{item.amount_applied + (item.amount_applied_lbp / item.amount_applied_lbp_rate) + (item.amount_applied_vat / item.amount_applied_vat_rate) | formatMoney(item.parent.currency, true)}}
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
    import Status from '../../components/status/Invoice.vue'
    import { Dropdown } from '../../components/dropdown'
    export default {
        computed: {
            user() {
                return window.apex.user
            },
            base_currency() {
                return window.apex.base_currency
            },
            first_currency(){
                return window.apex.first_currency
            },
            second_currency(){
                return window.apex.second_currency
            },
            display_exchange_rate(){
                return window.apex.display_exchange_rate
            },
            display_vat_rate(){
                return window.apex.display_vat_rate
            },
            company_type(){
                return window.apex.company_type
            },
            display_vat(){
                return window.apex.display_vat
            },
            selectedTaxes() {
                const taxes = {}

                this.model.items.forEach((item) => {
                    if(item.taxes && item.taxes.length) {
                        item.taxes.forEach((tax) => {
                            let key = `${tax.name} ${tax.rate}%`
                            if(taxes.hasOwnProperty(key)) {
                                taxes[key] =  taxes[key] + (Number(item.price) * Number(item.quantity)) * tax.rate / 100
                            } else {
                                taxes[key] = (Number(item.price) * Number(item.quantity)) * tax.rate / 100
                            }
                        })
                    }
                })
                return taxes
            }
        },
        components: { Status, Dropdown },
        data() {
            return {
                show: false,
                model: {
                    items: [],
                    currency: {},
                    client: {},
                    client_payments: [],
                    advance_payments: []
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/invoices/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/invoices/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Invoice - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            },
            markAs(status) {
                this.$refs.more.close()
                this.$bar.start()
                post(`/api/invoices/${this.model.id}/mark`, {status: status})
                    .then(({data}) => {
                        if(data.saved) {
                            Vue.set(this.model, 'status_id', data.status_id)
                            Vue.set(this.model, 'is_editable', data.is_editable)
                            this.$message.success(`You have successfully marked invoice!`)
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
                byMethod('delete', `/api/invoices/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/invoices')
                            this.$message.success(`You have successfully deleted invoice!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            }
        }
    }
</script>
