<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Purchase Overheads / التكاليف الإضافية للمشتريات</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Vendor / المورد</label>
                            <typeahead :initial="form.vendor"
                                :url="vendorURL"
                                @input="onVendorUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.vendor_id"></error-text>
                            <input style="display: none;" type="text" class="form-control" v-model="form.vat_status">
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Supplier Invoice / فاتورة المورد</label>
                            <typeahead :initial="form.bill"
                            :params="{vendor_id: form.vendor_id}"
                                :url="billURL"
                                @input="onVendorBillUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.bill_id"></error-text>
                            <input style="display: none;" type="text" class="form-control" v-model="form.bill_id">
                            <input style="display: none;" type="text" class="form-control" v-model="form.bill_number">
                            <input style="display: none;" type="date" class="form-control" v-model="form.bill_date">
                            <input style="display: none;" type="text" class="form-control" v-model="form.bill_total">
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Currency / العملة</label>
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
                                Number / الرقم
                                <small>(Auto Generated / يتم التوليد تلقائياً)</small>
                            </label>
                            <span class="form-control">{{form.number}}</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Payment Date / تاريخ الدفع</label>
                            <input type="date" class="form-control" v-model="form.payment_date">
                            <error-text :error="error.payment_date"></error-text>
                        </div>
                    </div>
                    <div class="col col-3" style="display: none;">
                         <div class="form-group">
                             <label>Amount Paid {{first_currency}} / المبلغ المدفوع {{first_currency}}</label>
                             <input type="text" class="form-control" v-model="form.amount_paid">
                             <error-text :error="error.amount_paid"></error-text>
                         </div>
                    </div>
                    <div class="col col-3" style="display: none;">
                         <div class="form-group">
                             <label>Amount Paid {{second_currency}} / المبلغ المدفوع {{second_currency}}</label>
                             <input type="text" class="form-control" v-model="form.amount_paid_lbp">
                             <error-text :error="error.amount_paid_lbp"></error-text>
                         </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Exchange Rate (1x to {{second_currency}}) </label>
                            <input type="text" class="form-control" v-model="form.exchangerate">
                            <error-text :error="error.exchangerate"></error-text>
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
                     <div class="col col-12">
                        <div class="form-group">
                            <label v-if="( form.vat_status == 1 ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note / ملاحظة:</label>  Vat Included / شاملة ضريبة القيمة المضافة
                            </label>
                            <label v-if="( form.vat_status == 0 ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note / ملاحظة:</label>  Vat Excluded / غير شاملة ضريبة القيمة المضافة
                            </label>
                            <label v-if="( form.vat_status == null ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note / ملاحظة:</label>  Adjust Vendor VAT to proceed / يرجى تعديل ضريبة القيمة المضافة للمورد للمتابعة
                            </label>
                            <label v-if="( form.vat_status == 1 ) && (display_exchange_rate == 1)" style="display: contents;">
                               <label style="color:red;display: contents;">Exchange Rate / سعر الصرف:</label>
                               <strong v-if="base_currency == 1">1 {{first_currency}} = {{form.exchangerate}} {{second_currency}}</strong>
                            </label>
                        </div>
                    </div>
                </div>
                <hr>
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Accounts Receivable / حسابات مدينة</th>
                            <th>Currency / العملة</th>
                            <th>Description / الوصف</th>
                            
                            <th>Date / التاريخ</th>
                            <th>Reference / المرجع</th>
                            <th>Debit / مدين</th>
                            <th v-if="form.vat_status == 1 && (display_vat_rate == 1)">
                                Debit Vat / ضريبة مدين
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
                                        @input="onAccountsURL601(item, index, $event)"
                                        :url="charAccountsURL601"
                                    >
                                    </typeahead>

                                    <input style="display: none;" type="text" class="form-control" v-model="item.account_receivable_id">
                                    <input style="display: none;" type="text" class="form-control" v-model="item.account_receivable_number">
                                    <input style="display: none;" type="text" class="form-control" v-model="item.account_receivable_name">
                                    <error-text :error="error[`items.${index}.account_id`]"></error-text>
                                </td>
                                <td>
                                    <typeahead :initial="item.account_receivable_currency"
                                        :url="currencyURL"
                                        @input="onCurrencyReceivableUpdate(item, index, $event)"
                                    >
                                    </typeahead>
                                </td>

                                <td>
                                    <input type="text" v-model="item.description" class="form-control">
                                </td>
                                <td>
                                    <input type="date" v-model="item.date" class="form-control">
                                </td>
                                <td>
                                    <input type="text" v-model="item.reference" class="form-control">
                                </td>
                                <td>
                                    <input type="number" v-model="item.debit" class="form-control"
                                    @input="item.debit_vat = (item.debit * (global_vat_percentage / 100)).toFixed(2)"
                                    >
                                </td>

                                <td v-if="form.vat_status == 1 && (display_vat_rate == 1)">
                                    <input type="number" v-model="item.debit_vat" class="form-control">
                                </td>
                                <td  v-if="form.vat_status == 1 && (display_vat_rate == 1)">
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
                             <th class="item-empty " colspan="3"></th>
                            <th class="item-empty" style="text-align: right;padding: 0px 5px !important;"colspan="3">
                                <button class="btn btn-primary" @click="addNewLine601">
                                    Add new line / إضافة سطر جديد
                                </button>
                            </th>
                            <th><span class="form-control" style="background: #ddd;font-weight: bold;">Total Debit / الإجمالي</span></th>
                            <th><span class="form-control" style="background: #ddd;font-weight: bold;">{{ totalDebit1 }}</span></th>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Accounts Payable / حسابات دائنة</th>
                            <th>Currency / العملة</th>
                            <th>Description / الوصف</th>
                            <th>Date / التاريخ</th>
                            <th>Reference / المرجع</th>
                            <th>Credit / المبلغ</th>
                            <!-- <th v-if="form.vat_status == 1 && (display_vat_rate == 1)">
                                Credit Vat /  قيمة الضريبة
                            </th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(item2, index) in form.items2">
                             <tr>
                                <td  :class="['width-3x', errors(`items2.${index}.item_id`)]">
                                    <typeahead :initial="item2.account_payable" :trim="80" 
                                        @input="onAccountsURL461(item2, index, $event)"
                                        :url="charAccountsURL461"
                                    >
                                    </typeahead>

                                    <input style="display: none;" type="text" class="form-control" v-model="item2.account_payable_id">
                                    <input style="display: none;" type="text" class="form-control" v-model="item2.account_payable_number">
                                    <input style="display: none;" type="text" class="form-control" v-model="item2.account_payable_name">
                                    <error-text :error="error[`items2.${index}.account_id`]"></error-text>
                                </td>
                                <td>
                                    <typeahead :initial="item2.account_payable_currency"
                                        :url="currencyURL"
                                        @input="onCurrencyPayableUpdate(item2, index, $event)"
                                    >
                                    </typeahead>
                                </td>

                                <td>
                                    <input type="text" v-model="item2.description" class="form-control">
                                </td>
                                <td>
                                    <input type="date" v-model="item2.date" class="form-control">
                                </td>
                                  <td>
                                    <input type="text" v-model="item2.reference" class="form-control">
                                </td>
                                <td>
                                    <input type="number" v-model="item2.debit" class="form-control">
                                </td>

                                <!-- <td v-if="form.vat_status == 1 && (display_vat_rate == 1)">
                                    <input type="number" v-model="item2.debit_vat" class="form-control">
                                </td> -->
                                <td>
                                    <button class="item-remove" @click="removeLine(item2, index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="item-empty " colspan="1"></th>
                            <th class="item-empty" style="text-align: right;padding: 0px 5px !important;"colspan="3">
                                <button class="btn btn-primary" @click="addNewLine461">
                                    Add new line / إضافة سطر جديد
                                </button>
                            </th>
                            <th><span class="form-control" style="background: #ddd;font-weight: bold;">Total Credit / الإجمالي</span></th>
                            <th><span class="form-control" style="background: #ddd;font-weight: bold;">{{ totalDebit2 }}</span></th>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <hr>
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Description / الوصف</label>
                            <textarea class="form-control" v-model="form.description"></textarea>
                            <error-text :error="error.description"></error-text>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <spinner v-if="isProcessing"></spinner>
                <div class="btn-group" v-else>
                    <button :disabled="isProcessing" @click="save" class="btn btn-primary">
                        Save / حفظ
                    </button>
                    <button :disabled="isProcessing" v-if="!isEdit"
                        @click="saveAndNew" class="btn btn-secondary">
                        Save and New / حفظ وإنشاء جديد
                    </button>
                    <router-link :disabled="isProcessing" :to="`${resource}/${$route.params.id}`"
                        class="btn" v-if="isEdit">
                        Cancel / إلغاء
                    </router-link>
                    <router-link :disabled="isProcessing" :to="`${resource}`"
                        class="btn" v-else>
                        Cancel / إلغاء
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
    import FileUpload2 from '../../components/form/FileUpload2.vue'
    import Spinner from '../../components/loading/Spinner.vue'
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/expenses/create`
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        computed: {
            totalDebit1() {
                return this.form.items.reduce((carry, item) => {
                        return carry + Number(item.debit) + Number(item.debit_vat) 
                }, 0)
            },
            totalDebit2() {
                return this.form.items2.reduce((carry, item) => {
                        return carry + Number(item.debit) + Number(item.debit_vat) 
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
            display_vat_rate(){
                return window.apex.display_vat_rate
            },
            display_exchange_rate(){
                return window.apex.display_exchange_rate
            },
            display_vat(){
                return window.apex.display_vat
            },
            global_vat_percentage(){
                return window.apex.global_vat_percentage
            },
        },
        components: { ErrorText, Typeahead, Spinner, FileUpload, FileUpload2 },
        mixins: [ form ],
        data () {
            return {
                resource: '/expenses',
                store: '/api/expenses',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created expense!',
                currencyURL: '/api/search/currencies',
                accountURL: '/api/search/ledger_account',
                vendorURL: '/api/search/vendors',
                billURL: '/api/search/vendor_bills_sent_expenses',
                companiesURL: '/api/search/third_parties_extras',
                charAccountsURL601:'/api/search/ledger_account_601',
                charAccountsURL461:'/api/search/ledger_account_461',
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
            addNewLine601() {
                this.form.items.push({
                    'debit': 0,
                    'credit': 0,
                    'debit_vat':0
                })
            },
            addNewLine461() {
                this.form.items2.push({
                    'debit': 0,
                    'credit': 0,
                    'debit_vat':0
                })
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
            
             onVendorBillUpdate(e) {
                const bill = e.target.value

                Vue.set(this.form, 'bill_id', bill.id)
                Vue.set(this.form, 'bill_number', bill.number)
                Vue.set(this.form, 'bill_date', bill.date)
                Vue.set(this.form, 'bill_total', bill.total)
                Vue.set(this.form, 'bill', bill)
            },
            onAccountsURL601(item, index, e) {
                const account_receivable = e.target.value
                // account_receivable
                Vue.set(this.form.items[index], 'account_receivable', account_receivable)
                Vue.set(this.form.items[index], 'account_receivable_id', account_receivable.id)
                Vue.set(this.form.items[index], 'account_receivable_number', account_receivable.code)
                Vue.set(this.form.items[index], 'account_receivable_name', account_receivable.name_en)

            

                // currency
                Vue.set(this.form.items[index], 'account_receivable_currency_id', account_receivable.currency_id)
                Vue.set(this.form.items[index], 'account_receivable_currency', account_receivable.currency)

                //VAT
                Vue.set(this.form.items[index], 'account_receivable_debit_vat_name', account_receivable.vat_account.name_ar)
                Vue.set(this.form.items[index], 'account_receivable_debit_vat_id', account_receivable.vat_account.id)
                Vue.set(this.form.items[index], 'account_receivable_debit_vat_code', account_receivable.vat_account.code)
            },
            onAccountsURL461(item2, index, e) {
                const account_payable = e.target.value
                // account_payable
                Vue.set(this.form.items2[index], 'account_payable', account_payable)
                Vue.set(this.form.items2[index], 'account_payable_id', account_payable.id)
                Vue.set(this.form.items2[index], 'account_payable_number', account_payable.code)
                Vue.set(this.form.items2[index], 'account_payable_name', account_payable.name_en)

             
                

                // currency
                Vue.set(this.form.items2[index], 'account_payable_currency_id', account_payable.currency_id)
                Vue.set(this.form.items2[index], 'account_payable_currency', account_payable.currency)
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
            onVendorUpdate(e) {
                const vendor = e.target.value

                // vendor
                Vue.set(this.form, 'vendor', vendor)
                Vue.set(this.form, 'vendor_id', vendor.id)

                Vue.set(this.form, 'vat_status', vendor.vat_status)
                // currency
                Vue.set(this.form, 'currency', vendor.currency)
                Vue.set(this.form, 'currency_id', vendor.currency.id)
            },

            onCurrencyUpdate(e) {
                const currency = e.target.value

                // currency
                Vue.set(this.form, 'currency', currency)
                Vue.set(this.form, 'currency_id', currency.id)

            },

            onCurrencyReceivableUpdate(item, index, e) {
                const currency = e.target.value

                Vue.set(this.form.items[index], 'account_receivable_currency_id', currency.id)
                Vue.set(this.form.items[index], 'account_receivable_currency', currency)
            },
        

            onCurrencyPayableUpdate(item2, index, e) {
                const currency = e.target.value

                Vue.set(this.form.items2[index],'account_payable_currency_id', currency.id)
                Vue.set(this.form.items2[index],'account_payable_currency', currency)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Purchase Overheads / التكاليف الإضافية للمشتريات  ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>

