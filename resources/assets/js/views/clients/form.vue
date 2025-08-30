<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Client</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name">
                            <error-text :error="error.name"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Name Ar</label>
                            <input type="text" class="form-control" v-model="form.name_ar">
                            <error-text :error="error.name_ar"></error-text>
                        </div>
                        
                        <div class="form-group">
                            <label>
                                Company
                            </label>
                            <input type="text" class="form-control" v-model="form.company">
                            <error-text :error="error.company"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" v-model="form.email">
                            <error-text :error="error.email"></error-text>
                        </div>
                        <div class="form-group">
                            <label>username
                                <a @click.stop="copyEmail">Copy Email</a>
                            </label>
                            
                            <input type="text" class="form-control" v-model="form.username">
                            <error-text :error="error.username"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Tax ID</label>
                            <input type="text" class="form-control" v-model="form.tax_id">
                            <error-text :error="error.tax_id"></error-text>
                        </div>
                        
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>
                                Mobile Number
                                <small>(optional)</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.phone">
                            <error-text :error="error.phone"></error-text>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label>
                                VAT Number
                                <small>(optional)</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.work_phone">
                            <error-text :error="error.work_phone"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Ref Number
                            </label>
                            <input type="text" class="form-control" v-model="form.ref_number">
                            <error-text :error="error.ref_number"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Account Code
                            </label>
                            <input disabled type="text" class="form-control" v-model="form.account_code">
                            <error-text :error="error.account_code"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Account Class</label>
                            <typeahead :initial="form.classes"
                                :url="classesURL"
                                @input="onClassUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.account_id"></error-text>
                            <input style="display: none;" type="text" class="form-control" v-model="form.account_id">
                        </div>
                        <div class="form-group" v-if="client_dropdown_1">
                            <label class="red">{{client_dropdown_1}}</label>
                            <typeahead :initial="form.client_dropdown_1"
                                :url="clientdropdown1URL"
                                @input="onclientdropdown1Update" required
                            >
                            </typeahead>
                            <error-text :error="error.uom_id"></error-text>
                        </div>
                        <div class="form-group" v-if="client_dropdown_2">
                            <label  class="red">{{client_dropdown_2}}</label>
                            <typeahead :initial="form.client_dropdown_2"
                                :url="clientdropdown2URL"
                                @input="onclientdropdown2Update" required
                            >
                            </typeahead>
                            <error-text :error="error.uom_id"></error-text>
                        </div>
                        <div class="form-group">
                            <label>seller</label>
                            <typeahead :initial="form.seller"
                                :url="sellerURL"
                                @input="onsellerUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.seller_id"></error-text>
                            <input style="display: none;" type="text" class="form-control" v-model="form.seller_id">
                            
                        </div>
                        <div class="form-group" v-if="form.allow_mobile == 1">
                            <label>
                                Password
                            </label>
                            <input type="text" class="form-control" v-model="form.password">
                            <error-text :error="error.password"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Delivery Condition</label>
                            <typeahead :initial="form.deliverycondition"
                                :url="deliveryconditionURL"
                                @input="onDeliveryConditionUpdated"
                            >
                            </typeahead>
                            <input  type="text" class="form-control" v-model="form.deliverycondition_name">
                            <error-text :error="error.deliverycondition_id"></error-text>
                        </div>
                       
                        
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Currency</label>
                            <typeahead :initial="form.currency"
                                :url="currencyURL"
                                @input="onCurrencyUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.currency_id"></error-text>
                        </div>
                        <div class="form-group">
                            <label>VAT Status</label>
                            <select class="form-control condition-select" v-model="form.vat_status">
                                <!-- <option value="2">Include VAT</option> -->
                                <option value="1">Include VAT</option>
                                <option value="0">Exclude VAT</option>
                            </select>
                            <error-text :error="error.vat_status"></error-text>
                        </div>
                        <div class="form-group">
                            <label>allow_mobile</label>
                            <select class="form-control condition-select" v-model="form.allow_mobile">
                                <!-- <option value="2">Include VAT</option> -->
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <error-text :error="error.allow_mobile"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Pricing Class</label>
                            <select class="form-control condition-select" v-model="form.price_class">
                                <!-- <option value="2">Include VAT</option> -->
                                <option value="0">None</option>
                                <option value="1">Class A</option>
                                <option value="2">Class B</option>
                                <option value="3">Class C</option>
                            </select>
                            <error-text :error="error.price_class"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Payment Condition</label>
                            <typeahead :initial="form.paymentcondition"
                                :url="paymentconditionURL"
                                @input="onPaymentConditionUpdated"
                            >
                            </typeahead>
                            
                            <input  type="text" class="form-control" v-model="form.paymentcondition_name">
                            <error-text :error="error.paymentcondition_id"></error-text>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" class="form-control" v-model="form.country">
                            <error-text :error="error.country"></error-text>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" class="form-control" v-model="form.state">
                            <error-text :error="error.state"></error-text>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>
                                City
                            </label>
                            <input type="text" class="form-control" v-model="form.city">
                            <error-text :error="error.city"></error-text>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>
                                Zip Code
                            </label>
                            <input type="text" class="form-control" v-model="form.zipcode">
                            <error-text :error="error.zipcode"></error-text>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Billing Address</label>
                            <textarea class="form-control" v-model="form.billing_address">
                            </textarea>
                            <error-text :error="error.billing_address"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>
                                <span>Shipping Address</span>
                                <a @click.stop="copyShippingAddress">Copy Shipping Address</a>
                            </label>
                            <textarea class="form-control" v-model="form.shipping_address">
                            </textarea>
                            <error-text :error="error.shipping_address"></error-text>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                    <strong class="form-controlz" style="float: left;width: 7%;padding: 2px  0;;">Show Filter</strong><br>
                    <input type="checkbox" :value="1" v-model="showFilter" style="width: 10px;top: 5px;
    position: relative;
    left: -25px;">
                </div>
            <div class="row col-md-12" v-if="showFilter == 1">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field1</label>
                            <input type="text" class="form-control" v-model="form.field1">
                            <error-text :error="error.field1"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field2</label>
                            <input type="text" class="form-control" v-model="form.field2">
                            <error-text :error="error.field2"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field3</label>
                            <input type="text" class="form-control" v-model="form.field3">
                            <error-text :error="error.field3"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field4</label>
                            <input type="text" class="form-control" v-model="form.field4">
                            <error-text :error="error.field4"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field5</label>
                            <input type="text" class="form-control" v-model="form.field5">
                            <error-text :error="error.field5"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field6</label>
                            <input type="text" class="form-control" v-model="form.field6">
                            <error-text :error="error.field6"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field7</label>
                            <input type="text" class="form-control" v-model="form.field7">
                            <error-text :error="error.field7"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field8</label>
                            <input type="text" class="form-control" v-model="form.field8">
                            <error-text :error="error.field8"></error-text>
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Field9</label>
                            <input type="text" class="form-control" v-model="form.field9">
                            <error-text :error="error.field9"></error-text>
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Field10</label>
                            <input type="text" class="form-control" v-model="form.field10">
                            <error-text :error="error.field10"></error-text>
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
    import Spinner from '../../components/loading/Spinner.vue'
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/clients/create`,
            'edit': `/api/clients/${to.params.id}/edit`,
            'clone': `/api/clients/${to.params.id}/edit?mode=clone`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        computed: {
            user() {
                return window.apex.user
            },
            company_type() {
                return window.apex.company_type
            },
            client_dropdown_1(){
                return window.apex.client_dropdown_1
            },
            client_dropdown_2(){
                return window.apex.client_dropdown_2
            },
        },
        components: { ErrorText, Typeahead, Spinner },
        mixins: [ form ],
        data () {
            return {
                showFilter: 0,
                resource: '/clients',
                store: '/api/clients',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created client!',
                currencyURL: '/api/search/currencies',
                sellerURL: '/api/search/sellers',
                deliveryconditionURL: '/api/search/deliverycondition',
                paymentconditionURL: '/api/search/paymentcondition',
                clientdropdown1URL: '/api/search/client_dropdown_1',
                clientdropdown2URL: '/api/search/client_dropdown_2',
                classesURL: '/api/search/chart_classes',
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/clients/${this.$route.params.id}`
                this.message = 'You have successfully updated client!'
                this.method = 'PUT'
                this.title = 'Edit'
            }
        },
        beforeRouteEnter(to, from, next) {

            get(initializeUrl(to))
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false

            get(initializeUrl(to))
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            save() {
                this.submit((data) => {
                    this.success()
                    this.$router.push(`${this.resource}/${data.id}`)
                })
            },
            saveAndNew() {
                this.submit((data) => {
                    const id = Math.random().toString(36).substring(7)
                    this.endProcessing()
                    this.success()
                    this.$router.push(`${this.resource}/create?new=${id}`)
                })
            },
            copyShippingAddress() {
                this.form.shipping_address = this.form.billing_address
            },
            copyEmail() {
                this.form.username = this.form.email
            },
            onCurrencyUpdate(e) {
                const currency = e.target.value
                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency', currency)
            },
            onClassUpdate(e) {
                const classes = e.target.value

                Vue.set(this.form, 'account_id', classes.code)
                Vue.set(this.form, 'classes', classes)
            },
            onsellerUpdate(e) {
                const seller = e.target.value

                // seller
                Vue.set(this.form, 'seller', seller)
                Vue.set(this.form, 'seller_id', seller.id)

                // bills
                // Vue.set(this.form, 'items', seller.bills)
            },

            onclientdropdown1Update(e) {
                const client_dropdown_1 = e.target.value

                Vue.set(this.form, 'client_dropdown_1_id', client_dropdown_1.id)
                Vue.set(this.form, 'client_dropdown_1', client_dropdown_1)
            },

            onclientdropdown2Update(e) {
                const client_dropdown_2 = e.target.value

                Vue.set(this.form, 'client_dropdown_2_id', client_dropdown_2.id)
                Vue.set(this.form, 'client_dropdown_2', client_dropdown_2)
            },

            onDeliveryConditionUpdated(e) {
                const deliverycondition = e.target.value
                Vue.set(this.form, 'deliverycondition', deliverycondition)
                Vue.set(this.form, 'deliverycondition_id', deliverycondition.id)
                Vue.set(this.form, 'deliverycondition_name', deliverycondition.name)
            },
            onPaymentConditionUpdated(e) {
                const paymentcondition = e.target.value
                Vue.set(this.form, 'paymentcondition', paymentcondition)
                Vue.set(this.form, 'paymentcondition_id', paymentcondition.id)
                Vue.set(this.form, 'paymentcondition_name', paymentcondition.name)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Client ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
