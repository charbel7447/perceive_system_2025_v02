<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Payroll {{model.number}}</span>
                <div>
                    <router-link :to="`/payroll`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <div class="btn-group">
                        <a :href="`/docs/payroll/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a :href="`/docs/payroll/${model.id}?mode=download`" target="_blank" class="btn" title="Download PDF">
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
                                <p><strong>Paid To:</strong></p>
                                <router-link :to="`/employees/${model.employee_id}`">
                                    <span>{{model.employee.name}}</span><br>
                                    <span>{{model.employee.company}}</span><br>
                                    <pre>{{model.employee.salary}}</pre>
                                </router-link>
                            </div>
                            <div class="col col-4">
                                &nbsp;
                            </div>
                            <div class="col col-4">
                                <table class="document-summary">
                                    <tbody>
                                        <tr>
                                            <td>Number:</td>
                                            <td>{{model.number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Payment Date:</td>
                                            <td>{{model.payment_date}}</td>
                                        </tr>
                                        <tr>
                                            <td>Currency:</td>
                                            <td>{{model.currency.text}}</td>
                                        </tr>
                                        <tr>
                                            <td>Amount Paid {{first_currency}}:</td>
                                            <td>{{model.amount_paid | formatMoney(model.currency, false)}}</td>
                                        </tr>
                                         <tr>
                                            <td>Amount Paid {{second_currency}}:</td>
                                            <td>{{model.amount_paid_lbp | formatMoney(model.currency, false)}}
                                                at rate {{model.exchangerate}}
                                            </td>
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

                                <strong>Amount Paid {{first_currency}}</strong><br>
                                <p>{{first_currency}} {{model.amount_paid | formatMoney(model.currency, false)}}</p>
                                 <strong>Amount Paid {{second_currency}}</strong><br>
                                <p>{{second_currency}} {{model.amount_paid_lbp | formatMoney(model.currency,false)}} at rate {{model.exchangerate}}</p>
                                <strong>Total Amount Paid</strong><br>
                                <p>{{first_currency}} {{model.amount_paid + (model.amount_paid_lbp / model.exchangerate)| formatMoney(model.currency,false)}}</p>
                            </div>
                        </div>
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
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get } from '../../lib/api'
    import Status from '../../components/status/AdvancePayment.vue'
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
        components: { Status },
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
            get(`/api/payroll/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/payroll/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Payroll - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
