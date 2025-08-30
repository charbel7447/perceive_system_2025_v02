<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Quotation</span>
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
                            <error-text :error="error.client_id"></error-text>
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
                            <label v-if="( form.vat_status == 2 ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Vat Included VAT {{first_currency}} Only
                            </label>
                            <label v-if="( form.vat_status == 1 ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Vat Included 
                            </label>
                            <label v-if="( form.vat_status == 0 ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Vat Excluded
                            </label>
                            <label v-if="( form.vat_status == null ) && (display_vat_rate == 1) ">
                               <label style="color:red;display: contents;">Note:</label>  Adjust Client VAT to proceed
                            </label>
                            <label v-if="( form.vat_status == 1 ) && (display_exchange_rate == 1)" style="display: inherit;">
                           <label style="color:red;display: contents;">Exchange Rate:</label><strong v-if="base_currency == 1">1 {{first_currency}} = {{form.exchangerate}} {{second_currency}}</strong>
                           </label>
                           <label v-if="display_vat == 1" style="display: inherit;">
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
                     <div class="col col-3" v-if="( form.vat_status == 1 ) && (display_exchange_rate == 1)">
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
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th>Unit Price
                                <small>
                                    <span v-if="form.price_class == 1">(Class A)</span>
                                    <span v-if="form.price_class == 2">(Class B)</span>
                                    <span v-if="form.price_class == 3">(Class C)</span>
                                </small>
                            </th>
                            <th>Qty</th>
                            <th>UOM</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(item, index) in form.items">
                            <tr>
                                <td :class="['width-7', errors(`items.${index}.product_id`)]">
                                    <typeahead :initial="item.product" :trim="80"
                                        @input="onProductUpdated(item, index, $event)"
                                        :url="productURL"
                                    >
                                    </typeahead>
                                    <error-text :error="error[`items.${index}.product_id`]"></error-text>
                                    <input style="display: none;" type="text" class="form-control" v-model="item.product_name">
                                </td>
                                <td :class="['width-2', errors(`items.${index}.unit_price`)]">
                                    <input type="text" class="form-control" v-model="item.unit_price">
                                    <error-text :error="error[`items.${index}.unit_price`]"></error-text>
                                </td>
                                <td :class="['width-1', errors(`items.${index}.qty`)]">
                                    <input type="text" class="form-control" v-model="item.qty">
                                    <error-text :error="error[`items.${index}.qty`]"></error-text>
                                </td>
                                <td :class="['width-1', errors(`items.${index}.uom_id`)]" style="display:none;">
                                    <input type="text" class="form-control" v-model="item.uom_id">
                                    <error-text :error="error[`items.${index}.uom_id`]"></error-text>
                                </td>
                                <td :class="['width-1', errors(`items.${index}.uom_id`)]">
                                 <typeahead :initial="item.uom" :trim="80"
                                        @input="onUomUpdated(item, index, $event)"
                                        :url="uomURL"
                                    >
                                    </typeahead>
                                    <error-text :error="error[`items.${index}.uom_id`]"></error-text>
                                    <input style="display: none;" type="text" class="form-control" v-model="item.uom_id">
                                    <input style="display: none;" type="text" class="form-control" v-model="item.uom_code">
                                    <input style="display: none;" type="text" class="form-control" v-model="item.uom_unit">
                                </td>
                                <td class="width-2">
                                    <span class="form-control align-right">
                                        {{Number(item.qty * Number(item.unit_price)) | formatMoney(form.currency, false)}}
                                    </span>
                                </td>
                                
                                <td>
                                    <button class="item-remove" @click="removeProduct(item, index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="item-tax" v-if="item.taxes.length && ( form.vat_status == 1 )" v-for="tax in item.taxes">
                                <td colspan="2" v-if="display_vat == 1">Vat +{{(Number(item.qty * Number(item.unit_price)* Number(form.vatrate)  ) * tax.rate / 100) | formatMoney(form.currency, false) }}{{second_currency}}</td>
                                <td colspan="1">{{tax.name}}</td>
                                <td>{{tax.rate}}%</td>
                                <td>+{{(Number(item.qty * Number(item.unit_price)  ) * tax.rate / 100) | formatMoney(form.currency, false)}}</td>
                            </tr>
                         
                        </template>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="item-footer_line"></td>
                        </tr>
                        <tr>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewLine">
                                    Add new line
                                </button>
                            </td>
                            <td class="item-footer" colspan="2">Sub Total</td>
                            <td class="item-footer" colspan="2">
                                <span class="">
                                    {{subTotal | formatMoney(form.currency, false)}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="item-empty" v-if="form.line1_text">
                                <span class="form-controlx">{{ form.line1_text }} </span>
                                <input type="text" class="form-controlx" v-model="form.line1_value">
                            </td>
                            <td></td>
                            <td class="item-footer" colspan="1"><b>Discount</b> (Fixed Amount)</td>
                            <td class="item-footer" colspan="2" style="">
                                    <input type="text" class="form-control" v-model="form.discount">
                             </td>
                        </tr>
                        <tr>
                            <td class="item-empty" v-if="form.line2_text">
                                <span class="form-controlx">{{ form.line2_text }} </span>
                                <input type="text" class="form-controlx" v-model="form.line2_value">
                            </td>
                            <td></td>
                            <td class="item-footer" colspan="1"><b>Shipping</b> (Fixed Amount)</td>
                            <td class="item-footer" colspan="2" style="">
                                    <input type="text" class="form-control" v-model="form.shipping">
                             </td>
                        </tr>
                         <tr v-if="form.vat_status == 1" v-for="(value, key) in selectedTaxes" class="item-selected-tax">
                            <td class="item-footer" colspan="2" v-if="display_vat == 1">Vat +{{ value * Number(form.vatrate) | formatMoney(form.currency, false) }}{{second_currency}}</td>
                            <td class="item-footer" colspan="2">
                                <span>{{key}}</span>
                            </td>
                            <td class="item-footer" colspan="2">
                                <span class="">
                                     {{value | formatMoney(form.currency, true)}}
                                </span>
                            </td>
                        </tr>
                          <tr v-if="form.vat_status == 2" v-for="(value, key) in selectedTaxes" class="item-selected-tax">
                            <td class="item-empty"></td>
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
                            <td class="item-empty" v-if="form.line3_text">
                                <span class="form-controlx">{{ form.line3_text }} </span>
                                <input type="text" class="form-controlx" v-model="form.line3_value">
                            </td>
                            <td></td>
                            <td class="item-footer" colspan="2">
                                <strong>Total Qty:</strong>
                            </td>
                            <td class="item-footer" colspan="2">
                                <strong class="item-dark form-control">
                                    {{totalQty}}
                                </strong>    
                            </td>
                        </tr>
                        <tr>
                            <td class="item-empty" v-if="form.line4_text">
                                <span class="form-controlx">{{ form.line4_text }} </span>
                                <input type="text" class="form-controlx" v-model="form.line4_value">
                            </td>
                            <td></td>
                            <td class="item-footer" colspan="2">
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
                    <div class="col col-12">
                        <div class="form-group">
                            <label>Terms and Conditions</label>
                        </div>
                    </div>
                  <div class="col col-6">
                    <div class="col col-12">
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
                    <div class="col col-12">
                            <div class="form-group">
                                <label>Delivery Time
                                    <small>(Optional)</small>
                                </label>
                            <!-- <input type="date" class="form-control" v-model="form.delivery_date">   -->
                            <textarea class="form-control" v-model="form.delivery_date"></textarea>
                                <error-text :error="error.delivery_date"></error-text>
                            </div>
                        </div>
                        
                  </div>
                  <div class="col col-6">
                    
                     <div class="col col-12">
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
                    <div class="col col-12">
                            <div class="form-group">
                                <label>
                                    Validity Date
                                    <small>(Optional)</small>
                                </label>
                                <input type="date" class="form-control" v-model="form.due_date">
                                <error-text :error="error.due_date"></error-text>
                            </div>
                        </div>

                    <div class="col col-12">
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
            'create': `/api/quotations/create`,
            'edit': `/api/quotations/${to.params.id}/edit`,
            'clone': `/api/quotations/${to.params.id}/edit?mode=clone`,
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
            subTotal() {
                return this.form.items.reduce((carry, item) => {
                    return carry + (Number(item.unit_price) * Number(item.qty))
                }, 0)
            },
            totalQty() {
                return this.form.items.reduce((carry, item) => {
                        return carry + Number(item.qty)
                }, 0)
            },
            totalTax() {
                return this.form.items.reduce((carry, item) => {

                    if(item.taxes && item.taxes.length) {
                        const taxes = item.taxes.reduce((c, tax) => {
                            return c + (Number(item.unit_price) * Number(item.qty)) * tax.rate / 100
                        }, 0)
                        return carry + taxes
                    } else {
                        return 0
                    }
                }, 0)
            },
            selectedTaxes() {
                const taxes = {}

                this.form.items.forEach((item) => {
                    if(item.taxes && item.taxes.length) {
                        item.taxes.forEach((tax) => {
                            let key = `${tax.name} ${tax.rate}%`
                            if(taxes.hasOwnProperty(key)) {
                                taxes[key] =  taxes[key] + (Number(item.unit_price) * Number(item.qty)) * tax.rate / 100
                            } else {
                                taxes[key] = (Number(item.unit_price) * Number(item.qty)) * tax.rate / 100
                            }
                        })
                    }
                })
                return taxes
            },
            total() {
                 // return this.subTotal + this.totalTax * 1507 - this.form.discount;
                  //{{first_currency}} only
                  if(this.form.vat_status == 2){
                      return  this.subTotal + this.totalTax + (Number(this.form.shipping))  - (Number(this.form.discount)) ;
                  }
                  // {{first_currency}} and LBP
                  else if(this.form.vat_status == 1) {
                      return this.subTotal + this.totalTax  + (Number(this.form.shipping))  - (Number(this.form.discount)) ;
                  }
                  //not vat
                  else{
                       return this.subTotal + (Number(this.form.shipping))  - (Number(this.form.discount)) ;
                  }
                  
            }
        },
        components: { ErrorText, Typeahead, Spinner },
        mixins: [ form ],
        data () {
            return {
                resource: '/quotations',
                store: '/api/quotations',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created quotation!',
                currencyURL: '/api/search/currencies',
                productURL: '/api/search/QuotationProducts',
                clientURL: '/api/search/clients',
                uomURL: '/api/search/uom',
                deliveryconditionURL: '/api/search/deliverycondition',
                paymentconditionURL: '/api/search/paymentcondition',
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/quotations/${this.$route.params.id}`
                this.message = 'You have successfully updated quotation!'
                this.method = 'PUT'
                this.title = 'Edit'
              
            } else if(this.mode === 'clone') {
                this.store = `/api/quotations`
                this.message = 'You have successfully cloned quotation!'
                this.method = 'POST'
                this.title = 'Clone'
              
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
            removeProduct(item, index) {
                if(this.form.items.length > 1) {
                    this.form.items.splice(index, 1)
                }
            },
            addNewLine() {
                this.form.items.push({
                    'product_id': null,
                    'product': null,
                    'uom_id': 0,
                    'unit_price': 0,
                    'qty': 1
                })
            },
            onClientUpdate(e) {
                const client = e.target.value

                // client
                Vue.set(this.form, 'client', client)
                Vue.set(this.form, 'client_id', client.id)
                Vue.set(this.form, 'user_id', client.id)
                // currency
                Vue.set(this.form, 'currency', client.currency)
                Vue.set(this.form, 'currency_id', client.currency.id)

                Vue.set(this.form, 'price_class', client.price_class)
                //Vat Status
                Vue.set(this.form, 'vat_status', client.vat_status)
            },
            onUomUpdated(item, index, e) {
                const uom = e.target.value

                // uom
                Vue.set(this.form.items[index], 'uom', uom)
                Vue.set(this.form.items[index], 'uom_id', uom.id)
                Vue.set(this.form.items[index], 'uom_code', uom.unit)
                Vue.set(this.form.items[index], 'uom_unit', uom.unit)
            },
            onProductUpdated(item, index, e) {
                const product = e.target.value

                // product
                Vue.set(this.form.items[index], 'product', product)
                Vue.set(this.form.items[index], 'product_id', product.id)
                Vue.set(this.form.items[index], 'product_name', product.description)
                // unit price
                // Vue.set(this.form.items[index], 'unit_price', product.unit_price)

                if(this.form.price_class == 1){
                    if(product.class_a_price == 0){
                        Vue.set(this.form.items[index], 'unit_price', product.unit_price)
                    }else{
                        Vue.set(this.form.items[index], 'unit_price', product.class_a_price)
                    }
                }else if(this.form.price_class == 2){
                    if(product.class_b_price == 0){
                        Vue.set(this.form.items[index], 'unit_price', product.unit_price)
                    }else{
                        Vue.set(this.form.items[index], 'unit_price', product.class_b_price)
                    }
                }else if(this.form.price_class == 3){
                    if(product.class_c_price == 0){
                        Vue.set(this.form.items[index], 'unit_price', product.unit_price)
                    }else{
                        Vue.set(this.form.items[index], 'unit_price', product.class_c_price)
                    }
                }else{
                    Vue.set(this.form.items[index], 'unit_price', product.unit_price)
                }

                //uom
                Vue.set(this.form.items[index], 'uom', product.uom)
                Vue.set(this.form.items[index], 'uom_id', product.uom_id)
                Vue.set(this.form.items[index], 'uom_code', product.uom.unit)
                Vue.set(this.form.items[index], 'uom_unit', product.uom.unit)
               
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
                this.submit((data) => {
                    this.success()
                    this.$router.push(`${this.resource}/${data.id}`)
                })
                this.endProcessing()
            },
            saveAndNew() {
                this.submit((data) => {
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
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Quotation ${this.title}`)
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
