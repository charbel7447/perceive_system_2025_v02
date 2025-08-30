<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Exchange Rate</span>
            </div>
            <div class="panel-body">
                <div class="row">
                   
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Base Currency X
                                <small>Created In ERP Settings</small></label>
                            <typeahead :initial="form.currency">
                            </typeahead>
                            <error-text :error="error.currency_id"></error-text>
                        </div>
                       <div class="form-group">
                            <label>
                                Value X
                                <small>X of Value 1</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.value1" disabled="validated ? '' : disabled" value="1" placeholder="1">
                            <error-text :error="error.value1"></error-text>
                        </div>
                        </div>
                      
                         <div class="col col-4">
                           <div class="form-group">
                            <label>Second Currency Y</label>
                            <typeahead :initial="form.currency2"
                                :url="currency2URL"
                                @input="onCurrency2Update"
                            >
                            </typeahead>
                            <error-text :error="error.currency2_id"></error-text>
                        </div>
                         <!-- <div class="form-group">
                            <label>Currency 2</label>
                            <select class="form-control condition-select" v-model="form.currency1">
                                <option value="1">USD Dollar</option>
                                <option value="62">LBP Lebanese Pound</option>
                            </select>
                            <error-text :error="error.currency1"></error-text>
                        </div> -->
                        <div class="form-group">
                            <label>
                                Value Y
                                <small>X of Value 1 = Y of Value 2</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.value2">
                            <error-text :error="error.value2"></error-text>
                        </div>
                    </div>
                    <div class="col col-5">
                        <div class="form-group">
                            <label>Exchange Date</label>
                            <input type="date" class="form-control" v-model="form.exchangedate">
                            <error-text :error="error.exchangedate"></error-text>
                        </div>
                    </div>
                </div>
                <hr>
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
            'create': `/api/exchangerate/create`,
            'edit': `/api/exchangerate/${to.params.id}/edit`,
            'clone': `/api/exchangerate/${to.params.id}/edit?mode=clone`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        computed: {
            user() {
                return window.apex.user
            },
        },
        components: { ErrorText, Typeahead, Spinner },
        mixins: [ form ],
        data () {
            return {
                resource: '/exchangerate',
                store: '/api/exchangerate',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created client!',
                currency1URL: '/api/search/currencies',
                currency2URL: '/api/search/currencies'
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/exchangerate/${this.$route.params.id}`
                this.message = 'You have successfully updated exchangerate!'
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
            onCurrency1Update(e) {
                const currency = e.target.value

                Vue.set(this.form, 'currency1_id', currency.id)
                Vue.set(this.form, 'currency1', currency)
            },
            onCurrency2Update(e) {
                const currency = e.target.value

                Vue.set(this.form, 'currency2_id', currency.id)
                Vue.set(this.form, 'currency2', currency)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Exchange Rate ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
