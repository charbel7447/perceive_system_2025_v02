<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Shipping Order {{model.number}}</span>
                <div>
                    <router-link :to="`/container_orders`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/email/${model.id}/purchase_order`"
                        :class="['btn', model.status_id === 1 ? 'btn-primary' : '']"
                        title="Sent Email">
                        <i class="fa fa-envelope-o"></i>
                    </router-link>
                    <router-link :to="`/container_orders/${model.id}/clone`" class="btn"
                        title="Clone">
                        <i class="fa fa-files-o"></i>
                    </router-link>
                    <div class="btn-group">
                        <a :href="`/docs/container_orders/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a :href="`/docs/container_orders/${model.id}?mode=download`" target="_blank"
                            class="btn" title="Download PDF">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                    <router-link v-if="model.is_editable" :to="`/container_orders/${model.id}/edit`"
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                     <dropdown title="More" ref="more">
                        <li v-if="model.status_id === 1">
                            <a @click.stop="markAs(2)">Mark as Sent</a>
                        </li>
                        <li v-if="[1,2, 5].indexOf(model.status_id) >= 0  && (user.id == model.manager_id)">
                            <a @click.stop="markAs(3)">Mark as Confirmed</a>
                        </li>
                        <li  v-if="[1, 2, 3].indexOf(model.status_id) >= 0  && (user.id == model.manager_id)">
                            <a @click.stop="markAs(5)">Mark as Cancelled</a>
                        </li>
                        <li v-if="[3].indexOf(model.status_id) >= 0">
                            <a @click.stop="markAs(6)">Mark as Closed</a>
                        </li>
                        <li v-if="[3, 8].indexOf(model.status_id) >= 0">
                            <a :href="`/docs/container_orders/${model.id}?mode=receive`" target="_blank">Print Receive Order List</a>
                        </li>
                         <li v-if="[2, 3, 8].indexOf(model.status_id) >= 0">
                            <router-link :to="`/container_orders/${model.id}/bill`">
                                Convert to Bill
                            </router-link>
                        </li>
                        <li v-if="[3, 8].indexOf(model.status_id) >= 0">
                            <router-link :to="`/container_orders/${model.id}/receive_order`">
                                Receive Order
                            </router-link>
                        </li>
                        <li v-if="[7].indexOf(model.status_id) >= 0">
                            <router-link :to="`/container_orders/${model.id}/bill`">
                                Convert to Bill
                            </router-link>
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
                            <div class="col col-6">
                                <p><strong>To:</strong></p>
                                <router-link :to="`/shippers/${model.shipper_id}`">
                                    <span>{{model.shipper.person}}</span><br>
                                    <span>{{model.shipper.company}}</span><br>
                                    <pre>{{model.shipper.billing_address}}</pre>
                                    <pre v-if="model.shipper.vat_number"><strong>Vat_Number:</strong>{{model.shipper.vat_number}}</pre>
                                      <label v-if="( model.shipper.vat_status === 1 ) && (display_exchange_rate == 1)">
                                <label style="color:red;display: contents;"><strong>Note:</strong></label>  Vat Included {{first_currency}} & {{second_currency}}<br>
                                  <label style="color:red;display: contents;"><strong>Exchange Rate:</strong></label><strong v-if="base_currency == 1">1 {{first_currency}} = {{model.exchangerate}} {{second_currency}}</strong>
                                </label>
                                <label v-if="( model.shipper.vat_status === 0 ) && (display_vat_rate == 1)">
                                <label style="color:red;display: contents;"><strong>Note:</strong></label>  Vat Excluded
                                </label>
                                <label v-if="( model.shipper.vat_status === 2 ) && (display_vat_rate == 1)">
                                <label style="color:red;display: contents;"><strong>Note:</strong></label>  Vat Included {{first_currency}} Only
                              
                                </label>
                                </router-link>
                            </div>
                            <div class="col col-2">
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
                                            <td>Date:</td>
                                            <td>{{model.date}}</td>
                                        </tr>
                                        <tr v-if="model.reference">
                                            <td>Reference:</td>
                                            <td>{{model.reference}}</td>
                                        </tr>
                                        <tr>
                                            <td>Currency:</td>
                                            <td>{{model.currency.text}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total:</td>
                                            <td>{{model.total | formatMoney(model.currency, false)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Weight:</td>
                                            <td>{{model.total_weight | formatMoney(model.currency, false)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Volume:</td>
                                            <td>{{model.total_volume | formatMoney(model.currency, false)}}</td>
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
                                    <!-- <th>shipper Ref</th> -->
                                    <th>Item Description</th>
                                    <th class="align-center">Unit Price</th>
                                    <th class="align-center">Qty</th>
                                    <th class="align-center">UOM</th>
                                    <th class="align-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in model.items">
                                    <!-- <td class="width-2">
                                        {{item.shipper_reference}}
                                    </td> -->
                                    <td class="width-4">
                                        <router-link :to="`/products/${item.product_id}`">
                                            <pre>{{item.product.description}}</pre>
                                            <small>({{item.product.code}})</small>
                                        </router-link>
                                    </td>
                                    <td class="width-2 align-right">
                                        {{item.unit_price | formatMoney(model.currency, false)}}
                                    </td>
                                    <td class="width-1 align-center">
                                        {{item.quantity}}
                                        <small class="status status-draft" v-if="item.qty_received > 0">
                                            Received: {{item.qty_received}}
                                        </small>
                                    </td>
                                     <td class="width-1 align-center">
                                            {{item.uom.unit}}
                                     </td>
                                    <td class="width-2 align-right">
                                        {{(item.quantity * item.unit_price) | formatMoney(model.currency, false)}}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">Sub Total</td>
                                    <td class="align-right"  colspan="2">
                                        {{model.subtotal | formatMoney(model.currency, false)}}
                                    </td>
                                </tr>
                                <tr v-if="(model.shipper.vat_status == 1)"  class="item-selected-tax">
                                    <td colspan="2"></td>
                                    
                                    <td colspan="2" v-if="display_vat == 1">{{second_currency}} {{ Number(model.totaltax) * (model.exchangerate) | formatMoney(model.currency, false)}}</td>
                                    <td colspan="1" v-else >&nbsp;</td>
                                    <td colspan="1">VAT</td>
                                    <td class="align-right" colspan="2">
                                       {{first_currency}} {{Number(model.totaltax)| formatMoney(model.currency, false)}}
                                    </td>
                                </tr>
                                <tr v-if="(model.shipper.vat_status == 0)"  class="item-selected-tax">
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
                                        <strong>Discount</strong>
                                    </td>
                                    <td class="align-right"  colspan="2">
                                        <strong>
                                            {{model.discount | formatMoney(model.currency)}}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <strong>Shipping</strong>
                                    </td>
                                    <td class="align-right"  colspan="2">
                                        <strong>
                                            {{model.shipping | formatMoney(model.currency)}}
                                        </strong>
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
                                 <br><pre> <strong style="float: left;margin: 0 5px;">Payment Condition:</strong>&nbsp;&nbsp;<span style="float: left;margin: 0 5px;"  v-html="model.paymentcondition.name"></span></pre>
                               <br><pre> <strong style="float: left;margin: 0 5px;">Delivery Terms:</strong>&nbsp;&nbsp;<span style="float: left;margin: 0 5px;" v-html="model.deliverycondition.name"></span></pre>
                               <br><pre> <strong style="float: left;margin: 0 5px;">Delivery Time:</strong>&nbsp;&nbsp;<span style="float: left;margin: 0 5px;" v-html="model.delivery_time"></span></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, post, byMethod } from '../../lib/api'
    import Status from '../../components/status/PurchaseOrder.vue'
    import {Dropdown} from '../../components/dropdown'
    export default {
        computed: {
            user() {
                return window.apex.user
            },
            first_currency(){
                return window.apex.first_currency
            },
            second_currency(){
                return window.apex.second_currency
            },
            display_vat(){
                return window.apex.display_vat
            },
            display_exchange_rate(){
                return window.apex.display_exchange_rate
            },
            display_vat_rate(){
                return window.apex.display_vat_rate
            },
            base_currency() {
                return window.apex.base_currency
            },
        },
        components: { Status, Dropdown },
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
            get(`/api/container_orders/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/container_orders/${to.params.id}`)
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
                byMethod('delete', `/api/container_orders/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/container_orders')
                            this.$message.success(`You have successfully deleted purchase order!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            markAs(status) {
                this.$refs.more.close()
                this.$bar.start()
                post(`/api/container_orders/${this.model.id}/mark`, {status: status})
                    .then(({data}) => {
                        if(data.saved) {
                            Vue.set(this.model, 'status_id', data.status_id)
                            Vue.set(this.model, 'is_editable', data.is_editable)
                            this.$message.success(`You have successfully marked purchase order!`)
                        }
                        this.$bar.finish()
                    })

            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Shipping Order - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
