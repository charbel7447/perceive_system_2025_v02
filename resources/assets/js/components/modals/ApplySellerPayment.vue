<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-heading">Add Seller Payment</div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col col-6">
                                <div class="form-group">
                                    <label>Reference</label>
                                    <input  type="text" class="form-control" v-model="form.payment_reference">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                            <label>Payment Mode</label>
                            <select class="form-control" v-model="form.payment_mode">
                                <option :value="1">Cash</option>
                                <option :value="2">Cheque</option>
                                <option :value="3">Bank Transfer</option>
                            </select>
                            <error-text :error="error.payment_mode"></error-text>
                        </div>
                            </div>

                            <div class="col col-3">
                        <div class="form-group">
                            <label>
                                Upload Document
                            </label>
                            <file-upload @ready="onDocument"></file-upload>
                            {{ form.document }}
                            <error-text :error="error.document"></error-text>
                        </div>
                    </div>
                            
                        </div>
                        <div class="row">
                            <div class="col col-12">
                                <div class="form-group">
                                    <label>Note</label>
                                    <input  type="text" class="form-control" v-model="form.note">
                                </div>
                            </div>
                        </div>
                        <div>
                            
                            <hr>
                            <table class="item-table">
                                <thead>
                                    <tr>
                                        <th>Sales Order</th>
                                        <th>SP Number</th>
                                        <th>Order Amount</th>
                                        <th>Seller Amount</th>
                                        <th>Pending Amount</th>
                                        <th>Amount Applied</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in form.items">
                                        <td class="width-2" style="display: none;">
                                            <span class="form-control">{{item.seller_payment_id}}</span>
                                        </td>
                                        <td class="width-2">
                                           <a target="_blank" :href="/sales_orders/ + item.sales_order_id " >
                                                <span class="form-control">{{item.sales_order_number}}</span>
                                                <input style="display: none;" type="text" class="form-control" v-model="item.sales_order_id">
                                           </a> 
                                        </td>
                                        <td class="width-3">
                                            <span class="form-control">{{item.number}}</span>
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
                    </div>
                    <div class="panel-footer">
                        <spinner v-if="isProcessing"></spinner>
                        <div class="btn-group" v-else>
                            <button :disabled="isProcessing" @click="save" class="btn btn-primary" v-if="form.items.length">
                                Save
                            </button>
                            <button :disabled="isProcessing" @click="closeModal" class="btn">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </transition>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get } from '../../lib/api'
    import { form } from '../../lib/mixins'
    import FileUpload from '../../components/form/FileUpload.vue'
    import ErrorText from '../../components/form/ErrorText.vue'
    import Spinner from '../../components/loading/Spinner.vue'
    export default {
        components: {ErrorText, Spinner, FileUpload },
        mixins: [form],
        data() {
            return {
                resource: `/seller_payments/${this.$route.params.id}`,
                url: `/api/search/seller_payments_invoices/${this.$route.params.id}`,
                store: `/api/seller_payments_invoices/${this.$route.params.id}/apply`,
                method: 'POST',
                message: 'You have successfully applied payment to seller!',
                form: {
                    currency: {},
                    client: {},
                    items: []
                }
            }
        },
        computed: {
            total() {
                return this.form.items.reduce((carry, item) => {
                    return carry + (Number(item.amount_received))
                }, 0)
            },
            amount_pending() {
                return this.form.items.reduce((carry, item) => {
                    return carry + (Number(item.amount_pending))
                }, 0)
            },
        },
        created() {
            this.fetchData()
        },
        methods: {
            closeModal() {
                this.$emit('close')
            },
            fetchData() {
                get(this.url)
                    .then((response) => {
                        if(response.data) {
                            Vue.set(this.$data, 'form', response.data.data)
                            this.show = true
                        }
                    })
                    .catch((error) => {
                        if(error.response.status === 422) {
                            Vue.set(this.$data, 'error', error.response.data)
                        }
                    })
            },
            onDocument(e) {
                Vue.set(this.$data.form, 'document', e.target.value)
            },
            // save() {
            //     this.submit((data) => {
            //         const id = Math.random().toString(36).substring(7)
            //         this.success()
            //         this.closeModal()
            //         this.$router.push(`${this.resource}?success=${id}`)
            //     })
            // },
            save() {
                this.submitMultipartForm(this.form, (data) => {
                    // this.endProcessing()
                    const id = Math.random().toString(36).substring(7)
                    this.success()
                    this.closeModal()
                    this.$router.push(`${this.resource}?success=${id}`)
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

        }
    }
</script>
