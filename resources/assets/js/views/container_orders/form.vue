<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Shipping Order</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Shipper</label>
                            <typeahead :initial="form.shipper"
                                :url="shipperURL"
                                @input="onShipperUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.shipper_id"></error-text>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Currency</label>
                            <typeahead :initial="form.currency"
                                :url="currencyURL"
                                @input="onCurrencyUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.currency_id"></error-text>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>
                                Number
                                <small>(Auto Generated)</small>
                            </label>
                            <span class="form-control">{{form.number}}</span>
                        </div>
                    </div>
                    <div class="col col-3">
                          <div class="form-group">
                            <label v-if="( form.vat_status == 2 ) && (display_vat_rate == 1) ">
                               <label style="color:red;display: contents;">Note:</label>  Vat Included VAT {{first_currency}} Only
                            </label>
                            <label v-if="( form.vat_status == 1 ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Vat Included
                            </label>
                            <label v-if="( form.vat_status == 0 ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Vat Excluded
                            </label>
                            <label v-if="( form.vat_status == null ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Adjust Client VAT to proceed
                            </label>
                            <label v-if="( form.vat_status == 1 ) && (display_exchange_rate == 1)"  style="display: inherit;">
                           <label style="color:red;display: contents;">Exchange Rate:</label><strong v-if="base_currency == 1">1 {{first_currency}} = {{form.exchangerate}} {{second_currency}}</strong>
                           </label>
                            <label v-if="(display_vat_rate == 1)" style="display: inherit;">
                               <strong> Display Second Currency Vat: </strong>
                               <span v-if="display_vat == 1"> True</span>
                               <span v-if="display_vat == 0"> False</span>
                           </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" class="form-control" v-model="form.date">
                            <error-text :error="error.date"></error-text>
                        </div>
                    </div>
                    <div class="col col-3" v-if="( form.vat_status == 1 )">
                        <div class="form-group">
                            <label>Exchange Rate (1x to {{second_currency}})</label>
                            <input type="text" class="form-control" v-model="form.exchangerate">
                            <error-text :error="error.exchangerate"></error-text>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>
                                Reference
                                <small>(Optional)</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.reference">
                            <error-text :error="error.reference"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>container_size</label>
                            <input type="text" class="form-control" v-model="form.container_size">
                        </div>
                    </div>
                    <div class="col col-3" v-if="(display_vat_rate == 1)">
                        <div class="form-group">
                            <label>
                                Vat Rate
                                <small>(Optional)</small>
                                
                            </label>
                            <input type="text" class="form-control" v-model="form.vatrate">
                            <error-text :error="error.vatrate"></error-text>
                        </div>
                    </div>
                </div>
                 <hr>
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>
                                Upload Document
                                <small>(Optional: client purchase order)</small>
                            </label>
                            <file-upload @ready="onDocument"></file-upload>
                            <error-text :error="error.document"></error-text>
                        </div>
                    </div>
                </div>
                <hr>
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th>Unit Price</th>
                            <th>Current Stock</th>
                            <th>Shipped Qty</th>
                            <th>volume</th>
                            <th>ct</th>
                            <th>weight</th>
                            <th>UOM</th>
                            <th>VAT</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(item, index) in form.items">
                            <tr>
                                <td :class="['width-4', errors(`items.${index}.product_id`)]">
                                    <typeahead :initial="item.product" :trim="80"
                                        @input="onProductUpdated(item, index, $event)"
                                        :url="productURL"
                                    >
                                    </typeahead>
                                    
                                    <input style="display: none;" type="text" class="form-control" v-model="item.product_id">
                                    <error-text :error="error[`items.${index}.product_id`]"></error-text>
                                </td>
                                <td :class="['width-1', errors(`items.${index}.unit_price`)]">
                                    <input type="text" class="form-control" v-model="item.unit_price">
                                    <error-text :error="error[`items.${index}.unit_price`]"></error-text>
                                </td>
                                <td :class="['width-1', errors(`items.${index}.current_stock`)]">
                                    <input type="text" class="form-control" v-model="item.current_stock">
                                    <error-text :error="error[`items.${index}.current_stock`]"></error-text>
                                </td>
                                <td :class="['width-1', errors(`items.${index}.quantity`)]">
                                    <input type="text" class="form-control" v-model="item.quantity">
                                    <error-text :error="error[`items.${index}.quantity`]"></error-text>
                                </td>
                                <td :class="['', errors(`items.${index}.volume_box`)]">
                                <input type="text" class="form-control" v-model="item.volume_box">
                                <error-text :error="error[`items.${index}.volume_box`]"></error-text>
                                </td>
                                <td :class="['', errors(`items.${index}.ct_box`)]">
                                    <input type="text" class="form-control" v-model="item.ct_box">
                                    <error-text :error="error[`items.${index}.ct_box`]"></error-text>
                                </td>
                                <td :class="['', errors(`items.${index}.weight_box`)]">
                                    <input type="text" class="form-control" v-model="item.weight_box">
                                    <error-text :error="error[`items.${index}.weight_box`]"></error-text>
                                </td>
                                
                                 <td :class="['width-1', errors(`items.${index}.uom_id`)]" style="display:none;">
                                    <input type="text" class="form-control" v-model="item.uom_id">
                                    <input type="text" class="form-control" v-model="item.uom2_id">
                                    <error-text :error="error[`items.${index}.uom_id`]"></error-text>
                                </td>
                                <td :class="['width-1', errors(`items.${index}.uom_id`)]">
                                        <typeahead :initial="item.uom"
                                            :url="uomURL"
                                            @input="onUomUpdate(item, index, $event)"
                                        >
                                        </typeahead>
                                        <error-text :error="error.uom_id"></error-text>
                                        <!-- {{ item.uom }} -->
                                </td>
                                <td>
                                    <!-- <input disabled type="text" class="form-control" v-for="x in item.uom" v-model="x.unit"> -->
                                     <span class="form-control" v-for="tax in item.taxes">{{tax.rate}}%</span>
                                     <input style="display:none;" type="text" class="form-control" v-model="item.tax_rate">
                                     <input style="display:none;" type="text" class="form-control" v-model="item.tax_name">
                                    </td>
                                    <td >
                                     <span class="form-control">{{Number(item.quantity * Number(item.unit_price)+ (Number(item.quantity * Number(item.unit_price) ) * item.tax_rate / 100)) | formatMoney(form.currency, false)}}
                                    </span>
                                </td>
                                <!-- <td class="width-1">
                                    <span class="form-control align-right">
                                        {{Number(item.quantity * Number(item.price))  | formatMoney(form.currency, false)}}
                                    </span>
                                </td> -->
                                <td>
                                    <button class="item-remove" @click="removeProduct(item, index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="danger error red" v-if="item.quantity > item.current_stock ">Error ! Order Will Not Proceed, Shipped quantity greater than stock</span>
                                </td>
                            </tr>
                            <!-- <tr class="item-tax" v-if="item.taxes.length" v-for="tax in item.taxes">
                                <td colspan="5" v-if="display_vat == 1">Vat +{{(Number(item.quantity * Number(item.price)* Number(form.vatrate)  ) * tax.rate / 100) | formatMoney(form.currency, false) }}{{second_currency}}</td>
                                <td colspan="5">{{tax.name}}</td>
                                <td>{{tax.rate}}%</td>
                                <td>+{{(Number(item.quantity * Number(item.price) ) * tax.rate / 100) | formatMoney(form.currency, false)}}</td>
                            </tr> -->
                           
                            
                        </template>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewLine">
                                    Add new line
                                </button>
                            </td>
                            
                            <td class="item-footer" colspan="7">Sub Total</td>
                            <td class="item-footer" colspan="2">
                                <span class="item-dark form-control">
                                    {{subTotal | formatMoney(form.currency, false)}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td></td><td></td>
                            <td class="item-footer" colspan="6"><b>Discount</b> (Fixed Amount)</td>
                            <td class="item-footer" colspan="2" style="">
                                    <input type="text" class="form-control" v-model="form.discount">
                             </td>
                        </tr>
                        <tr>
                            <td></td><td></td>
                            <td class="item-footer" colspan="6"><b>Shipping</b> (Fixed Amount)</td>
                            <td class="item-footer" colspan="2" style="">
                                    <input type="text" class="form-control" v-model="form.shipping">
                             </td>
                        </tr>
                        <tr v-for="(value, key) in selectedTaxes" class="item-selected-tax">
                            <td class="item-footer" colspan="7" v-if="display_vat == 1">Vat +{{ value * Number(form.vatrate) | formatMoney(form.currency, false) }}{{second_currency}}</td>
                            <td class="item-footer" colspan="7">
                                <span>{{key}}</span>
                            </td>
                            <td class="item-footer" colspan="2">
                                <span class="">
                                     {{value | formatMoney(form.currency, false)}}
                                </span>
                            </td>
                        </tr>
                         <tr v-if="form.vat_status === 2" v-for="(value, key) in selectedTaxes" class="item-selected-tax">
                            <td class="item-empty"></td>
                            <td class="item-footer" colspan="7">
                                <span>{{key}}</span>
                            </td>
                            <td class="item-footer" colspan="2">
                                <span class="">
                                    {{value | formatMoney(form.currency, true)}}
                                </span>
                            </td>
                        </tr>
                         <tr>
                            <td class="item-empty"></td>
                            <td class="item-footer" colspan="7">
                                <strong>Total quantity:</strong>
                            </td>
                            <td class="item-footer" colspan="2">
                                <strong class="item-dark form-control">
                                    {{totalquantity}}
                                </strong>    
                            </td>
                        </tr>
                        <tr>
                            <td class="item-empty"></td>
                            <td class="item-footer" colspan="7">
                                <strong>Total</strong>
                            </td>
                            <td class="item-footer" colspan="2">
                                <strong class="item-dark form-control">
                                    {{total | formatMoney(form.currency)}} 
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Terms and Conditions</label>
                   
                   <div class="col col-3x">
                        <div class="form-group">
                            <label>Delivery Time
                                <small>(Optional)</small>
                            </label>
                          <!-- <input type="date" class="form-control" v-model="form.delivery_date">   -->
                          <textarea class="form-control" v-model="form.delivery_date"></textarea>
                            <error-text :error="error.delivery_date"></error-text>
                        </div>
                    </div>
                    <div class="col col-3x">
                        <div class="form-group">
                            <label>Payment Condition</label>
                            <typeahead :initial="form.paymentcondition"
                                :url="paymentconditionURL"
                                @input="onPaymentConditionUpdated"
                            >
                            </typeahead>
                            <error-text :error="error.paymentcondition_id"></error-text>
                        </div>
                    </div>
                     <div class="col col-3x">
                        <div class="form-group">
                            <label >Delivery Condition</label>
                            <typeahead :initial="form.deliverycondition"
                                :url="deliveryconditionURL"
                                @input="onDeliveryConditionUpdated"
                            >
                            </typeahead>
                            <error-text :error="error.deliverycondition_id"></error-text>
                        </div>
                    </div>

                    <div class="col col-3x">
                       <div class="form-group">
                            <label>Note:
                                <small>(Optional)</small>
                            </label>
                            <textarea class="form-control" v-model="form.terms"></textarea>
                       </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <spinner v-if="isProcessing"></spinner>
                <div class="btn-group" v-else>
                    <button :disabled="isProcessing" @click="save" class="btn btn-primary">
                        Save
                    </button>
                    <button :disabled="isProcessing" v-if="!isEdit"
                        @click="saveAndNew" class="btn btn-secondary">
                        Save and New
                    </button>
                    <router-link :disabled="isProcessing" :to="`${resource}/${$route.params.id}`"
                        class="btn" v-if="isEdit">
                        Cancel
                    </router-link>
                    <router-link :disabled="isProcessing" :to="`${resource}`"
                        class="btn" v-else>
                        Cancel
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import ErrorText from '../../components/form/ErrorText.vue'
    import Typeahead from '../../components/form/Typeahead.vue'
    import FileUpload from '../../components/form/FileUpload.vue'
    import Spinner from '../../components/loading/Spinner.vue'
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/container_orders/create`,
            'edit': `/api/container_orders/${to.params.id}/edit`,
            'clone': `/api/container_orders/${to.params.id}/edit?mode=clone`,
            'sales_order': `/api/quotations/${to.params.id}/edit?mode=sales_order`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        computed: {
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
            display_vat(){
                return window.apex.display_vat
            },
            subTotal() {
                return this.form.items.reduce((carry, item) => {
                    return carry + (Number(item.unit_price) * Number(item.quantity) )
                }, 0)
            },
            totalTax() {
                return this.form.items.reduce((carry, item) => {

                    if(item.taxes && item.taxes.length) {
                        const taxes = item.taxes.reduce((c, tax) => {
                            return c + (Number(item.unit_price) * Number(item.quantity)) * tax.rate / 100
                        }, 0)
                        return carry + taxes
                    }
                    else {
                        return 0
                    }
                }, 0)
            },
            totalquantity() {
                return this.form.items.reduce((carry, item) => {
                        return carry + Number(item.quantity)
                }, 0)
            },
            selectedTaxes() {
                const taxes = {}

                this.form.items.forEach((item) => {
                    if(item.taxes && item.taxes.length) {
                        item.taxes.forEach((tax) => {
                            let key = `${tax.name} ${tax.rate}%`
                            if(taxes.hasOwnProperty(key)) {
                                taxes[key] =  taxes[key] + (Number(item.unit_price) * Number(item.quantity)) * tax.rate / 100
                            } else {
                                taxes[key] = (Number(item.unit_price) * Number(item.quantity)) * tax.rate / 100
                            }
                        })
                    }
                })
                return taxes
            },
             DeliveryCondition() {
            
            },
            total() {
                // return this.subTotal + this.totalTax * 1507 - this.form.discount;
                // return  this.subTotal + (Number(this.form.shipping))  - (Number(this.form.discount)) ;
                 //{{first_currency}} only
                //   if(this.form.vat_status == 2){
                //       return  this.subTotal + this.totalTax + (Number(this.form.shipping))  - (Number(this.form.discount)) ;
                //   }
                //   // {{first_currency}} and LBP
                //   else if(this.form.vat_status == 1) {
                      return this.subTotal + this.totalTax  + (Number(this.form.shipping))  - (Number(this.form.discount)) ;
                //   }
                //   //not vat
                //   else{
                //        return this.subTotal + (Number(this.form.shipping))  - (Number(this.form.discount)) ;
                //   }
            }
        },
        components: { ErrorText, Typeahead, Spinner, FileUpload },
        mixins: [ form ],
       
        data () {
            return {
                resource: '/container_orders',
                store: '/api/container_orders',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created sales order!',
                currencyURL: '/api/search/currencies',
                deliveryconditionURL: '/api/search/deliverycondition',
                paymentconditionURL: '/api/search/paymentcondition',
                // productURL: '/api/search/SalesProducts',
                productURL: '/api/search/ContainerProducts',
                clientURL: '/api/search/clients',
                sellerURL: '/api/search/sellers',
                shipperURL: '/api/search/shippers',
                uomURL: '/api/search/uom_po',
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/container_orders/${this.$route.params.id}?_method=PUT`
                this.message = 'You have successfully updated sales order!'
                this.method = 'POST'
                this.title = 'Edit'
            } else if(this.mode === 'clone') {
                this.store = `/api/container_orders`
                this.message = 'You have successfully cloned sales order!'
                this.method = 'POST'
                this.title = 'Clone'
            } else if(this.mode === 'sales_order') {
                this.store = `/api/container_orders`
                this.message = 'You have successfully converted quotation to sales order!'
                this.method = 'POST'
                this.title = 'Convert Quotation to '
             
            }
        },
        beforeRouteEnter(to, from, next) {
            get(initializeUrl(to), to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false

            get(initializeUrl(to), to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            onDocument(e) {
                Vue.set(this.$data.form, 'document', e.target.value)
            },
            removeProduct(item, index) {
                if(this.form.items.length > 1) {
                    this.form.items.splice(index, 1)
                }
            },
            addNewLine() {
                this.form.items.push({
                    'product_id': null,
                    'product': null,
                    'price': 0,
                    'uom_id': 0,
                    'quantity': 1
                })
            },
            onSellertUpdate(e) {
                const seller = e.target.value
                // seller
                Vue.set(this.form, 'seller', seller)
                Vue.set(this.form, 'seller_id', seller.id)
            },
            onShipperUpdate(e) {
                const shipper = e.target.value

                // vendor
                Vue.set(this.form, 'shipper', shipper)
                Vue.set(this.form, 'shipper_id', shipper.id)
                Vue.set(this.form, 'container_size', shipper.container_size)
                // currency
                Vue.set(this.form, 'currency', shipper.currency)
                Vue.set(this.form, 'currency_id', shipper.currency.id)

                //Vat Status
                Vue.set(this.form, 'vat_status', shipper.vat_status)
            },
            
            onClientUpdate(e) {
                const client = e.target.value

                // client
                Vue.set(this.form, 'client', client)
                Vue.set(this.form, 'user_id', client.id)

                // currency
                Vue.set(this.form, 'currency', client.currency)
                Vue.set(this.form, 'currency_id', client.currency.id)
                
                //Vat Status
                Vue.set(this.form, 'vat_status', client.vat_status)
            },
            onUomUpdate(item, index, e) {
                const uom = e.target.value

                // uom
                Vue.set(this.form.items[index], 'uom', uom)
                Vue.set(this.form.items[index], 'uom_id', uom.id)

            
            },
            onProductUpdated(item, index, e) {
                const product = e.target.value

                // product
                Vue.set(this.form.items[index], 'product', product)
                Vue.set(this.form.items[index], 'product_id', product.id)

                // unit price
                // Vue.set(this.form.items[index], 'price', product.price)
                Vue.set(this.form.items[index], 'unit_price', product.price)

                //uom
                // Vue.set(this.form.items[index], 'uom', product.uom)
                Vue.set(this.form.items[index], 'uom', product.uom.unit)
                // Vue.set(this.form.items[index], 'uom', product.uom_id)
                Vue.set(this.form.items[index], 'uom', product.uom)
                Vue.set(this.form.items[index], 'uom_id', product.uom_id)
                Vue.set(this.form.items[index], 'uom_unit', product.uom.unit)
                Vue.set(this.form.items[index], 'uom_code', product.uom.unit)
                

                Vue.set(this.form.items[index], 'volume_box', product.volume_box)
                Vue.set(this.form.items[index], 'ct_box', product.ct_box)
                Vue.set(this.form.items[index], 'weight_box', product.weight_box)
                Vue.set(this.form.items[index], 'current_stock', product.qty_on_hand)
                
                //uom
                Vue.set(this.form.items[index], 'uom', product.uom)
                Vue.set(this.form.items[index], 'uom_id', product.uom_id)
                Vue.set(this.form.items[index], 'uom_unit', product.uom)
                Vue.set(this.form.items[index], 'uom2_id', product.uom_id)
                 // taxes
                Vue.set(this.form.items[index], 'tax_name', product.tax_name)
                Vue.set(this.form.items[index], 'tax_rate', product.tax_rate)
                // taxes
                Vue.set(this.form.items[index], 'taxes', product.taxes)
            },
            onProductCurrencyUpdated(v, index, e) {
                const currency = e.target.value

                // currency
                Vue.set(this.form.items[index], 'currency', currency)
                Vue.set(this.form.items[index], 'currency_id', currency.id)
            },
             
            save() {
                this.submitMultipartForm(this.form, (data) => {
                    this.success()
                    this.$router.push(`${this.resource}/${data.id}`)
                })
            },
            onPaymentCondition(e) {
                
            },
            saveAndNew() {
                this.submitMultipartForm(this.form, (data) => {
                    const id = Math.random().toString(36).substring(7)
                    this.endProcessing()
                    this.success()
                    this.$router.push(`${this.resource}/create?new=${id}`)
                })
            },
            onCurrencyUpdate(e) {
                const currency = e.target.value

                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency', currency)
            },
             onDeliveryUpdate(e){
                 const delivery_conditions = e.target.value
                    Vue.set(this.form, 'delivery_conditions', delivery_conditions)
             },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Shipping Order ${this.title}`)
                this.$bar.finish()
                this.show = true
            },
           onDeliveryConditionUpdated(e) {
                const deliverycondition = e.target.value
                Vue.set(this.form, 'deliverycondition', deliverycondition)
                Vue.set(this.form, 'deliverycondition_id', deliverycondition.id)
            },
            onPaymentConditionUpdated(e) {
                const paymentcondition = e.target.value
                Vue.set(this.form, 'paymentcondition', paymentcondition)
                Vue.set(this.form, 'paymentcondition_id', paymentcondition.id)
            },
        },
        
    }
</script>
