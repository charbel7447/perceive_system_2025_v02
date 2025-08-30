<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Payroll</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Employee</label>
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
                            <label>
                                Salary
                                <small>(Auto Generated)</small>
                            </label>
                            <span class="form-control">{{form.salary}}&nbsp;{{form.currency.code}}</span>
                        </div>
                    </div>
                    <div class="col col-3" style="display:none;">
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
                </div>
                <hr>
                <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Payment Date</label>
                            <input type="date" class="form-control" v-model="form.payment_date">
                            <error-text :error="error.payment_date"></error-text>
                        </div>
                    </div>
                    <div class="col col-3">
                         <div class="form-group">
                             <label>Amount Paid {{first_currency}}</label>
                             <input type="text" class="form-control" v-model="form.amount_paid">
                             <error-text :error="error.amount_paid"></error-text>
                         </div>
                    </div>
                    <div class="col col-3">
                         <div class="form-group">
                             <label>Amount Paid {{second_currency}}</label>
                             <input type="text" class="form-control" v-model="form.amount_paid_lbp">
                             <error-text :error="error.amount_paid_lbp"></error-text>
                         </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Exchange Rate (1x to {{second_currency}})</label>
                            <input type="text" class="form-control" v-model="form.exchangerate">
                            <error-text :error="error.exchangerate"></error-text>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Description</label>
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
            'create': `/api/payroll/create`
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
        },
        components: { ErrorText, Typeahead, Spinner, FileUpload },
        mixins: [ form ],
        data () {
            return {
                resource: '/payroll',
                store: '/api/payroll',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created expense!',
                currencyURL: '/api/search/currencies',
                employeeURL: '/api/search/employees'
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
            onEmployeeUpdate(e) {
                const employee = e.target.value

                // employee
                Vue.set(this.form, 'employee', employee)
                Vue.set(this.form, 'employee_id', employee.id)

                Vue.set(this.form, 'salary', employee.salary)
                // currency
                Vue.set(this.form, 'currency', employee.currency)
                Vue.set(this.form, 'currency_id', employee.currency.id)
            },
            onCurrencyUpdate(e) {
                const currency = e.target.value

                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency', currency)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Payroll ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>

