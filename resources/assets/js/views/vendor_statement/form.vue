<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} vendor_statement</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-12">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Person</label>
                            <input type="text" class="form-control" v-model="form.person">
                            <error-text :error="error.person"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Company
                            </label>
                            <input type="text" class="form-control" v-model="form.company">
                            <error-text :error="error.company"></error-text>
                        </div>
                         <div class="form-group">
                            <label>
                                total_expense
                            </label>
                            <input type="text" class="form-control" v-model="form.total_expense">
                            <error-text :error="error.company"></error-text>
                        </div>
                    </div>
                   <div class="col col-3">
                        <div class="form-group">
                            <label>From Date
                            <small>(Required)</small>
                            </label>
                            <input type="date" class="form-control" v-model="form.date" v-bind="attrs" >
                            <error-text :error="error.date"></error-text>
                        </div>
                    </div>
                     <div class="col col-3">
                        <div class="form-group">
                            <label>
                                To Date
                                <small>(Required)</small>
                            </label>
                            <input type="date" class="form-control" v-model="form.due_date" >
                            <error-text :error="error.due_date"></error-text>
                        </div>
                    </div>
                   
             
              <div class="panel-footer" style="float: left; margin: 5px 30%;">
                <spinner v-if="isProcessing"></spinner>
                <div class="btn-group" v-else>
                    
                    
                    <button :disabled="isProcessing" @click="save"   class="btn btn-primary">
                         <i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Export
                    </button>

                    <button :disabled="isProcessing" v-if="!isEdit"
                        @click="saveAndNew" class="btn btn-secondary">
                        Export and New
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
            'create': `/api/vendor_statement/create`,
            'edit': `/api/vendor_statement/${to.params.id}/edit`,
            'clone': `/api/vendor_statement/${to.params.id}/edit?mode=clone`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

 

    export default {

        components: { ErrorText, Typeahead, Spinner },
        mixins: [ form ],
        data () {
            return {
                resource: '/vendor_statement',
                store: '/api/vendor_statement',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created vendor_statement!',
                currencyURL: '/api/search/currencies'
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/vendor_statement/${this.$route.params.id}`
                this.message = 'You have successfully created your vendor_statement!'
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
                          this.endProcessing()
                    // this.$router.push(`${this.resource}/${data.id}`)
                    window.location.href = `/docs${this.resource}/${this.$route.params.id}`
                    //c/onsole.log(`/docs${resource}/${$route.params.id}`);x
                    
                })
            },
            url() { 
                href=`/docs${resource}/${$route.params.id}`
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
                this.$title.set(`vendor_statement ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
