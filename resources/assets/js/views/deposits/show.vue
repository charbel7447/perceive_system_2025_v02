<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Deposit {{model.number}}</span>
                <div>
                    <router-link :to="`/deposits`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                </div>
            </div>
            <div class="panel-body">
                <div class="document">
                    <div class="document-heading">
                        <div class="row">
                            <div class="col col-4">
                                <p><strong>From:
                                    {{model.employee.name}}
                                </strong></p>
                                <p><strong>To:
                                    {{model.to_account.name}}
                                </strong></p>
                            </div>
                            <div class="col col-3">
                                &nbsp;
                            </div>
                            <div class="col col-5">
                                <table class="document-summary">
                                    <tbody>
                                        <tr>
                                            <td>Amount:</td>
                                            <td>{{model.amount}}</td>
                                        </tr>
                                        <tr v-if="display_exchange_rate == 1">
                                            <td>Exchange Rate:</td>
                                            <td>{{model.exchangerate}}</td>
                                        </tr>
                                        <tr>
                                            <td>Number:</td>
                                            <td>{{model.number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Date:</td>
                                            <td>{{model.deposit_date}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <table class="document-summary">
                                    <thead>
                                        <tr>
                                            <td><b>Note: </b>{{ model.note }}</td>
                                        </tr>
                                    </thead>
                                  </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, post, byMethod } from '../../lib/api'
    import Status from '../../components/status/Invoice.vue'
    import { Dropdown } from '../../components/dropdown'
    export default {
        computed: {
            base_currency() {
                return window.apex.base_currency
            },
            display_exchange_rate(){
                return window.apex.display_exchange_rate
            },
        },
        components: { Status, Dropdown },
        data() {
            return {
                show: false,
                model: {
                    items: [],
                    currency: {},
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/deposits/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/deposits/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`deposits - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            },
        }
    }
</script>
