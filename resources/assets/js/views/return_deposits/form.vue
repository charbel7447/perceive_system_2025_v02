<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Return Deposit</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>{{first_currency}} Balance</label>
                            <input disabled type="text" class="form-control" v-model="form.usdbalance" >
                            <error-text :error="error.vendor_id"></error-text>
                        </div>
                    </div>
                    <div class="col col-3"  v-if="display_exchange_rate == 1">
                        <div class="form-group">
                            <label>{{second_currency}} Balance</label>
                            <input disabled type="text" class="form-control" v-model="form.lbpbalance" >
                            <error-text :error="error.vendor_id"></error-text>
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
                </div>
                <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Return Deposit Date</label>
                            <input type="date" class="form-control" v-model="form.return_date">
                            <error-text :error="error.return_date"></error-text>
                        </div>
                    </div>
                </div>
                <div class="row">
                 <div class="col col-3">
                        <div class="form-group">
                            <label>To Employee</label>
                            <typeahead :initial="form.employee"
                                :url="employeeURL"
                                @input="onEmployeeUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.employee_id"></error-text>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>From Account</label>
                            <typeahead :initial="form.from_account"
                                :url="accountURL"
                                @input="onFromAccountUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.from_account_id"></error-text>
                        </div>
                    </div>
                </div>
                <div class="row">
                 <div class="col col-3">
                        <div class="form-group">
                            <label>Transfer Amount <small v-if="form.from_account_id === 1">Amount in {{first_currency}}</small>
                            <small v-if="form.from_account_id === 2">Amount in {{second_currency}}</small></label>
                            <input type="text" class="form-control" v-model="form.amount">
                            
                            <error-text :error="error.amount"></error-text>
                        </div>
                    </div>
                    <div class="col col-3"  v-if="display_exchange_rate == 1">
                        <div class="form-group">
                            <label>Exchange Rate <small>Only for record</small></label>
                            <input type="text" class="form-control" v-model="form.exchangerate">
                            <error-text :error="error.exchangerate"></error-text>
                        </div>
                    </div>
                </div>
                <hr>
         
         
                <div class="row">
                 <div class="col col-12">
                        <div class="form-group">
                            <label>Note:</label>
                            <textarea class="form-control" v-model="form.note"></textarea>
                            <error-text :error="error.note"></error-text>
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
            'create': `/api/return_deposits/create`
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        components: { ErrorText, Typeahead, Spinner, FileUpload },
        mixins: [ form ],
        data () {
            return {
                resource: '/return_deposits',
                store: '/api/return_deposits',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully made the transfer !',
                currencyURL: '/api/search/currencies',
                accountURL: '/api/search/accounts',
                employeeURL: '/api/search/employees',
            }
        },
        computed: {
            base_currency() {
                return window.apex.base_currency
            },
            first_currency(){
                return window.apex.first_currency
            },
            display_exchange_rate(){
                return window.apex.display_exchange_rate
            },
            second_currency(){
                return window.apex.second_currency
            },
            total() {
              return this.form.items.reduce((carry, item) => {
                    return carry + (Number(item.amount_applied)) + ((Number(item.amount_applied_lbp)) / (Number(item.amount_applied_lbp_rate))) + ((Number(item.amount_applied_vat)) / (Number(item.amount_applied_vat_rate)))
                }, 0)
            },
            totalReceived(){
                   return  Number(form.amount_received) + Number(form.amount_received_lbp)
            },
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
            onVendorUpdate(e) {
                const vendor = e.target.value

                // vendor
                Vue.set(this.form, 'vendor', vendor)
                Vue.set(this.form, 'vendor_id', vendor.id)

                // currency
                Vue.set(this.form, 'currency', vendor.currency)
                Vue.set(this.form, 'currency_id', vendor.currency.id)

                // bills
                Vue.set(this.form, 'items', vendor.bills)
            },
            onEmployeeUpdate(e) {
                const employee = e.target.value

                Vue.set(this.form, 'employee_id', employee.id)
                Vue.set(this.form, 'employee', employee)
            },
            onFromAccountUpdate(e) {
                const from_account = e.target.value

                Vue.set(this.form, 'from_account_id', from_account.id)
                Vue.set(this.form, 'from_account', from_account)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Deposit ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
