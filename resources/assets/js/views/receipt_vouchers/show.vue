<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Receipt Voucher {{model.number}}</span>
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
                    <button class="btn btn-primary" v-if="model.status_id == 1 || model.balance_amount > 0"
                        @click="showModal = true">Apply to Invoices {{ model.balance_amount  }}</button>
                    <router-link :to="`/receipt_vouchers`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/receipt_vouchers/${model.id}/clone`" class="btn"
                        title="Clone">
                        <i class="fa fa-files-o"></i>
                    </router-link>
                    <div class="btn-group">
                        <a :href="`/docs/receipt_vouchers/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a :href="`/docs/receipt_vouchers/${model.id}?mode=download`" target="_blank"
                            class="btn" title="Download PDF">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                    <!-- <router-link v-if="model.status_id == 1" :to="`/receipt_vouchers/${model.id}/edit`"
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link> -->
                    <dropdown title="More" ref="more">
                        <li>
                            <a @click.stop="deleteModel">
                                Delete
                            </a>
                        </li>
                    </dropdown>
                </div>
            </div>
            <div class="panel-body">
                <div class="document_full">
                    <div class="document-heading">
                        <div class="row">
                            <div class="col col-4">
                                <p><strong>Payment From:</strong></p>
                                <router-link :to="`/clients/${model.client_id}`">
                                    <span>{{model.client.person}}</span><br>
                                    <span>{{model.client.company}}</span><br>
                                    <pre>{{model.client.billing_address}}</pre>
                                </router-link>
                                <br>
                                <label style="display: contents;">
                                <strong>Vat Status: &nbsp;&nbsp;</strong>
                                    <span >
                                        <a style="color: red;" ><b>&nbsp;{{ model.vat_status }}</b> </a>
                                    </span>
                                </label>
                                <br>
                                <label style="display: contents;">
                                <strong>Client Balance: &nbsp;&nbsp;</strong>
                                    <span >
                                        <a style="color: red;" ><b>&nbsp;{{ model.client_balance }}</b> </a>
                                    </span>
                                </label>
                                <br>
                                <label style="display: contents;">
                                <strong>Document Balance Amount: &nbsp;&nbsp;</strong>
                                    <span >
                                        <a style="color: red;" ><b>&nbsp;{{ model.balance_amount }}</b> </a>
                                    </span>
                                </label>
                                <br>
                                <label style="display: contents;">
                                <strong>Exchange Rate: &nbsp;&nbsp;</strong>
                                    <span >
                                        <a style="color: red;" ><b>&nbsp;{{ model.exchange_rate }}</b> </a>
                                    </span>
                                </label>
                                <br>
                                <label style="display: contents;">
                                <strong>Vat %: &nbsp;&nbsp;</strong>
                                    <span >
                                        <a style="color: red;" ><b>&nbsp;{{ model.global_vat_percentage }}</b> </a>
                                    </span>
                                </label>
                                <br>
                            </div>
                            <div class="col col-4">
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
                                            <td>Payment Date:</td>
                                            <td>{{model.date}}</td>
                                        </tr>
                                        <tr v-if="model.reference">
                                            <td>Payment Reference:</td>
                                            <td>{{model.reference}}</td>
                                        </tr>
                                        <tr>
                                            <td>Currency:</td>
                                            <td>{{model.currency.code}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total:</td>
                                            <td>{{model.total_debit_usd | formatMoney(model.currency, false)}}</td>
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
                                    <th>Accounts Receivable / حسابات مدينة</th>
                                    <th>Payment Mode</th>
                                    <th>Reference / المرجع</th>
                                    <th>Currency / العملة</th>
                                    <th>Description / الوصف</th>
                                    
                                    <th>Date / التاريخ</th>
                                    
                                    <th>Debit / مدين</th>
                                    <th>Debit USD / مدين</th>
                                    <th v-if="model.vat_status == 1 && (display_vat_rate == 1)">
                                        Debit Vat / ضريبة مدين
                                    </th>
                                    <th v-if="model.vat_status == 1 && (display_vat_rate == 1)">
                                        Vat Account / حساب الضريبة
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in model.items">
                                    <td>
                                    <span type="text" class="form-control">
                                        {{ item.account_receivable_number }} - {{ item.account_receivable_name }}
                                    </span> 
                                </td>
                                <td>
                                     <span type="text" class="form-control">
                                        {{ item.payment_mode }}
                                    </span> 
                                </td>
                                   <td>
                                    <span type="text" class="form-control">
                                        {{ item.reference }}
                                    </span> 
                                </td>
                                    <td>
                                    <span type="text" class="form-control">
                                        {{ item.account_receivable_currency_code }}
                                    </span> 
                                </td>
                                <td>
                                    <span type="text" class="form-control">
                                        {{ item.description }}
                                    </span> 
                                </td>
                                <td>
                                    <input disabled type="date" v-model="item.date" class="form-control">
                                </td>
                                  <td>
                                    <span type="text" class="form-control">
                                        {{ item.debit }}
                                    </span> 
                                </td>
                                  <td>
                                    <span type="text" class="form-control">
                                        {{ item.debit_usd }}
                                    </span> 
                                </td>
                                <td>
                                    <span type="text" class="form-control">
                                        {{ item.debit_vat }}
                                    </span> 
                                </td>
     
                                <td  v-if="model.vat_status == 1 && (display_vat_rate == 1) && item.account_receivable_debit_vat_id > 0">
                                    <span class="form-control">
                                        <b>
                                            {{ item.account_receivable_debit_vat_code }}</b> - 
                                            {{ item.account_receivable_debit_vat_name }}
                                        </span>
                                </td>
                                <td v-else>
                                    &nbsp;
                                </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"></td>
                                    <td colspan="2">
                                        <strong>Amount Received</strong>
                                    </td>
                                    <td colspan="2" class="align-right">
                                        <strong>
                                            {{model.total_debit_usd | formatMoney(model.currency)}}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
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
        <mini-panel class="product_sales_order" :resource="ReceiotVoucherInvoicesURL"
            :heading="ReceiotVoucherInvoicesColumns">
            <div slot="title">
                Applied Invoices
            </div>
            <router-link :to="`/invoices/create?client_id=${model.client_id}`"
                class="btn btn-secondary btn-sm" slot="create">Create</router-link>
            <tr slot-scope="props" @click="$router.push(`/invoices/${props.item.invoice_id}`)">
                    <td>{{ props.item.number }}</td>
                    <td>{{ props.item.date }}</td>
                    <td>{{ props.item.total || '-' }}</td>
                    <td>{{ props.item.runningBalance || '-' }}</td>
                    <td>{{ (props.item.amount_applied )|| '-' }}</td>
                    <td>{{ props.item.amount_applied_usd | formatMoney(props.item.currency)}}</td>
                    <td><invoice :id="props.item.status_id" /></td>
                </tr>
        </mini-panel>
        <apply-receipt-voucher v-if="showModal" @close="showModal = false"></apply-receipt-voucher>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, post, byMethod  } from '../../lib/api'
    import { Dropdown } from '../../components/dropdown'
    import Status from '../../components/status/ReceiptVoucher.vue'
    import Posted from '../../components/status/Posted.vue'
    import MiniPanel from '../../components/search/MiniPanel.vue'
    import Invoice from '../../components/status/Invoice.vue'
    import ApplyReceiptVoucher from '../../components/modals/ApplyReceiptVoucher.vue'
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
                        return carry + Number(item.debit) + Number(item.debit_vat) 
                }, 0)
            },
        },
       components: { Status, Dropdown, Posted, byMethod, ApplyReceiptVoucher, MiniPanel, Invoice },
        data() {
            return {
                ReceiotVoucherInvoicesURL: `/api/mini/receipt_vouchers/invoices/${this.$route.params.id}`,
                ReceiotVoucherInvoicesColumns: ['Number', 'Date', 'Invoice Total','runningBalance','Amount Applied', 'Amount Applied USD', 'Status'],
                show: false,
                showModal: false,
                model: {
                    items: [],
                    currency: {},
                    client: {}
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/receipt_vouchers/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/receipt_vouchers/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Receipt Voucher - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            },
        deleteModel() {
                this.$refs.more.close()
                const r = confirm("Are you sure!")
                if(r != true) { return }
                this.$bar.start()
                byMethod('delete', `/api/receipt_vouchers/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/receipt_vouchers')
                            this.$message.success(`You have successfully deleted clientpayment!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            }}
    }
</script>
