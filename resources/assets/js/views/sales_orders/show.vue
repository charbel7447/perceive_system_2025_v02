<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Sales Order {{model.number}}</span>
                <div>
                    <div class="btn-group" v-if="model.posted == 1">
                        <router-link style="background:green;color: #fff;" :to="`/journal_vouchers/${model.journal_id}/`"
                          class="btn" title="Journal Document">
                            POSTED &nbsp;<i class="fa fa-arrow-right"></i>
                        </router-link>
                    </div>
                    <router-link :to="`/sales_orders`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/email/${model.id}/sales_order`"
                        :class="['btn', model.status_id === 1 ? 'btn-primary' : '']"
                        title="Sent Email">
                        <i class="fa fa-envelope-o"></i>
                    </router-link>
                    <router-link :to="`/sales_orders/${model.id}/clone`" class="btn"
                        title="Clone">
                        <i class="fa fa-files-o"></i>
                    </router-link>
                    <div class="btn-group">
                        <a :href="`/docs/sales_orders/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a :href="`/docs/sales_orders/${model.id}?mode=download`" target="_blank"
                            class="btn" title="Download PDF">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                    <router-link v-if="model.is_editable" :to="`/sales_orders/${model.id}/edit`"
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                     <dropdown title="More" ref="more">
                        <li v-if="model.status_id === 1">
                            <a @click.stop="markAs(2)">Mark as Sent</a>
                        </li>
                        <li v-if="[1, 2, 4].indexOf(model.status_id) >= 0  && (user.id == model.manager_id)">
                            <a @click.stop="markAs(3)">Mark as Confirmed</a>
                        </li>
                        <li  v-if="[1, 2, 3].indexOf(model.status_id) >= 0  && (user.id == model.manager_id)">
                            <a @click.stop="markAs(4)">Mark as On Hold</a>
                        </li>
                        <li  v-if="[3].indexOf(model.status_id) >= 0">
                            <a @click.stop="markAs(4)">Mark as Closed</a>
                        </li>
                        <li v-if="model.status_id != 1">
                             <a @click.stop="markAs(9)">ReOpen</a>
                        </li>
                        <li v-if="[2, 3, 8].indexOf(model.status_id) >= 0">
                            <router-link :to="`/sales_orders/${model.id}/invoice`">
                                Convert to Invoice
                            </router-link>
                        </li>
                        <!-- <li v-if="[3, 7].indexOf(model.status_id) >= 0"> -->
                            <li>
                            <a :href="`/docs/sales_orders/${model.id}?mode=pick`" target="_blank">
                                Print Pick List (Default)
                            </a>
                        </li>
                        <li>
                            <a :href="`/docs/sales_orders/${model.id}?mode=picklocation`" target="_blank">
                                Print Pick List (Location)
                            </a>
                        </li>
                        <li v-if="[3, 7].indexOf(model.status_id) >= 0">
                            <router-link :to="`/sales_orders/${model.id}/goods_issue`">
                                Post Goods Issue
                            </router-link>
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
                                <p><strong>Bill To:</strong></p>
                                <router-link :to="`/clients/${model.client_id}`">
                                    <span>{{model.client.person}}</span><br>
                                    <span>{{model.client.company}}</span><br>
                                    <pre>{{model.client.billing_address}}</pre>
                                    <label  v-if="(display_vat_rate == 1) " style="color:red;display: contents;"><strong>Vat Rate:</strong></label><strong v-if="(display_vat_rate == 1) ">{{model.vatrate}}</strong>
                                            <br>
                                      <label v-if="( model.client.vat_status === 1 ) && (display_vat_rate == 1) ">
                                        <label style="color:red;display: contents;"><strong>Note:</strong></label> Vat Included  <br>
                                  <label style="color:red;display: contents;"><strong>Exchange Rate:</strong></label><strong v-if="base_currency == 1">1 {{first_currency}} = {{model.exchangerate}} {{second_currency}}</strong>
                                        </label>
                                        <label v-if="( model.client.vat_status === 0 ) && (display_vat_rate == 1) ">
                                        <label style="color:red;display: contents;"><strong>Note:</strong></label>  Vat Excluded
                                      </label>
                                        <label v-if="( model.client.vat_status === 2 ) && (display_vat_rate == 1) ">
                                <label style="color:red;display: contents;"><strong>Note:</strong></label>  Vat Included {{first_currency}} Only
                                </label>
                                <br>
                                <label v-if="(display_vat_rate == 1)" style="display: inherit;">
                               <strong> Display Second Currency Vat: </strong>
                               <span v-if="display_vat == 1"> True</span>
                                <span v-if="display_vat == 0"> False</span>
                                    </label>

                                </router-link>
                            </div>
                            <div class="col col-3">
                                <p><strong>Ship To:</strong></p>
                                <router-link :to="`/clients/${model.client_id}`">
                                    <span>{{model.client.person}}</span><br>
                                    <span>{{model.client.company}}</span><br>
                                    <pre>{{model.client.shipping_address}}</pre>
                                </router-link>
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
                                        <tr v-if="model.reference">
                                            <td>Reference:</td>
                                            <td>{{model.reference}}</td>
                                        </tr>
                                        <tr v-if="model.quotation_id">
                                            <td>Quotation Number:</td>
                                            <td>
                                                <router-link :to="`/quotations/${model.quotation_id}`">
                                                    {{model.quotation.number}}
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
                                    <th>Item Code</th>
                                    <th>Item Description</th>
                                    <th class="align-center">Unit Price</th>
                                    <th class="align-center">quantity</th>
                                    <th class="align-center">UOM</th>
                                    <th class="align-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="item in model.items">
                                    <tr>
                                        <td class="width-2">
                                            <router-link :to="`/products/${item.item_id}`">
                                               <span v-if="company_type == 0">{{item.product.code}}</span>
                                               <span v-if="company_type == 1">{{item.product.generated_code}}</span>
                                            </router-link>
                                        </td>
                                        <td class="width-3">
                                            <router-link :to="`/products/${item.item_id}`">
                                                <!-- <pre>{{item.product.description}}</pre> -->
                                                <pre>{{item.product_name}}</pre>
                                            </router-link>
                                        </td>
                                        <td class="width-2 align-right">
                                            {{item.price | formatMoney(model.currency, false)}}
                                        </td>
                                        <td class="width-1 align-center">
                                            {{item.quantity}}
                                            <small class="status status-draft" v-if="item.qty_issued > 0">
                                            Issued: {{item.qty_issued}}
                                        </small>
                                        </td>
                                        <td class="width-2 align-right">
                                            {{item.uom_code}} 
                                        </td>
                                        <td class="width-2 align-right">
                                            {{(item.quantity * item.price) | formatMoney(model.currency, false)}}
                                        </td>
                                    </tr>
                                    <tr class="item-tax-show" v-if="item.taxes.length && (model.client.vat_status == 1)" v-for="tax in item.taxes">
                                        <td  colspan="2" v-if="display_vat == 1">Vat +{{(Number(item.quantity * Number(item.price)* Number(model.vatrate)  ) * tax.rate / 100) | formatMoney(model.currency, false) }}{{second_currency}}</td>
                                        <td v-if="display_vat == 1" colspan="2"> {{tax.name}}</td>
                                        <td v-else colspan="4"> {{tax.name}}</td>
                                        <td>{{tax.rate}}%</td>
                                        <td>+{{(Number(item.quantity * Number(item.price)  ) * tax.rate / 100) | formatMoney(model.currency, false)}}</td>
                                    </tr>
                                    <tr class="item-tax-show"  v-if="item.taxes.length && (model.client.vat_status == 2)" v-for="tax in item.taxes">
                                        <td colspan="3">{{first_currency}} {{tax.name}}</td>
                                        <td>{{tax.rate}}%</td>
                                        <td>+{{(Number(item.quantity * Number(item.price) ) * tax.rate / 100) | formatMoney(model.currency, false)}}</td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">Sub Total</td>
                                    <td class="align-right">
                                        {{model.sub_total | formatMoney(model.currency, false)}}
                                    </td>
                                </tr>
                                <tr v-if="(model.client.vat_status == 1)"  v-for="(value, key) in selectedTaxes" class="item-selected-tax">
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
                                    <td class="align-right">
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
                                    <td class="align-right">
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
                                    <td class="align-right">
                                        <strong>
                                            {{model.total_amount | formatMoney(model.currency)}}
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
    import { get, post, byMethod } from '../../lib/api'
    import Status from '../../components/status/SalesOrder.vue'
    import {Dropdown} from '../../components/dropdown'
    import Posted from '../../components/status/Posted.vue'
    
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
            display_exchange_rate(){
                return window.apex.display_exchange_rate
            },
            display_vat_rate(){
                return window.apex.display_vat_rate
            },
            second_currency(){
                return window.apex.second_currency
            },
            display_vat(){
                return window.apex.display_vat
            },
            company_type(){
                return window.apex.company_type
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
        components: { Status, Dropdown,Posted },
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
            get(`/api/sales_orders/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/sales_orders/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            deleteModel() {
                this.$refs.more.close()
                const r = confirm("Are you sure!")
                if(r != true) { return }
                this.$bar.start()
                byMethod('delete', `/api/sales_orders/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/sales_orders')
                            this.$message.success(`You have successfully deleted sales order!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            markAs(status) {
                this.$refs.more.close()
                this.$bar.start()
                post(`/api/sales_orders/${this.model.id}/mark`, {status: status})
                    .then(({data}) => {
                        if(data.saved) {
                            Vue.set(this.model, 'status_id', data.status_id)
                            Vue.set(this.model, 'is_editable', data.is_editable)
                            Vue.set(this.model, 'posted', data.posted)
                            Vue.set(this.model, 'journal_id', data.journal_id)
                            this.$message.success(`You have successfully marked sales order!`)
                        }
                        this.$bar.finish()
                    })

            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Sales Order - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
