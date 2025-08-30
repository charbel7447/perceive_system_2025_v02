<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Journal {{model.number}}</span>
                <div>
                    <router-link :to="`/journal_vouchers`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/email/${model.id}/invoice`"
                        :class="['btn', model.status_id === 1 ? 'btn-primary' : '']"
                        title="Sent Email">
                        <i class="fa fa-envelope-o"></i>
                    </router-link>
                    <router-link :to="`/journal_vouchers/${model.id}/clone`" class="btn"
                        title="Clone">
                        <i class="fa fa-files-o"></i>
                    </router-link>
                    <div class="btn-group">
                        <a :href="`/docs/journal_vouchers/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a :href="`/docs/journal_vouchers/${model.id}?mode=download`" target="_blank" class="btn" title="Download PDF">
                            <i class="fa fa-download"></i>
                        </a>

                        <a v-if="model.document" target="_blank" class="btn" title="Uploaded Doc" :href="'/uploads/' + model.document" >
                            <i class="fa  fa-paperclip"></i>
                        </a>
                    </div>
                    <router-link  v-if="model.status_id != 2" :to="`/journal_vouchers/${model.id}/edit`"
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                     <dropdown title="More" ref="more">
                        <li v-if="model.status_id == 1">
                            <a @click.stop="markAs(2)">Post Document</a>
                        </li>
                        <li v-if="model.status_id != 2 || user.id == 1">
                            <a @click.stop="deleteModel">
                                Delete
                            </a>
                        </li>
                    </dropdown>
                </div>
            </div>
            <div class="panel-body">
                <div class="document">
                    <div class="document-heading">
                        <div class="row">
                            <div class="col col-4">
                                <label style="display: contents;"><strong>Document Type: </strong>
                                    <span v-if="model.document_type == 1"> &nbsp;&nbsp;Sales Invoice</span>
                                    <span v-if="model.document_type == 2"> &nbsp;&nbsp;Purchase Invoice (Vendor Bill)</span>
                                    <span v-if="model.document_type == 3"> &nbsp;&nbsp;Purchase Order</span>
                                    <span v-if="model.document_type == 4"> &nbsp;&nbsp;Sales Order</span>
                                    <span v-if="model.document_type == 5"> &nbsp;&nbsp;Manual Journal Entry (No linked doc)</span>
                                </label><br>
                                <label style="display: contents;">
                                <strong>Related Document: &nbsp;&nbsp;</strong>
                                    <span v-if="model.document_type == 1">
                                        <a style="color: red;" :href="'/invoices/' + model.document_id"><b>--> {{ model.document_number }}</b> </a>
                                    </span>
                                    <span v-if="model.document_type == 2">
                                        <a style="color: red;" :href="'/bills/' + model.document_id"><b>--> {{ model.document_number }}</b> </a>
                                    </span>
                                    <span v-if="model.document_type == 3">
                                        <a style="color: red;" :href="'/purchase_orders/' + model.document_id"><b>--> {{ model.document_number }}</b> </a>
                                    </span>
                                    <span v-if="model.document_type == 4">
                                        <a style="color: red;" :href="'/sales_orders/' + model.document_id"><b>--> {{ model.document_number }}</b> </a>
                                    </span>
                                    <span v-if="model.manual_type != null">
                                        <a style="color: red;" :href="'/' + model.manual_type + '/' + model.document_id"><b>--> {{ model.document_number }}</b> </a>
                                    </span>

                                </label><br>
                                <label style="display: contents;"><strong>Exchange Rate: &nbsp;&nbsp;</strong><span>{{model.exchange_rate}}</span></label><br>
                                <label style="display: contents;"><strong>Vat Rate: &nbsp;&nbsp;</strong><span>{{model.vat_rate}}</span></label><br>
                            </div>
                            <div class="col col-3">
                                &nbsp;
                            </div>
                            <div class="col col-5">
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
                                            <td>Total Credit:</td>
                                            <td>{{model.total_credit | formatMoney(model.currency, false)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Debit:</td>
                                            <td>{{model.total_debit | formatMoney(model.currency, false)}}</td>
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
                                    <th>Ledger Account</th>
                                    <th>Description</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="item in model.items">
                                    <tr>
                                        <td>
                                            <span class="form-control">{{ item.account_code }} - {{ item.account_name_en }} - {{ item.account_name_ar }}</span>
                                        </td>
                                        <td>
                                            <span class="form-control">{{ item.description }}</span>
                                        </td>
                                        <td>
                                            <span class="form-control">{{ item.debit  | formatMoney(model.currency, false) }}</span>
                                        </td>
                                        <td>
                                            <span class="form-control">{{ item.credit  | formatMoney(model.currency, false) }}</span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="document-footer row">
                         <strong>Note</strong>
                            <div class="document-terms"><br>
                              <pre>{{model.terms}}</pre>
                              </br>
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
    import Status from '../../components/status/Journal.vue'
    import { Dropdown } from '../../components/dropdown'
    export default {
        computed: {
            user() {
                return window.apex.user
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
            company_type(){
                return window.apex.company_type
            },
            display_vat(){
                return window.apex.display_vat
            },
        },
        components: { Status, Dropdown },
        data() {
            return {
                show: false,
                model: {
                    items: [],
                    currency: {},
                    client: {},
                    client_payments: [],
                    advance_payments: []
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/journal_vouchers/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/journal_vouchers/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Journal Voucher - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            },
            markAs(status) {
                this.$refs.more.close()
                this.$bar.start()
                post(`/api/journal_vouchers/${this.model.id}/mark`, {status: status})
                    .then(({data}) => {
                        if(data.saved) {
                            Vue.set(this.model, 'status_id', data.status_id)
                            Vue.set(this.model, 'is_editable', data.is_editable)
                            this.$message.success(`You have successfully marked Journal Voucher!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            deleteModel() {
                this.$refs.more.close()
                const r = confirm("Are you sure!")
                if(r != true) { return }
                this.$bar.start()
                byMethod('delete', `/api/journal_vouchers/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/journal_vouchers')
                            this.$message.success(`You have successfully deleted Journal Voucher!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            }
        }
    }
</script>
