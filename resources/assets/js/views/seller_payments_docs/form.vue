<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} seller Payment</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>seller</label>
                            <typeahead :initial="form.seller" :params="{with: 'seller_payments'}"
                                :url="sellerURL"
                                @input="onsellerUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.seller_id"></error-text>
                            <input style="display: none;" type="text" class="form-control" v-model="form.seller_id">
                            
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
                    <!-- <div class="col col-3">
                        <div class="form-group">
                            <label>Payment Mode</label>
                            <select class="form-control" v-model="form.payment_mode">
                                <option :value="1">Cheque</option>
                                <option :value="2">Cash</option>
                                <option :value="3">Bank Transfer</option>
                            </select>
                            <error-text :error="error.payment_mode"></error-text>
                        </div>
                    </div> -->
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Payment Mode</label>
                            <typeahead :initial="form.payment_options"
                                :url="paymentOptionURL"
                                @input="onPayemntOptionUpdate"
                            >
                            </typeahead>
                            <input style="display: none;" type="text" class="form-control" v-model="form.payment_mode">
                            <error-text :error="error.payment_option_id"></error-text>
                        </div>
                    </div>
                </div>
                <div class="row">
                   
                    
                    <div class="col col-3">
                        <div class="form-group">
                            <label>
                                Payment Reference
                                <small v-if="form.payment_mode == 'cash'">(Optional)</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.payment_reference">
                            <error-text :error="error.payment_reference"></error-text>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>
                                Upload Document
                                <small v-if="form.payment_mode == 'cash'">(Optional)</small>
                                <small v-else>(cheque, transfer receipt)</small>
                            </label>
                            <file-upload @ready="onDocument"></file-upload>
                            <error-text :error="error.document"></error-text>
                        </div>
                    </div>
                    <div class="col col-6">
                                <div class="form-group">
                                    <label>Note</label>
                                    <input  type="text" class="form-control" v-model="form.note">
                                </div>
                            </div>
                </div>
               
                
                        <hr>
                   
                <table class="item-table" v-if="form.seller_id">
                    <thead>
                        <tr>
                            <th>Sales Order</th>
                            <th>SP Number</th>
                            <th>Payment Date</th>
                            <th>Order Amount</th>
                            <th>Seller Amount</th>
                            <th>Pending Amount</th>
                            <th>Amount Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in form.seller.seller_payments" style="border: 1px solid transparent;">
                            <td class="width-1" style="display: none;">
                                <span class="form-control">{{item.seller_payment_id}}</span>
                            </td>
                            <td class="width-1">
                                           <a target="_blank" :href="/sales_orders/ + item.sales_order_id " >
                                                <span class="form-control">{{item.sales_order_number}}</span>
                                                <input style="display: none;" type="text" class="form-control" v-model="item.sales_order_id">
                                           </a> 
                                        </td>
                                        <td style="display: none;">
                                            <input disabled type="text" class="form-control" v-model="item.client_id">
                                        </td>
                                        <td class="width-2">
                                            <span class="form-control">{{item.number}}</span>
                                        </td>
                                        <td class="width-2">
                                            <input  type="date" class="form-control" v-model="item.payment_date">
                                        </td>
                                        
                                        <td class="width-2">
                                            <span class="form-control">{{item.order_amount}}</span>
                                        </td>
                                        <td class="width-2">
                                            <span class="form-control">{{item.total_amount}}</span>
                                        </td>
                                        <td class="width-2" style="display: non;">
                                            <span class="form-control">{{item.amount_pending}}</span>
                                        </td>
                                        <td class="width-2">
                                            <input type="text" class="form-control" v-model="item.amount_received">
                                            <error-text :error="error[`items.${index}.amount_received`]"></error-text>
                                        </td>
                        </tr>
                        <hr>
                    </tbody>
                    <tfoot>
                        <tr>
                                        <td class="item-footer" colspan="4">Balance Amount</td>
                                        <td class="item-footer">
                                            <strong class="item-dark form-control">
                                                {{amount_pending | formatMoney(form.currency)}}
                                            </strong>
                                            <error-text :error="error.amount_pending"></error-text>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item-footer" colspan="4">Amount Applied</td>
                                        <td class="item-footer">
                                            <strong class="item-dark form-control">
                                                {{total | formatMoney(form.currency)}}
                                            </strong>
                                        </td>
                                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="panel-footer" v-if="form.seller_id">
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
            'create': `/api/seller_payments_docs/create`
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        components: { ErrorText, Typeahead, Spinner, FileUpload },
        mixins: [ form ],
        data () {
            return {
                resource: '/seller_payments_docs',
                store: '/api/seller_payments_docs',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully paid seller payment!',
                currencyURL: '/api/search/currencies',
                sellerURL: '/api/search/sellers',
                paymentOptionURL: '/api/search/sp_payment_options',
            }
        },
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
            display_vat(){
                return window.apex.display_vat
            },
            total() {
                return this.form.seller.seller_payments.reduce((carry, item) => {
                    return carry + (Number(item.amount_received))
                }, 0)
            },
            amount_pending() {
                return this.form.seller.seller_payments.reduce((carry, item) => {
                    return carry + (Number(item.amount_pending))
                }, 0)
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
            save() {
                this.submitMultipartForm(this.form, (data) => {
                    // this.endProcessing()
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
            onsellerUpdate(e) {
                const seller = e.target.value

                // seller
                Vue.set(this.form, 'seller', seller)
                Vue.set(this.form, 'seller_id', seller.id)

                // bills
                // Vue.set(this.form, 'items', seller.bills)
            },
            onCurrencyUpdate(e) {
                const currency = e.target.value

                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency', currency)
            },
            onPayemntOptionUpdate(e) {
                const payment_options = e.target.value

                Vue.set(this.form, 'payment_option_id', payment_options.id)
                Vue.set(this.form, 'payment_mode', payment_options.name)
                Vue.set(this.form, 'payment_options', payment_options)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Seller Payment ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
