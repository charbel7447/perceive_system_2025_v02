<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Journal</span>
            </div>
            <div class="panel-body">
                <div class="row">
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
                            <label>
                                Document Type
                            </label>
                            <select v-model="form.document_type" id="documentType" class="form-control">
                                <option :value="0" disabled>Select document type</option>
                                <option :value="1">Sales Invoice</option>
                                <option :value="2">Purchase Invoice (Vendor Bill)</option>
                                <option :value="3">Purchase Order</option>
                                <option :value="4">Sales Order</option>
                                <option :value="5">Manual Journal Entry (No linked doc)</option>
                            </select>
                        </div>
                    </div> 
                    <div class="col col-3" v-if="form.document_type == '1'">
                        <div class="form-group">
                            <label>Sent Invoices</label>
                            <typeahead :initial="form.invoices"
                                :url="invoicesURL"
                                @input="onInvoiceUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.invoice_id"></error-text>
                        </div>
                    </div>
                    <div class="col col-3" v-if="form.document_type == '2'">
                        <div class="form-group">
                            <label>Vendor Bills</label>
                            <typeahead :initial="form.bills"
                                :url="vendorBillURL"
                                @input="onVendorBillUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.bill_id"></error-text>
                        </div>
                    </div>
                    <input style="display: none;" type="text" class="form-control" v-model="form.document_id">
                    <input style="display: none;" type="text" class="form-control" v-model="form.document_number">
                    <input style="display: none;" type="date" class="form-control" v-model="form.document_date">
                    <input style="display: none;" type="text" class="form-control" v-model="form.document_total">
                    <input style="display: none;" type="text" class="form-control" v-model="form.document_currency_id">
                    <input style="display: none;" type="text" class="form-control" v-model="form.currency_name">

                    <div class="col col-3" v-if="form.document_type == '3'">
                        <div class="form-group">
                            <label>Purchase Orders Sent</label>
                            <typeahead :initial="form.purchase_orders"
                                :url="purchaseOrderURL"
                                @input="onPurchaseOrderUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.purchase_order_id"></error-text>
                        </div>
                    </div>
                    <div class="col col-3" v-if="form.document_type == '4'">
                        <div class="form-group">
                            <label>Sales Orders Sent</label>
                            <typeahead :initial="form.sales_orders"
                                :url="salesOrderURL"
                                @input="onSalesOrderUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.sales_order_id"></error-text>
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
                     <div class="col col-3" v-if="(display_exchange_rate == 1) ">
                        <div class="form-group">
                            <label>Exchange Rate (1{{first_currency}} to {{second_currency}})</label>
                            <input type="text" class="form-control" v-model="form.exchange_rate">
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
                            <input type="text" class="form-control" v-model="form.vat_rate">
                            <error-text :error="error.vatrate"></error-text>
                        </div>
                    </div>
                      <div class="col col-6">
                        <div class="form-group">
                            <label>
                                Upload Document / رفع المستند
                                <small>(payment receipt / إيصال الدفع)</small>
                            </label>
                            <file-upload @ready="onDocument"></file-upload>
                            <error-text :error="error.document"></error-text>
                        </div>
                    </div>
                    <div class="col col-6" v-if="form.document">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <a class="btn btn-primary" target="_blank"  :href="'/uploads/' + form.document" >
                                <i style="font-size: 17px;color: #000;" class="fa  fa-paperclip"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <hr>
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Ledger Account</th>
                            <th>Description</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                          <!-- '/products/'+`${item.product.id}` -->
                        <!-- :xhref="`/products/${item.product.id}`" target="_blank"  -->
                        <template v-for="(item, index) in form.items">
                             <tr>
                                <td  :class="['width-5', errors(`items.${index}.item_id`)]">
                                    <typeahead :initial="item.account" :trim="80" v-focus 
                                        @input="onAccountUpdated(item, index, $event)"
                                        :url="accountURL"
                                    >
                                    </typeahead>
                                    <input style="display: none;" type="text" class="form-control" v-model="item.account_id">
                                    <input style="display: none;" type="text" class="form-control" v-model="item.account_code">
                                    <input style="display: none;" type="text" class="form-control" v-model="item.account_name_en">
                                    <input style="display: none;" type="text" class="form-control" v-model="item.account_name_ar">
                                
                                    <error-text :error="error[`items.${index}.account_id`]"></error-text>
                                </td>
                                <td>
                                    <input type="text" v-model="item.description" class="form-control">
                                </td>
                                <td>
                                    <input v-if="item.credit > 0" disabled type="number" v-model="item.debit" class="form-control">
                                    <input v-else type="number" v-model="item.debit" class="form-control">
                                </td>
                                <td>
                                    <input v-if="item.debit > 0" disabled type="number" v-model="item.credit" class="form-control">
                                    <input v-else type="number" v-model="item.credit" class="form-control">
                                </td>
                                
                                <td>
                                    <button class="item-remove" @click="removeLine(item, index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="item-empty" colspan="1"></th>
                            <th><span class="form-control" style="background: #ddd;font-weight: bold;">Total</span></th>
                            <th><span class="form-control" style="background: #ddd;font-weight: bold;">{{ totalDebit }}</span></th>
                            <th><span class="form-control" style="background: #ddd;font-weight: bold;">{{ totalCredit }}</span></th>
                        </tr>
                        <tr>
                            <td class="item-empty" colspan="3"></td>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewLine">
                                    Add new line
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <div class="row">
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
    import FileUpload from '../../components/form/FileUpload.vue'
    import Spinner from '../../components/loading/Spinner.vue'
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/journal_vouchers/create`,
            'edit': `/api/journal_vouchers/${to.params.id}/edit`,
            'clone': `/api/journal_vouchers/${to.params.id}/edit?mode=clone`
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
            totalDebit() {
                return this.form.items.reduce((carry, item) => {
                        return carry + Number(item.debit)
                }, 0)
            },
            totalCredit() {
                return this.form.items.reduce((carry, item) => {
                        return carry + Number(item.credit)
                }, 0)
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
            display_vat(){
                return window.apex.display_vat
            },
            journal_vouchers_available_qty(){
                return window.apex.journal_vouchers_available_qty
            },
            company_type(){
                return window.apex.company_type
            },
        },
        components: { ErrorText, Typeahead, Spinner, FileUpload },
        mixins: [ form ],
        data () {
            return {
                showFilter: 0,
                resource: '/journal_vouchers',
                store: '/api/journal_vouchers',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully Created Journal Voucher!',
                currencyURL: '/api/search/currencies',
                accountURL: '/api/search/ledger_account',
                invoicesURL: '/api/search/invoices_sent',
                vendorBillURL: '/api/search/vendor_bills_sent',
                purchaseOrderURL: '/api/search/purchase_orders_sent',
                salesOrderURL: '/api/search/sales_orders_sent',
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
            removeLine(item, index) {
                if(this.form.items.length > 1) {
                    this.form.items.splice(index, 1)
                }
            },
            addNewLine() {
                this.form.items.push({
                    'debit': 0,
                    'credit': 0
                })
            },
            onDocument(e) {
                Vue.set(this.$data.form, 'document', e.target.value)
            },
            onCurrencyUpdate(e) {
                const currency = e.target.value

                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency_name', currency.code)
                Vue.set(this.form, 'currency', currency)
            },
            onAccountUpdated(item, index, e) {
                const account = e.target.value
                // account
                Vue.set(this.form.items[index], 'account', account)
                Vue.set(this.form.items[index], 'account_id', account.id)
                Vue.set(this.form.items[index], 'account_code', account.code)
                Vue.set(this.form.items[index], 'account_name_ar', account.name_ar)
                Vue.set(this.form.items[index], 'account_name_en', account.name_en)
            },
            onInvoiceUpdate(e) {
                const invoices = e.target.value

                Vue.set(this.form, 'document_id', invoices.id)
                Vue.set(this.form, 'document_number', invoices.number)
                Vue.set(this.form, 'document_date', invoices.date)
                Vue.set(this.form, 'document_total', invoices.total)
                Vue.set(this.form, 'document_currency_id', invoices.currency_id)
                Vue.set(this.form, 'invoices', invoices)
            },
            onVendorBillUpdate(e) {
                const bills = e.target.value

                Vue.set(this.form, 'document_id', bills.id)
                Vue.set(this.form, 'document_number', bills.number)
                Vue.set(this.form, 'document_date', bills.date)
                Vue.set(this.form, 'document_total', bills.total)
                Vue.set(this.form, 'bills', bills)
            },
            onPurchaseOrderUpdate(e) {
                const purchase_orders = e.target.value

                Vue.set(this.form, 'document_id', purchase_orders.id)
                Vue.set(this.form, 'document_number', purchase_orders.number)
                Vue.set(this.form, 'document_date', purchase_orders.date)
                Vue.set(this.form, 'document_total', purchase_orders.total)
                Vue.set(this.form, 'purchase_orders', purchase_orders)
            },
            onSalesOrderUpdate(e) {
                const sales_orders = e.target.value

                Vue.set(this.form, 'document_id', sales_orders.id)
                Vue.set(this.form, 'document_number', sales_orders.number)
                Vue.set(this.form, 'document_date', sales_orders.date)
                Vue.set(this.form, 'document_total', sales_orders.total)
                Vue.set(this.form, 'sales_orders', sales_orders)
            },
            save() {
                // this.submit((data) => {
                //     this.success()
                //     this.$router.push(`${this.resource}/${data.id}`)
                // })
                this.submitMultipartForm(this.form, (data) => {
                    this.success()
                    this.$router.push(`${this.resource}/${data.id}`)
                })

            },
            saveAndNew() {
                // this.submit((data) => {
                //     const id = Math.random().toString(36).substring(7)
                //     this.endProcessing()
                //     this.success()
                //     this.$router.push(`${this.resource}/create?new=${id}`)
                // })
                  this.submitMultipartForm(this.form, (data) => {
                    const id = Math.random().toString(36).substring(7)
                    this.endProcessing()
                    this.success()
                    this.$router.push(`${this.resource}/create?new=${id}`)
                })
            },
            
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Journal Vouchers ${this.title}`)
                this.$bar.finish()
                this.show = true
            },
        }
    }
</script>
