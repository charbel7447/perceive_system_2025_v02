<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Bill</span>

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
            <div class="panel-body">
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Vendor</label>
                            <typeahead :initial="form.vendor"
                                :url="vendorURL"
                                @input="onVendorUpdate"
                            >
                            </typeahead>
                            <!-- <input type="text" class="form-control" v-model="form.vendor_id"> -->
                            <error-text :error="error.vendor_id"></error-text>
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
                            <label v-if="( form.vat_status == 1 ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Vat Included
                            </label>
                            <label v-if="( form.vat_status == 0 ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Vat Excluded
                            </label>
                            <label v-if="( form.vat_status == null ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note:</label>  Adjust Vendor VAT to proceed
                            </label>
                            <label v-if="( form.vat_status == 1 ) && (display_exchange_rate == 1)" style="display: contents;">
                           <label style="color:red;display: contents;">Exchange Rate:</label><strong v-if="base_currency == 1">1 {{first_currency}} = {{form.exchangerate}} {{second_currency}}</strong>
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
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Due Date</label>
                            <input type="date" class="form-control" v-model="form.due_date">
                            <error-text :error="error.due_date"></error-text>
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
                </div>
                 <hr>
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>
                                Upload Document
                                <small>(Optional: vendor invoice)</small>
                            </label>
                            <file-upload @ready="onDocument"></file-upload>
                            <error-text :error="error.document"></error-text>
                        </div>
                    </div>
                </div>
                <hr>
                <div v-if="!form.vendor_id">
                    <div class="alert alert-info">
                        <span>Please select the vendor first.</span>
                    </div>
                    <hr>
                </div>
                <hr>
                <div class="row"  v-if="form.vendor_id > 0">
                    <div class="col col-12 row" style="margin: 10px 0 0 0;text-align: right;">
                        <div class="form-group col col-6">
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
                        <div class="form-group col col-3">
                            <span class="form-control btn btn-primary" style="text-align: right;">
                                Scan Product Barcode / UPC
                            </span>
                        </div>
                        <div class="form-group col col-3">
                            <input  v-focus @keyup.enter="saveBarcode() "
                             type="text" class="form-control" v-model="form.barcode_scan">
                        </div>
                    </div>
                     
                </div>
                <hr>
                <table class="item-table"  v-if="form.vendor_id > 0">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th>Vendor Reference</th>
                            <th>Vendor Price</th>
                            <th>Qty</th>
                            <th>UOM</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(item, index) in form.items">
                        <tr>
                            <td :class="['width-5', errors(`items.${index}.product_id`)]">
                                <typeahead :initial="item.product" :trim="50"
                                    :params="{vendor_id: form.vendor_id}"
                                    @input="onProductUpdated(item, index, $event)"
                                    :url="productURL"
                                >
                                </typeahead>
                                <input style="display: none;" type="text" class="form-control" v-model="item.product_name">
                                <error-text :error="error[`items.${index}.product_id`]"></error-text>
                            </td>
                            <td class="width-2">
                                <span class="form-control">{{item.vendor_reference}}</span>
                            </td>
                            <td :class="['width-2', errors(`items.${index}.unit_price`)]">
                                <input type="text" class="form-control" v-model="item.unit_price">
                                <error-text :error="error[`items.${index}.unit_price`]"></error-text>
                            </td>
                            <td :class="['width-1', errors(`items.${index}.qty`)]">
                                <input type="text" class="form-control" v-model="item.qty">
                                <error-text :error="error[`items.${index}.qty`]"></error-text>
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
                         <!-- <tr class="item-tax" v-if="form.vat_status == 1">
                                 <td colspan="2" v-if="display_vat == 1">Vat +{{(Number(item.qty * Number(item.unit_price)* Number(form.exchangerate)  ) * item.tax_rate / 100) | formatMoney(form.currency, false) }}{{second_currency}}</td>
                                <td colspan="2" v-else>&nbsp;</td>
                                 <td colspan="2">{{item.tax_name}}</td>
                                <td>{{item.tax_rate}}%</td>
                                <td>+{{(Number(item.qty * Number(item.unit_price)  ) * item.tax_rate/ 100) | formatMoney(form.currency, true)}}</td>
                        </tr> -->
                         <tr class="item-tax"  v-if="item.taxes.length && ( form.vat_status == 1 )" v-for="tax in item.taxes">
                                <td colspan="4" v-if="display_vat == 1">Vat +{{(Number(item.qty * Number(item.unit_price)* Number(form.exchangerate)  ) * tax.rate / 100) }} {{second_currency}}</td>
                                <td colspan="4" v-else></td>
                                <td colspan="1">{{tax.name}}</td>
                                <td>{{tax.rate}}%</td>
                                <td>+{{(Number(item.qty * Number(item.unit_price) ) * tax.rate / 100)}}</td>
                            </tr>
                         </template>
                    </tbody>
                    <tfoot>
                        <tr>
                             
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewLine">
                                    Add new line
                                </button>
                            </td>
                            <td class="item-footer" colspan="1"></td>
                             <td  class="item-footer" colspan="2">Sub Total</td>
                            <td  class="item-footer" colspan="2">
                                <span class="item-dark form-control">
                                    {{subTotal | formatMoney(form.currency, true)}}
                                </span>
                            </td>
                        </tr>
                        <tr class="item-selected-tax" v-if="form.vat_status == 1">
                            <td class="item-empty"  colspan="3"></td>
                            <td class="item-footer" colspan="2">
                                <span>Total Tax</span>
                            </td>
                            <td class="item-footer" colspan="2">
                                <span class="">
                                    {{totalTax | formatMoney(form.currency, true)}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="item-empty" v-if="form.line1_text">
                                <span class="form-controlx">{{ form.line1_text }} </span>
                                <input type="text" class="form-controlxxx" v-model="form.line1_value">
                            </td>
                            <td></td>
                            <td  class="item-footer" colspan="3">
                                <strong>Total Qty:</strong>
                            </td>
                            <td class="item-footer" colspan="1">
                                <strong class="item-dark form-control">
                                    {{totalQty}}
                                </strong>    
                            </td>
                        </tr>
                        <tr>
                            <td class="item-empty" v-if="form.line2_text">
                                <span class="form-controlx">{{ form.line2_text }} </span>
                                <input type="text" class="form-controlxxx" v-model="form.line2_value">
                            </td>
                            <td></td>
                            <td class="item-footer" colspan="1"></td>
                            <td class="item-footer" colspan="1">
                                <strong>Total</strong>
                            </td>
                            <td class="item-footer" colspan="2">
                                <strong class="item-dark form-control">
                                    {{total | formatMoney(form.currency)}}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="item-empty" v-if="form.line3_text">
                                <span class="form-controlx">{{ form.line3_text }} </span>
                                <input type="text" class="form-controlxxx" v-model="form.line3_value">
                            </td>
                        </tr>
                        <tr>
                            <td class="item-empty" v-if="form.line4_text">
                                <span class="form-controlx">{{ form.line4_text }} </span>
                                <input type="text" class="form-controlxxx" v-model="form.line4_value">
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Terms and Conditions</label>
                            <textarea class="form-control" v-model="form.terms"></textarea>
                            <error-text :error="error.terms"></error-text>
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
            'create': `/api/bills/create`,
            'edit': `/api/bills/${to.params.id}/edit`,
            'clone': `/api/bills/${to.params.id}/edit?mode=clone`,
            'purchase_order': `/api/purchase_orders/${to.params.id}/edit?mode=bill`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }
    const focus = {
       inserted(el) {
       el.focus()
       },
    }
    export default {
        directives: { focus },
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
            display_vat_rate(){
                return window.apex.display_vat_rate
            },
            display_exchange_rate(){
                return window.apex.display_exchange_rate
            },
            display_vat(){
                return window.apex.display_vat
            },
            subTotal() {
                return this.form.items.reduce((carry, item) => {
                   if(item.unit_price){
                        return carry + (Number(item.unit_price) * Number(item.qty));
                   }else{
                       return carry + (Number(item.unit_price) * Number(item.qty));
                   }
                }, 0)
            },
            // totalTax() {
            //     return this.form.items.reduce((carry, item) => {
            //             return carry + (Number(item.unit_price) * Number(item.qty)) * item.tax_rate / 100
            //     }, 0)
            // },
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
            // selectedTaxes() {
            //     const taxes = {}

            //     this.form.items.forEach((item) => {
            //         if(item.taxes && item.taxes.length) {
            //             item.taxes.forEach((tax) => {
            //                 let key = `${tax.name} ${tax.rate}%`
            //                 if(taxes.hasOwnProperty(key)) {
            //                     taxes[key] =  taxes[key] + (Number(item.unit_price) * Number(item.qty)) * tax.rate / 100
            //                 } else {
            //                     taxes[key] = (Number(item.unit_price) * Number(item.qty)) * tax.rate / 100
            //                 }
            //             })
            //         }
            //     })
            //     return taxes
            // },
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
            totalQty() {
                return this.form.items.reduce((carry, item) => {
                        return carry + Number(item.qty)
                }, 0)
            },
            // total() {
            //      // return this.subTotal + this.totalTax * 1507 - this.form.discount;
            //       //usd only
            //     if(this.form.vat_status == 1){
            //           return  Number(this.subTotal) + Number(this.totalTax)  ;
            //     }
            //       // Usd and LBP
            //     //   else if(this.form.vat_status === 1) {
            //          //  return this.subTotal + this.TotalTaxes   ;
            //       //  }
            //       //not vat
            //        else{
            //             return this.subTotal  ;
            //         }
                  
            // }
            total() {
                // return this.subTotal + this.totalTax * 1507 - this.form.discount;
                  //return this.subTotal  - this.form.discount - this.form.shipping;
                 //  return  this.subTotal + (Number(this.form.shipping))  - (Number(this.form.discount)) ;
                    //usd only
                  if(this.form.vat_status == 2){
                      return  this.subTotal + this.totalTax;
                  }
                  // Usd and LBP
                  else if(this.form.vat_status == 1) {
                      return this.subTotal + this.totalTax;
                  }
                  //not vat
                  else{
                       return this.subTotal  ;
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
        components: { ErrorText, Typeahead, Spinner, FileUpload },
        mixins: [ form ],
        data () {
            return {
                resource: '/bills',
                store: '/api/bills',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created bill!',
                currencyURL: '/api/search/currencies',
                productURL: '/api/search/products_bill',
                vendorURL: '/api/search/vendors',
                uomURL: '/api/search/uom',
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/bills/${this.$route.params.id}?_method=PUT`
                this.message = 'You have successfully updated bill!'
                this.method = 'POST'
                this.title = 'Edit'
            } else if(this.mode === 'clone') {
                this.store = `/api/bills`
                this.message = 'You have successfully cloned bill!'
                this.method = 'POST'
                this.title = 'Clone'
            } else if(this.mode === 'purchase_order') {
                this.store = `/api/bills`
                this.message = 'You have successfully converted quotation to bill!'
                this.method = 'POST'
                this.title = 'Convert Purchase Order to '
            }

        },
        beforeRouteEnter(to, from, next) {
            get(initializeUrl(to))
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false

            get(initializeUrl(to))
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
                    'unit_price': 0,
                    'qty': 1,
                     'uom_id': 0,
                })
            },
            onVendorUpdate(e) {
                const vendor = e.target.value

                // vendor
                Vue.set(this.form, 'vendor', vendor)
                Vue.set(this.form, 'vendor_id', vendor.id)

                // currency
                Vue.set(this.form, 'currency', vendor.currency)
                Vue.set(this.form, 'currency_id', vendor.currency.id)

                //Vat Status
                Vue.set(this.form, 'vat_status', vendor.vat_status)
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
                Vue.set(this.form.items[index], 'unit_price', product.vendor_price)
                Vue.set(this.form.items[index], 'vendor_reference', product.reference)


                //uom
                Vue.set(this.form.items[index], 'uom', product.uom)
                Vue.set(this.form.items[index], 'uom_id', product.uom_id)
                Vue.set(this.form.items[index], 'uom_code', product.uom.unit)
                Vue.set(this.form.items[index], 'uom_unit', product.uom.unit)
                
                // taxes
                Vue.set(this.form.items[index], 'taxes', product.taxes)
                // taxes
                Vue.set(this.form.items[index], 'tax_name', product.name)
                Vue.set(this.form.items[index], 'tax_rate', product.rate)
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
            saveBarcode() {
            get(`/api/scan_barcode/get_info_purchase?search=${this.form.barcode_scan}&vendor_id=${this.form.vendor_id}`)
            .then(response => {
                const products = response.data.scanned_product || [];

                if (products.length === 0) {
                    console.warn("No products found.");
                    return;
                }

                const scanned_product = products[0];

                // ✅ Check and remove first item if it's empty or price is missing
                const firstItem = this.form.items[0];
                if (
                    this.form.items.length >= 0 &&
                    (!firstItem.product_id || !firstItem.unit_price)
                ) {
                    this.form.items.splice(0, 1);
                }

                // ✅ Push the new scanned product
                this.form.items.push({
                    item_id: scanned_product.id,
                    product_id: scanned_product.id,
                    product: scanned_product, // 👈 This is needed for <typeahead :initial="...">
                    product_name: scanned_product.description, 
                    price: scanned_product.price,
                    unit_price: scanned_product.vendor_price,
                    cost_price: scanned_product.cost_price,
                    qty: 1,
                    vendor_reference: scanned_product.reference,
                    discount_per:0,
                    discount_usd:0,
                    uom: scanned_product.uom,
                    uom_id: scanned_product.uom_id,
                    uom_code: scanned_product.uom.unit,
                    uom_unit: scanned_product.uom.unit,
                    location: scanned_product.location,
                    taxes: scanned_product.taxes
                });

                // this.save(); // Optional
                this.form.barcode_scan = '';
                this.gross_weight = '';
                this.net_weight = '';
            })
            .catch(error => {
                console.error("Barcode scan failed:", error);
            });
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
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Bill ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
