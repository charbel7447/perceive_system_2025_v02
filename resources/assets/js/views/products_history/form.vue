<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Invoice</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Client</label>
                            <typeahead :initial="form.client"
                                :url="clientURL"
                                @input="onClientUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.user_id"></error-text>
                            <input style="display: none;" type="text" class="form-control" v-model="form.client_id">
                            <input style="display: none;" type="text" class="form-control" v-model="form.user_id">
                            <input style="display: none;" type="text" class="form-control" v-model="form.price_class">
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
                            <label v-if="( form.vat_status == 2 )  && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Vat Included VAT 
                            </label>
                            <label v-if="( form.vat_status == 1 )  && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Vat Included
                            </label>
                            <label v-if="( form.vat_status == 0 )  && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Vat Excluded
                            </label>
                            <label v-if="( form.vat_status == null )  && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Adjust Client VAT to proceed
                            </label>
                            <label v-if="( form.vat_status == 1 )  && (display_exchange_rate == 1)"  style="display: inherit;">
                           <label style="color:red;display: contents;">Exchange Rate:</label><strong v-if="base_currency == 1">1 {{first_currency}} = {{form.exchangerate}} {{second_currency}} </strong>
                           </label>
                           <label v-if="(display_vat == 1) " style="display: inherit;">
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
                     <div class="col col-3" v-if="( form.vat_status == 1 ) && (display_exchange_rate == 1) ">
                        <div class="form-group">
                            <label>Exchange Rate (1x to {{second_currency}})</label>
                            <input type="text" class="form-control" v-model="form.exchangerate">
                            <error-text :error="error.exchangerate"></error-text>
                        </div>
                    </div>
                     <!-- <div class="col col-3">
                        <div class="form-group">
                            <label>
                                Due Date
                                <small>(Optional)</small>
                            </label>
                            <input type="date" class="form-control" v-model="form.due_date">
                            <error-text :error="error.due_date"></error-text>
                        </div>
                    </div> -->
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
                <div class="col-md-12">
                    <strong class="form-controlz" style="float: left;width: 7%;padding: 2px  0;;">Show Filter</strong><br>
                    <input type="checkbox" :value="1" v-model="showFilter" style=";width: 10px;">
                </div>
            <div class="row col-md-12" v-if="showFilter == 1">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field1</label>
                            <input type="text" class="form-control" v-model="form.field1">
                            <error-text :error="error.field1"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field2</label>
                            <input type="text" class="form-control" v-model="form.field2">
                            <error-text :error="error.field2"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field3</label>
                            <input type="text" class="form-control" v-model="form.field3">
                            <error-text :error="error.field3"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field4</label>
                            <input type="text" class="form-control" v-model="form.field4">
                            <error-text :error="error.field4"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field5</label>
                            <input type="text" class="form-control" v-model="form.field5">
                            <error-text :error="error.field5"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field6</label>
                            <input type="text" class="form-control" v-model="form.field6">
                            <error-text :error="error.field6"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field7</label>
                            <input type="text" class="form-control" v-model="form.field7">
                            <error-text :error="error.field7"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field8</label>
                            <input type="text" class="form-control" v-model="form.field8">
                            <error-text :error="error.field8"></error-text>
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Field9</label>
                            <input type="text" class="form-control" v-model="form.field9">
                            <error-text :error="error.field9"></error-text>
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Field10</label>
                            <input type="text" class="form-control" v-model="form.field10">
                            <error-text :error="error.field10"></error-text>
                        </div>
                    </div>
                </div>
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Description</th>
                            <th>H</th>
                            <th>Unit Price
                                <small>
                                    <span v-if="form.price_class == 1">(Class A)</span>
                                    <span v-if="form.price_class == 2">(Class B)</span>
                                    <span v-if="form.price_class == 3">(Class C)</span>
                                </small>
                            </th>
                            <th v-if="invoices_available_qty == 1">Available Stock</th>
                            <th>Qty</th>
                            <th>Discount 
                                <br>
                                <span>% <input type="checkbox" value="1" v-model="form.discount_per"></span>
                                <span>$ <input type="checkbox" value="1"  v-model="form.discount_usd"></span> 
                            </th>
                            <th>UOM</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                          <!-- '/products/'+`${item.product.id}` -->
                        <!-- :xhref="`/products/${item.product.id}`" target="_blank"  -->
                        <template v-for="(item, index) in form.items">
                            <tr>
                                <td style="vertical-align:middle;" > <a class="link_shortcut" v-if="item.product" @click="myFunction(item, index)"><i class="fa fa-link"></i></a></td>
                                <td v-if="invoices_available_qty == 1" :class="['width-5', errors(`items.${index}.item_id`)]">
                                    <typeahead :initial="item.product" :trim="80" v-focus 
                                        @input="onProductUpdated(item, index, $event)"
                                        :url="productURL"
                                    >
                                    </typeahead>
                                    <input style="display: none;" type="text" class="form-control" v-model="item.item_id">
                                    <error-text :error="error[`items.${index}.item_id`]"></error-text>
                                </td>
                                
                                <td v-if="invoices_available_qty == 0" :class="['width-6', errors(`items.${index}.item_id`)]">
                                    <typeahead :initial="item.product" :trim="80" v-focus 
                                        @input="onProductUpdated(item, index, $event)"
                                        :url="productURL"
                                    >
                                    </typeahead>
                                    <error-text :error="error[`items.${index}.item_id`]"></error-text>
                                </td>
                                <td style="vertical-align:middle;"> <a class="link_shortcut" v-if="item.product" @click="myFunctionH(item, index)"><i class="fa fa-link"></i></a></td>
                                <td :class="['width-1', errors(`items.${index}.price`)]">
                                    <!-- <input type="text" class="form-control" v-model="item.price"> -->
                                    <input v-focus class="form-control" type="text" v-model="item.price" @keyup.enter="addNewLine" />
                                    <error-text :error="error[`items.${index}.price`]"></error-text>
                                </td>
                                <td :class="['width-2']" v-if="invoices_available_qty == 1">
                                   <span class="form-control">{{item.qty_on_hand}}</span>
                                </td>
                                <td :class="['width-1', errors(`items.${index}.qty`)]">
                                    <!-- <input type="text" class="form-control" v-model="item.quantity" > -->
                                    <input type="text" class="form-control" v-model="item.quantity" @keyup.enter="addNewLine">
                                    
                                    <error-text :error="error[`items.${index}.qty`]"></error-text>
                                </td>
                                <td v-if="form.discount_usd == 1" :class="['width-1', errors(`items.${index}.discount_usd`)]" >
                                  <input type="text" class="form-control" v-model="item.discount_usd">
                                    <error-text :error="error[`items.${index}.discount_usd`]"></error-text>
                                </td>
                                <td v-if="form.discount_per == 1" :class="['width-1', errors(`items.${index}.discount_per`)]" >
                                  <input type="text" class="form-control" v-model="item.discount_per">
                                    <error-text :error="error[`items.${index}.discount_per`]"></error-text>
                                </td>
                          
                                <td :class="['width-1', errors(`items.${index}.uom_id`)]" style="display:none;">
                                    <!-- <input type="text" class="form-control" v-model="item.uom_unit">
                                    <input type="text" class="form-control" v-model="item.uom"> -->
                                    <error-text :error="error[`items.${index}.uom_id`]"></error-text>
                                </td>
                                <td :class="['width-1', errors(`items.${index}.uom_id`)]">
                                    <span class="form-control">{{item.uom_code}}</span>
                                     <input style="display:none;" type="text" class="form-control" v-model="item.uom_code">

                                <!-- <input disabled type="text" class="form-control" v-for="x in item.uom" v-model="x.unit"> -->
                                </td>
                                <td class="width-2">
                                    <span class="form-control align-right" v-if="form.discount_usd == 1">
                                        {{Number(item.quantity * Number(item.price) - item.discount_usd) | formatMoney(form.currency, false)}}
                                    </span>
                                    <span class="form-control align-right" v-if="form.discount_per == 1">
                                        {{Number(item.quantity * Number(item.price) - ((item.quantity * Number(item.price)) * item.discount_per/100)) | formatMoney(form.currency, false)}}
                                    </span>
                                </td>
                                <td>
                                    <button class="item-remove" @click="removeProduct(item, index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="item-tax">
                                <td colspan="3" v-if="item.quantity > item.qty_on_hand && (invoices_available_qty == 1  && company_type == 0)">
                                    <span><strong style="color:red;">Error Invoiced quantities more than available stock</strong></span>
                                </td>
                            </tr>
                            
                             <tr class="item-tax"  v-if="item.taxes.length && ( form.vat_status == 1 )" v-for="tax in item.taxes">
                                <td colspan="3" v-if="display_vat == 1">Vat +{{(Number(item.quantity * Number(item.price)* Number(form.vatrate)  ) * tax.rate / 100) | formatMoney(form.currency, false) }}{{second_currency}}</td>
                                <td colspan="3" v-else></td>
                                <td colspan="1">{{tax.name}}</td>
                                <td>{{tax.rate}}%</td>
                                <td>+{{(Number(item.quantity * Number(item.price) ) * tax.rate / 100) | formatMoney(form.currency, false)}}</td>
                            </tr>
                        </template>
                    </tbody>
                    
                    <tfoot>
                        <tr>
                            <td class="item-empty"></td>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewLine">
                                    Add new line
                                </button>
                            </td>
                            <td class="item-footer" colspan="3">Sub Total</td>
                            <td class="item-footer" colspan="2">
                                <span class="item-dark form-control">
                                    {{subTotal | formatMoney(form.currency, false)}}
                                </span>
                            </td>
                        </tr>
                         <tr>
                            <td class="item-empty"></td>
                            <td></td><td></td>
                            <td class="item-footer" colspan="3"><b>Discount</b> (Fixed Amount)</td>
                            <td class="item-footer" colspan="2" style="">
                                    <input type="text" class="form-control" v-model="form.discount">
                             </td>
                        </tr>
                          <tr>
                            <td class="item-empty"></td>
                            <td></td><td></td>
                            <td class="item-footer" colspan="3"><b>Shipping</b> (Fixed Amount)</td>
                            <td class="item-footer" colspan="2" style="">
                                    <input type="text" class="form-control" v-model="form.shipping">
                             </td>
                        </tr>
                        <tr v-if="form.vat_status == 1" v-for="(value, key) in selectedTaxes" class="item-selected-tax">
                            <td class="item-empty"></td>
                            <td class="item-footer" colspan="3" v-if="display_vat == 1">Vat +{{ value * Number(form.vatrate) | formatMoney(form.currency, false) }}{{second_currency}}</td>
                            <td colspan="3" v-else></td>
                            <td class="item-footer" colspan="2">
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
                            <td class="item-empty"></td>
                            <td class="item-footer" colspan="3">
                                <strong>Total Qty:</strong>
                            </td>
                            <td class="item-footer" colspan="3">
                                <strong class="item-dark form-control">
                                    {{totalQty}}
                                </strong>    
                            </td>
                        </tr>
                        <tr>
                            <td class="item-empty"></td>
                            <td class="item-empty"></td>
                            <td class="item-footer" colspan="3">
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
                            <!-- <textarea class="form-control" v-model="form.terms"></textarea> -->
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
    import Spinner from '../../components/loading/Spinner.vue'
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/invoices/create`,
            'edit': `/api/invoices/${to.params.id}/edit`,
            'clone': `/api/invoices/${to.params.id}/edit?mode=clone`,
            'quotation': `/api/quotations/${to.params.id}/edit?mode=invoice`,
            'sales_order': `/api/sales_orders/${to.params.id}/edit?mode=invoice`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }
    const focus = {
       inserted(el) {
       el.focus()
       },
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
            invoices_available_qty(){
                return window.apex.invoices_available_qty
            },
            company_type(){
                return window.apex.company_type
            },
            subTotal() {
                // return this.form.items.reduce((carry, item) => {
                //     return carry + (Number(item.price) * Number(item.quantity))
                // }, 0)

                if(this.form.discount_usd == 1){
                    return this.form.items.reduce((carry, item) => {
                        return carry + (Number(item.price) * Number(item.quantity) - Number(item.discount_usd) )
                    }, 0)
                  }

                  if(this.form.discount_per == 1){
                    return this.form.items.reduce((carry, item) => {
                        return carry + (Number(item.price) * Number(item.quantity) - ((Number(item.price) * Number(item.quantity) * (Number(item.discount_per)/100))))
                    }, 0)
                  }


            },
            totalTax() {
                return this.form.items.reduce((carry, item) => {

                    if(item.taxes && item.taxes.length) {
                        const taxes = item.taxes.reduce((c, tax) => {
                            return c + (Number(item.price) * Number(item.quantity)) * tax.rate / 100
                        }, 0)
                        return carry + taxes
                    } else {
                        return 0
                    }
                }, 0)
            },
            totalQty() {
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
                                taxes[key] =  taxes[key] + (Number(item.price) * Number(item.quantity)) * tax.rate / 100
                            } else {
                                taxes[key] = (Number(item.price) * Number(item.quantity)) * tax.rate / 100
                            }
                        })
                    }
                })
                return taxes
            },
           total() {
                // return this.subTotal + this.totalTax * 1507 - this.form.discount;
                  //return this.subTotal  - this.form.discount - this.form.shipping;
                 //  return  this.subTotal + (Number(this.form.shipping))  - (Number(this.form.discount)) ;
                    //usd only
                  if(this.form.vat_status == 2){
                      return  this.subTotal + this.totalTax + (Number(this.form.shipping))  - (Number(this.form.discount)) ;
                  }
                  // Usd and LBP
                  else if(this.form.vat_status == 1) {
                      return this.subTotal + this.totalTax  + (Number(this.form.shipping))  - (Number(this.form.discount)) ;
                  }
                  //not vat
                  else{
                       return this.subTotal + (Number(this.form.shipping))  - (Number(this.form.discount)) ;
                  }
                //   if(this.form.discount_usd == 1){
                //     return this.form.items.reduce((carry, item) => {
                //         return carry + (Number(item.price) * Number(item.quantity) - Number(item.discount_usd) )
                //     }, 0)
                //   }

                //   if(this.form.discount_per == 1){
                //     return this.form.items.reduce((carry, item) => {
                //         return carry + (Number(item.price) * Number(item.quantity) - ((Number(item.price) * Number(item.quantity) * (Number(item.discount_per)/100))))
                //     }, 0)
                //   }


            }
        },
        components: { ErrorText, Typeahead, Spinner },
        mixins: [ form ],
        data () {
            return {
                showFilter: 0,
                resource: '/invoices',
                store: '/api/invoices',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created invoice!',
                currencyURL: '/api/search/currencies',
                productURL: '/api/search/InvoicesProducts',
                clientURL: '/api/search/clients',
                deliveryconditionURL: '/api/search/deliverycondition',
                paymentconditionURL: '/api/search/paymentcondition',
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/invoices/${this.$route.params.id}`
                this.message = 'You have successfully updated invoice!'
                this.method = 'PUT'
                this.title = 'Edit'
               
            } else if(this.mode === 'clone') {
                this.store = `/api/invoices`
                this.message = 'You have successfully cloned invoice!'
                this.method = 'POST'
                this.title = 'Clone'
               
            } else if(this.mode === 'quotation') {
                this.store = `/api/invoices`
                this.message = 'You have successfully converted quotation to invoice!'
                this.method = 'POST'
                this.title = 'Convert Quotation to '
               
            } else if(this.mode === 'sales_order') {
                this.store = `/api/invoices`
                this.message = 'You have successfully converted sales_order to invoice!'
                this.method = 'POST'
                this.title = 'Convert Sales Order to '
               
            }
            this.getDeliveryCondition()
            this.getPaymentCondition()


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
            myFunction(item, index){		
                window.open('/products/'+ item.item_id, "_blank",`width=1024,height=800`);	
            },
            myFunctionH(item, index){		
                window.open('/'+this.form.client_id + '/products_history/'+ item.item_id, "_blank",`width=1024,height=800`);	
            },
            removeProduct(item, index) {
                if(this.form.items.length > 1) {
                    this.form.items.splice(index, 1)
                }
            },
            addNewLine() {
                this.form.items.push({
                    'item_id': null,
                    'product': null,
                    'price': 0,
                    'uom': 0,
                    'discount_usd': 0,
                    'discount_per': 0,
                    'quantity': 1
                })
            },
            onClientUpdate(e) {
                const client = e.target.value

                // client
                Vue.set(this.form, 'client', client)
                Vue.set(this.form, 'user_id', client.id)
                Vue.set(this.form, 'client_id', client.id)
                // currency
                Vue.set(this.form, 'currency', client.currency)
                Vue.set(this.form, 'currency_id', client.currency.id)
                Vue.set(this.form, 'price_class', client.price_class)
                //Vat Status
                Vue.set(this.form, 'vat_status', client.vat_status)
            },
            onProductUpdated(item, index, e) {
                const product = e.target.value

                // product
                Vue.set(this.form.items[index], 'product', product)
                Vue.set(this.form.items[index], 'item_id', product.id)

                // unit price
                // Vue.set(this.form.items[index], 'price', product.price)
                Vue.set(this.form.items[index], 'qty_on_hand', product.qty_on_hand)
                

                if(this.form.price_class == 1){
                    if(product.class_a_price == 0){
                        Vue.set(this.form.items[index], 'price', product.price)
                    }else{
                        Vue.set(this.form.items[index], 'price', product.class_a_price)
                    }
                }else if(this.form.price_class == 2){
                    if(product.class_b_price == 0){
                        Vue.set(this.form.items[index], 'price', product.price)
                    }else{
                        Vue.set(this.form.items[index], 'price', product.class_b_price)
                    }
                }else if(this.form.price_class == 3){
                    if(product.class_c_price == 0){
                        Vue.set(this.form.items[index], 'price', product.price)
                    }else{
                        Vue.set(this.form.items[index], 'price', product.class_c_price)
                    }
                }else{
                    Vue.set(this.form.items[index], 'price', product.price)
                }

                //uom
                // Vue.set(this.form.items[index], 'uom', product.uom)
                // Vue.set(this.form.items[index], 'uom_id', product.uom_id)
                // Vue.set(this.form.items[index], 'uom_unit', product.uom.unit)

                Vue.set(this.form.items[index], 'uom', product.uom)
                Vue.set(this.form.items[index], 'uom_unit', product.uom.unit)
                Vue.set(this.form.items[index], 'uom_code', product.uom.unit)
                

                // taxes
                Vue.set(this.form.items[index], 'taxes', product.taxes)

                this.addNewLine()
            },
            onProductCurrencyUpdated(v, index, e) {
                const currency = e.target.value

                // currency
                Vue.set(this.form.items[index], 'currency', currency)
                Vue.set(this.form.items[index], 'currency_id', currency.id)
            },
            save() {
                this.submit((data) => {
                    this.success()
                    this.$router.push(`${this.resource}/${data.id}`)
                })
            },
            saveAndNew() {
                this.submit((data) => {
                    const id = Math.random().toString(36).substring(7)
                    this.endProcessing()
                    this.success()
                    this.$router.push(`${this.resource}/create?new=${id}`)
                })
            },
            saveAndNew3() {
               this.submit((data) => {
                   // const id = Math.random().toString(36).substring(7)
                   // this.endProcessing()
                   // this.success()
                   // this.$router.push(`${this.resource}/create?new=${id}`)
                   const id = Math.random().toString(36).substring(7)
                  this.endProcessing()
                  this.success()
                  this.$router.push(`${this.resource}/${data.id}/edit`)
               })
            },
            onCurrencyUpdate(e) {
                const currency = e.target.value

                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency', currency)
            },
            onPaymentCondition(e) {
                const payment_conditions = e.target.value
                Vue.set(this.form, 'payment_conditions', payment_conditions)
            },
            onDeliveryCondition(e) {
                const delivery_conditions = e.target.value
                Vue.set(this.form, 'delivery_conditions', delivery_conditions)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Invoices ${this.title}`)
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
        }
    }
</script>
