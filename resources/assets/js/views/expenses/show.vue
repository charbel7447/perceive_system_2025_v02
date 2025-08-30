<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title"> Purchase Overheads / التكاليف الإضافية للمشتريات  {{model.number}}</span>
                <div>
                    <div class="btn-group" v-if="model.posted == 1">
                        <router-link style="background:green;color: #fff;" :to="`/journal_vouchers/${model.journal_id}/`"
                          class="btn" title="Journal Document">
                            POSTED &nbsp;<i class="fa fa-arrow-right"></i>
                        </router-link>
                    </div>
                    <div class="btn-group" v-if="model.journal_id > 0 && model.posted == 0">
                        <router-link style="background:green;color: #fff;" :to="`/journal_vouchers/${model.journal_id}/`"
                          class="btn" title="Journal Document">
                            JV Created &nbsp;<i class="fa fa-arrow-right"></i>
                        </router-link>
                    </div>
                    <router-link :to="`/expenses`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <div class="btn-group">
                        <a :href="`/docs/expenses/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a :href="`/docs/expenses/${model.id}?mode=download`" target="_blank" class="btn" title="Download PDF">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                       <a @click.stop="deleteModel" class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Vendor / المورد</label>
                           {{model.vendor.person}} -  {{model.vendor.company}}
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Supplier Invoice / فاتورة المورد</label>
                            <span class="form-control">
                                {{model.bill_number}} - {{model.bill_date}} - {{model.bill_total}}
                            </span>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Currency / العملة</label>
                            {{model.currency.code}}
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>
                                Number / الرقم
                                <small>(Auto Generated / يتم التوليد تلقائياً)</small>
                            </label>
                            <span class="form-control">{{model.number}}</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Payment Date / تاريخ الدفع</label>
                            <input disabled type="date" class="form-control" v-model="model.payment_date">
                        </div>
                    </div>
                    <div class="col col-3" style="display: none;">
                         <div class="form-group">
                             <label>Amount Paid {{first_currency}} / المبلغ المدفوع {{first_currency}}</label>
                             <input disabled type="text" class="form-control" v-model="model.amount_paid">
                         </div>
                    </div>
                    <div class="col col-3" style="display: none;">
                         <div class="form-group">
                             <label>Amount Paid {{second_currency}} / المبلغ المدفوع {{second_currency}}</label>
                             <input disabled type="text" class="form-control" v-model="model.amount_paid_lbp">
                         </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Exchange Rate (1x to {{second_currency}}) </label>
                            <input disabled type="text" class="form-control" v-model="model.exchangerate">
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="form-group">
                            <label>
                                Upload Document / رفع المستند
                                <small>(payment receipt / إيصال الدفع)</small>
                            </label>
                               <a v-if="model.document" target="_blank" class="btn" title="Uploaded Doc" :href="'/uploads/' + model.document" >
                            <i class="fa  fa-paperclip"></i>
                        </a>
                        </div>
                    </div>
                     <div class="col col-12">
                        <div class="form-group">
                            <label v-if="( model.vat_status == 1 ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note / ملاحظة:</label>  Vat Included / شاملة ضريبة القيمة المضافة
                            </label>
                            <label v-if="( model.vat_status == 0 ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note / ملاحظة:</label>  Vat Excluded / غير شاملة ضريبة القيمة المضافة
                            </label>
                            <label v-if="( model.vat_status == null ) && (display_vat_rate == 1)">
                               <label style="color:red;display: contents;">Note / ملاحظة:</label>  Adjust Vendor VAT to proceed / يرجى تعديل ضريبة القيمة المضافة للمورد للمتابعة
                            </label>
                            <label v-if="( model.vat_status == 1 ) && (display_exchange_rate == 1)" style="display: contents;">
                               <label style="color:red;display: contents;">Exchange Rate / سعر الصرف:</label>
                               <strong v-if="base_currency == 1">1 {{first_currency}} = {{model.exchangerate}} {{second_currency}}</strong>
                            </label>
                        </div>
                    </div>
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
                </div>
                <hr>
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Accounts Receivable / حسابات مدينة</th>
                            <th>Description / الوصف</th>
                            
                            <th>Date / التاريخ</th>
                            <th>Reference / المرجع</th>
                            <th>Debit / مدين</th>
                            <th v-if="model.vat_status == 1 && (display_vat_rate == 1)">
                                Debit Vat / ضريبة مدين
                            </th>
                            <th v-if="model.vat_status == 1 && (display_vat_rate == 1)">
                                Vat Account / حساب الضريبة
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(item, index) in model.items">
                             <tr>
                                <td>
                                     <span class="form-control">
                                        {{ item.account_receivable_number}} - {{item.account_receivable_name}}
                                     </span>
                                </td>
                                <td>
                                    <input disabled type="text" v-model="item.description" class="form-control">
                                </td>
                                <td>
                                    <input disabled type="date" v-model="item.date" class="form-control">
                                </td>
                                <td>
                                    <input disabled type="text" v-model="item.reference" class="form-control">
                                </td>
                                <td>
                                    <input disabled type="number" v-model="item.debit" class="form-control">
                                </td>

                                <td v-if="model.vat_status == 1 && (display_vat_rate == 1)">
                                    <input disabled type="number" v-model="item.debit_vat" class="form-control">
                                </td>
                                <td  v-if="model.vat_status == 1 && (display_vat_rate == 1)">
                                    <input disabled type="text" v-model="item.account_receivable_debit_vat_name" class="form-control">
                                    <input disabled style="display: none;" type="text" v-model="item.account_receivable_debit_vat_id" class="form-control">
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="item-empty" colspan="3"></th>
                            <th><span class="form-control" style="background: #ddd;font-weight: bold;">Total / الإجمالي</span></th>
                            <th><span class="form-control" style="background: #ddd;font-weight: bold;">{{ totalDebit1 }}</span></th>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Accounts Payable / حسابات دائنة</th>
                            <th>Description / الوصف</th>
                            <th>Date / التاريخ</th>
                            <th>Reference / المرجع</th>
                            <th>Debit / مدين</th>
                            <th v-if="model.vat_status == 1 && (display_vat_rate == 1)">
                                Debit Vat / ضريبة مدين
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(item2, index) in model.items2">
                             <tr>
                                <td>
                                    <span class="form-control">
                                         {{ item2.account_payable_number}} - {{item2.account_payable_name}}
                                    </span>
                                </td>
                                <td>
                                    <input disabled type="text" v-model="item2.description" class="form-control">
                                </td>
                                <td>
                                    <input disabled type="date" v-model="item2.date" class="form-control">
                                </td>
                                  <td>
                                    <input disabled type="text" v-model="item2.reference" class="form-control">
                                </td>
                                <td>
                                    <input disabled type="number" v-model="item2.debit" class="form-control">
                                </td>

                                <td v-if="model.vat_status == 1 && (display_vat_rate == 1)">
                                    <input disabled type="number" v-model="item2.debit_vat" class="form-control">
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="item-empty" colspan="3"></th>
                            <th><span class="form-control" style="background: #ddd;font-weight: bold;">Total / الإجمالي</span></th>
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
                            <textarea class="form-control" v-model="model.description"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, post, byMethod  } from '../../lib/api'
    import { Dropdown } from '../../components/dropdown'
    import Status from '../../components/status/ClientPayment.vue'
    import Posted from '../../components/status/Posted.vue'
    export default {
        computed: {
            totalDebit1() {
                return this.model.items.reduce((carry, item) => {
                        return carry + Number(item.debit) + Number(item.debit_vat) 
                }, 0)
            },
            totalDebit2() {
                return this.model.items2.reduce((carry, item2) => {
                        return carry + Number(item2.debit) + Number(item2.debit_vat) 
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
        },
        components: { Status, Dropdown, Posted, byMethod },
        data() {
            return {
                show: false,
                model: {
                    quotation: {},
                    currency: {},
                    vendor: {}
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/expenses/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/expenses/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            deleteModel() {
                const r = confirm("Are you sure!")
                if(r != true) { return }
                this.$bar.start()
                byMethod('delete', `/api/expenses/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/expenses')
                            this.$message.success(`You have successfully deleted Purchase Overheads / التكاليف الإضافية للمشتريات!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
              setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Purchase Overheads / التكاليف الإضافية للمشتريات - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
