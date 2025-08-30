<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Credit Note {{model.number}}</span>
                <div>
                    <div class="btn-group" v-if="model.posted == 1">
                        <router-link style="background:green;color: #fff;" :to="`/journal_vouchers/${model.journal_id}/`"
                          class="btn" title="Journal Document">
                            POSTED &nbsp;<i class="fa fa-arrow-right"></i>
                        </router-link>
                    </div>
                    <button class="btn btn-primary" v-if="model.status_id == 1 || model.status_id == 5"
                        @click="showModal = true">Apply to Invoices</button>
                    <router-link :to="`/credit_notes`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <!-- <router-link :to="`/email/${model.id}/credit_notes`" class="btn btn-primary" title="Sent Email">
                        <i class="fa fa-envelope-o"></i>
                    </router-link> -->
                    <dropdown title="More" ref="more">
                        <li>
                            <a @click.stop="markAs(4)">Mark as Cancelled</a>
                        </li>
                    </dropdown>
                    <div class="btn-group">
                        <a :href="`/docs/credit_notes/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a :href="`/docs/credit_notes/${model.id}?mode=download`" target="_blank" class="btn" title="Download PDF">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="document">
                    <div class="document-heading">
                        <div class="row">
                            <div class="col col-4">
                                <p><strong>Payment From:</strong></p>
                                <router-link :to="`/clients/${model.client_id}`">
                                    <span>{{model.client.person}}</span><br>
                                    <span>{{model.client.company}}</span><br>
                                    <pre>{{model.client.billing_address}}</pre>
                                </router-link>
                            </div>
                            <div class="col col-2">
                                &nbsp;
                            </div>
                            <div class="col col-6">
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
                                            <td>{{model.payment_date}}</td>
                                        </tr>
                                        <tr>
                                            <td>Payment Mode:</td>
                                            <td>{{model.payment_mode}}</td>
                                        </tr>
                                        <tr v-if="model.payment_reference">
                                            <td>Reference:</td>
                                            <td>{{model.payment_reference}}</td>
                                        </tr>
                                        <tr v-if="model.quotation_id">
                                            <td>Quotation Number:</td>
                                            <td>
                                                <router-link :to="`/quotations/${model.quotation_id}`">
                                                    {{model.quotation.number}}
                                                </router-link>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Currency:</td>
                                            <td>{{model.currency.text}}</td>
                                        </tr>
                                        <tr>
                                            <td>Credit Amount:</td>
                                            <td>{{Number(model.amount_received) + Number(model.amount_received_lbp / model.exchangerate)   + Number(model.vat_received_usd) + Number(model.vat_received_lbp / model.exchangerate) | formatMoney(model.currency, false)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="document-body">
                        <hr>
                        <div class="row">
                            <div class="col col-8">
                                <strong>Description</strong><br>
                                <pre>{{model.description}}</pre><br>

                                <strong>Credit Amount Received</strong><br>
                                <p>{{model.amount_received + (model.amount_received_lbp / model.exchangerate)| formatMoney(model.currency)}}</p>
                            </div>
                            <div class="col col-4" v-if="model.applied_amount && model.applied_date">
                                <strong>Amount Applied to Invoices</strong><br>
                                <p>{{model.applied_amount | formatMoney(model.currency)}}</p>
                                <strong>Amount Applied Date</strong><br>
                                <p>{{model.applied_date}}</p>
                            </div>
                        </div>
                        <hr>
                        <table class="document-table" v-if="model.items.length">
                            <thead>
                                <tr>
                                    <th>Invoice Date</th>
                                    <th>Invoice Number</th>
                                    <th class="align-center">Total</th>
                                    <th class="align-center">Amount Applied</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in model.items">
                                    <td class="width-4">
                                        {{item.invoice.date}}
                                    </td>
                                    <td class="width-2">
                                        <router-link :to="`/invoices/${item.invoice_id}`">
                                            {{item.invoice.number}}
                                        </router-link>
                                    </td>
                                    <td class="width-2 align-right">
                                        {{item.invoice.total | formatMoney(model.currency, false)}}
                                    </td>
                                    <td class="width-2 align-right">
                                        {{item.amount_applied | formatMoney(model.currency, false)}}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="1"></td>
                                    <td colspan="2">
                                        <strong>Credit Amount Received {{first_currency}}</strong>
                                    </td>
                                    <td class="align-right">
                                        <strong>
                                            {{model.amount_received | formatMoney(model.currency,false)}}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1"></td>
                                    <td colspan="2">
                                        <strong>Credit Amount Received {{second_currency}}</strong>
                                    </td>
                                    <td class="align-right">
                                        <strong>
                                            {{model.amount_received_lbp | formatMoney(model.currency,false)}}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1"></td>
                                    <td colspan="2">
                                        <strong>Amount Applied to Invoices</strong>
                                    </td>
                                    <td class="align-right">
                                        <strong>
                                            {{model.applied_amount | formatMoney(model.currency)}}
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
        <apply-invoices v-if="showModal" @close="showModal = false"></apply-invoices>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, post, byMethod } from '../../lib/api'
    import Status from '../../components/status/CreditNotes.vue'
    import { Dropdown } from '../../components/dropdown'
    import ApplyInvoices from '../../components/modals/ApplyCredit.vue'

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
         },
        components: { Status, Dropdown, ApplyInvoices },
        data() {
            return {
                show: false,
                showModal: false,
                model: {
                    quotation: {},
                    currency: {},
                    client: {},
                    items: []
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/credit_notes/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/credit_notes/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            markAs(status) {
                this.$refs.more.close()
                this.$bar.start()
                post(`/api/credit_notes/${this.model.id}/mark`, {status: status})
                    .then(({data}) => {
                        if(data.saved) {
                            Vue.set(this.model, 'status_id', data.status_id)
                            Vue.set(this.model, 'is_editable', data.is_editable)
                            this.$message.success(`You have successfully delete this credit note!`)
                        }
                        this.$bar.finish()
                    })

            },

            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Credit Note - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
