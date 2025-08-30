<template>
    <div v-if="show && user.is_employee_edit == 1  || user.is_admin == 1 || user.is_employee_create == 1 || user.is_employee_delete == 1">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Employee</span>
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
                            <label>Title</label>
                            <input type="text" class="form-control" v-model="form.title">
                            <error-text :error="error.title"></error-text>
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
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>
                                Mobile Number
                                <small>(optional)</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.mobile_number">
                            <error-text :error="error.mobile_number"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Work Phone
                                <small>(optional)</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.telephone">
                            <error-text :error="error.telephone"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Extension
                                <small>(optional)</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.extension">
                            <error-text :error="error.extension"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Salary</label>
                            <input type="text" class="form-control" v-model="form.salary">
                            <error-text :error="error.salary"></error-text>
                        </div>
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
                </div>
                <hr>
                
            </div>
            <div class="panel-footer">
                <div class="btn-group">
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
    <div v-else>
    <div class="col col-12" v-if="user.is_employee_edit != 1" style="margin: 25% auto;">
        <p style="color:red;text-align:center;"><strong>You don't Have Permission, Contact your system administrator</strong></p>
    </div></div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import ErrorText from '../../components/form/ErrorText.vue'
    import Typeahead from '../../components/form/Typeahead.vue'
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/employees/create`,
            'edit': `/api/employees/${to.params.id}/edit`,
            'clone': `/api/employees/${to.params.id}/edit?mode=clone`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        computed: {
            user() {
                return window.apex.user
            },
        },
        components: { ErrorText, Typeahead },
        mixins: [ form ],
        data () {
            return {
                resource: '/employees',
                store: '/api/employees',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created vendor!',
                currencyURL: '/api/search/currencies'
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/employees/${this.$route.params.id}`
                this.message = 'You have successfully updated vendor!'
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
            onCurrencyUpdate(e) {
                const currency = e.target.value
                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency', currency)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Employees ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
