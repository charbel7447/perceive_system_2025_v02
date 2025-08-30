<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Payment Voucher</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Vendor</label>
                            <typeahead :initial="form.vendor" :params="{with: 'bills'}"
                                :url="vendorURL"
                                @input="onvendorUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.vendor_id"></error-text>
                            <input style="display: none;" type="text" v-model="form.vat_status" />
                            <input style="display: none;" type="text" v-model="form.vendor_name" />
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
                            <input style="display: none;" type="text" v-model="form.currency_code" />
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
                    <div class="col col-2">
                        <div class="form-group">
                            <label>
                                Vat %
                            </label>
                            <span class="form-control">{{form.global_vat_percentage}} %</span>
                            <input style="display: none;" type="text" v-model="form.global_vat_percentage" />
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Payment Date</label>
                            <input type="date" class="form-control" v-model="form.date">
                            <error-text :error="error.date"></error-text>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Balance</label>
                            <input type="text" class="form-control" v-model="form.vendor_balance">
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>
                                Payment Reference
                                <small v-if="form.payment_mode == 'cash'">(Optional)</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.reference">
                            <error-text :error="error.reference"></error-text>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>
                                Exchange Rate
                            </label>
                            <input type="text" class="form-control" v-model="form.exchange_rate">
                            <error-text :error="error.exchange_rate"></error-text>
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
                <table class="item-table" v-if="form.vendor_id">
                    <thead>
                        <tr>
                           <th>Accounts Payables / حسابات دائنة</th>
                            <th>Payment Mode</th>
                            <th>Reference / المرجع</th>
                            <th>Currency / العملة</th>
                            <th>Description / الوصف</th>
                            
                            <th>Date / التاريخ</th>
                            
                          <th>Credit / دائن</th>
<th>Credit USD / دائن</th>
<th v-if="form.vat_status == 1 && (display_vat_rate == 1)">
    Credit Vat / ضريبة دائن
</th>
<th v-if="form.vat_status == 1 && (display_vat_rate == 1)">
    Vat Account / حساب الضريبة
</th>

                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(item, index) in form.items">
                             <tr>
                                <td  :class="['width-3x', errors(`items.${index}.item_id`)]">
                                    <typeahead :initial="item.account_receivable" :trim="80" 
                                        @input="onAccountsURL(item, index, $event)"
                                        :url="charAccountsURL"
                                    >
                                    </typeahead>

                                    <input style="display: none;" type="text" class="form-control" v-model="item.account_receivable_id">
                                    <input style="display: none;" type="text" class="form-control" v-model="item.account_receivable_number">
                                    <input style="display: none;" type="text" class="form-control" v-model="item.account_receivable_name">
                                    <error-text :error="error[`items.${index}.account_id`]"></error-text>
                                </td>
                            
                                <td>
                                     
                                    <typeahead :initial="item.payment_options"
                                        :url="paymentOptionURL"
                                        @input="onPayemntOptionUpdate(item, index, $event)"
                                    >
                                    </typeahead>
                                    <input  style="display: none;" type="text" class="form-control" v-model="item.payment_mode">
                                    <error-text :error="error[`items.${index}.payment_option_id`]"></error-text>
                             
                                </td>
                                   <td>
                                    <input type="text" v-model="item.reference" class="form-control">
                                </td>
                                    <td>
                                    <typeahead :initial="item.account_receivable_currency"
                                        :url="currencyURL"
                                        @input="onCurrencyReceivableUpdate(item, index, $event)"
                                    >
                                    </typeahead>
                                    <input style="display: none;" type="text" v-model="item.account_receivable_currency_code" class="form-control" />
                                    
                                </td>
                                <td>
                                    <input type="text" v-model="item.description" class="form-control">
                                </td>
                                <td>
                                    <input type="date" v-model="item.date" class="form-control">
                                </td>
                             
        <td> 
    <input 
        type="number" 
        v-model.number="item.debit" 
        class="form-control"
        @input="
            item.debit_vat = item.account_receivable_debit_vat_id > 0 ?(item.debit * (form.global_vat_percentage / 100)).toFixed(2) : 0;
            item.debit_usd = item.account_receivable_currency_id != 1 
                ? (item.debit / form.exchange_rate) 
                : item.debit;
        "
    >
</td>

                             
                                <td>
                                    <input  type="number" v-model="item.debit_usd" class="form-control"
                                    >
                                </td>
   <td v-if="form.vat_status == 1 && (display_vat_rate == 1)  && item.account_receivable_debit_vat_id > 0">
                                    <input type="number" v-model="item.debit_vat" class="form-control">
                                </td>
                                <td  v-if="form.vat_status == 1 && (display_vat_rate == 1) && item.account_receivable_debit_vat_id > 0">
                                    <span class="form-control">
                                        <b>
                                            {{ item.account_receivable_debit_vat_code }}</b> - 
                                            {{ item.account_receivable_debit_vat_name }}
                                        </span>
                                    <input style="display: none;" type="text" v-model="item.account_receivable_debit_vat_name" class="form-control">
                                    <input style="display: none;" type="text" v-model="item.account_receivable_debit_vat_id" class="form-control">
                                    <input style="display: none;" type="text" v-model="item.account_receivable_debit_vat_code" class="form-control">
                                    <input style="display: none;" type="text" v-model="item.account_receivable_debit_vat_name" class="form-control">
                                </td>
                                <td v-else>
                                    &nbsp;
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
                             <th class="item-empty " colspan="4"></th>
                            <th class="item-empty" style="text-align: right;padding: 0px 5px !important;"colspan="3">
                                <button class="btn btn-primary" @click="addNewLine601">
                                    Add new line / إضافة سطر جديد
                                </button>
                            </th>
                            <th colspan="2"><span class="form-control" style="background: #ddd;font-weight: bold;">Total Credit / الإجمالي</span></th>
                            <th><span class="form-control" style="background: #ddd;font-weight: bold;">{{ totalDebit }}</span></th>
                        </tr>
                    </tfoot>
                </table>
                <hr>
            </div>
            <hr>
            <div class="panel-footer" v-if="form.vendor_id && form.items.length">
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
            'create': `/api/payment_vouchers/create`,
            'edit': `/api/payment_vouchers/${to.params.id}/edit`,
            'clone': `/api/payment_vouchers/${to.params.id}/edit?mode=clone`
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
            global_vat_percentage(){
                return window.apex.global_vat_percentage
            },
            display_vat_rate(){
                return window.apex.display_vat_rate
            },
            totalDebit() {
                return this.form.items.reduce((carry, item) => {
                        return carry + Number(item.debit_usd) + Number(item.debit_vat) 
                }, 0)
            },
        },
        components: { ErrorText, Typeahead, Spinner, FileUpload },
        mixins: [ form ],
        data () {
            return {
                resource: '/payment_vouchers',
                store: '/api/payment_vouchers',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully received Vendor payment!',
                currencyURL: '/api/search/currencies',
                vendorURL: '/api/search/vendors',
                paymentOptionURL: '/api/search/cp_payment_options',
                charAccountsURL:'/api/search/ledger_account_payables',
            }
        },
        created() {
             if(this.mode === 'edit') {
                this.store = `/api/payment_vouchers/${this.$route.params.id}?_method=PUT`
                this.message = 'You have successfully updated Payment Voucher!'
                this.method = 'POST'
                this.title = 'Edit'
            } else if(this.mode === 'clone') {
                this.store = `/api/payment_vouchers`
                this.message = 'You have successfully cloned Payment Voucher!'
                this.method = 'POST'
                this.title = 'Clone'
            }
            console.log(this.mode);
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
            addNewLine601() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0'); // months are 0-based
                const day = String(today.getDate()).padStart(2, '0'); // local day
                const formatted = `${year}-${month}-${day}`;
                this.form.items.push({
                    'debit': 0,
                    'credit': 0,
                    'debit_vat':0,
                    'debit_usd':0,
                    'date': formatted,
                })
            },
            removeLine(item, index) {
                if(this.form.items.length > 1) {
                    this.form.items.splice(index, 1)
                }
            },
            save() {
                this.submitMultipartForm(this.form, (data) => {
                    this.success()
                    this.$router.push(`${this.resource}/${data.id}`)
                })
            },
            saveAndNew() {
                   this.submitMultipartForm(this.form, (data) => {
                    const id = Math.random().toString(36).substring(7)
                    this.endProcessing()
                    this.success()
                    this.$router.push(`${this.resource}/create?new=${id}`)
                })
            },
            onDocument(e) {
                Vue.set(this.$data.form, 'document', e.target.value)
            },
              onPayemntOptionUpdate(item, index, e) {
                const payment_options = e.target.value

                Vue.set(this.form.items[index], 'payment_option_id', payment_options.id)
                Vue.set(this.form.items[index], 'payment_mode', payment_options.name)
                Vue.set(this.form.items[index], 'payment_options', payment_options)
            },
               onCurrencyReceivableUpdate(item, index, e) {
                const currency = e.target.value

                Vue.set(this.form.items[index], 'account_receivable_currency_code', currency.code)
                Vue.set(this.form.items[index], 'account_receivable_currency_id', currency.id)
                Vue.set(this.form.items[index], 'account_receivable_currency', currency)
            },
             onAccountsURL(item, index, e) {
                const account_receivable = e.target.value
                // account_receivable
                Vue.set(this.form.items[index], 'account_receivable', account_receivable)
                Vue.set(this.form.items[index], 'account_receivable_id', account_receivable.id)
                Vue.set(this.form.items[index], 'account_receivable_number', account_receivable.code)
                Vue.set(this.form.items[index], 'account_receivable_name', account_receivable.name_en)

            

                // currency
Vue.set(this.form.items[index], 'account_receivable_currency_code', account_receivable.currency.code)
                
                Vue.set(this.form.items[index], 'account_receivable_currency_id', account_receivable.currency_id)
                Vue.set(this.form.items[index], 'account_receivable_currency', account_receivable.currency)

                //VAT
                Vue.set(this.form.items[index], 'account_receivable_debit_vat_name', account_receivable.vat_account.name_ar)
                Vue.set(this.form.items[index], 'account_receivable_debit_vat_id', account_receivable.vat_account.id)
                Vue.set(this.form.items[index], 'account_receivable_debit_vat_code', account_receivable.vat_account.code)
            },
            onvendorUpdate(e) {
                const vendor = e.target.value

                // vendor
                Vue.set(this.form, 'vendor', vendor)
                Vue.set(this.form, 'vendor_id', vendor.id)
                Vue.set(this.form, 'vendor_name', vendor.name)
                Vue.set(this.form, 'vat_status', vendor.vat_status)
                
                // currency
                Vue.set(this.form, 'currency', vendor.currency)
                Vue.set(this.form, 'currency_id', vendor.currency.id)

                // invoices
                Vue.set(this.form, 'items', vendor.bills)

            },
            onCurrencyUpdate(e) {
                const currency = e.target.value

                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency_code', currency.code)
                Vue.set(this.form, 'currency', currency)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Payment Voucher ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>