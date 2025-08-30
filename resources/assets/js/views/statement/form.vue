<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Statement</span>
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
                                total_revenue
                            </label>
                            <input type="text" class="form-control" v-model="form.total_revenue">
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
           
            <div class="col col-6" style="display:none;">
                <h4>Quick Reports | Current Client | Current Year</h4>
             <div class="btn-group">
                 <div class="col col-12">
                  <div class="form-group">
                    <button :disabled="isProcessing" @click="exportReport"   class="btn btn-primary" style="margin: 10px;">
                          Generate General Report
                    </button>
                  </div>
                 </div>
                 <div class="col col-12">
                  <div class="form-group">
                    <button :disabled="isProcessing" @click="inReport"   class="btn btn-primary" style="margin: 10px;">
                         Show Invoices Report
                    </button>
                    </div>
                     </div>
                 <div class="col col-12">
                  <div class="form-group">
                    <button :disabled="isProcessing" @click="inReportPDF"   class="btn btn-primary" style="margin: 10px;">
                        <i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Invoices Report PDF
                    </button>
                    </div>
                     </div>
                 <div class="col col-12" style="display:none;">
                  <div class="form-group">
                    <button :disabled="isProcessing" @click="inReportEXCEL"   class="btn btn-primary" style="margin: 10px;">
                          <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Generate Invoices Report Excel
                    </button>
                    </div>
                     </div>
                 <div class="col col-12">
                  <div class="form-group">
          
                    <button :disabled="isProcessing" @click="cpReport"   class="btn btn-primary" style="margin: 10px;">
                          <i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Generate Client Payments Report
                    </button>
                    </div>
                     </div>
                      <div class="col col-12" style="display:none;">
                  <div class="form-group">
                    <button :disabled="isProcessing" @click="cpReportEXCEL"   class="btn btn-primary" style="margin: 10px;">
                          <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Generate Client Payments  Report Excel
                    </button>
                    </div>
                     </div>

                     <div class="col col-12">
                  <div class="form-group">
                    <button :disabled="isProcessing" @click="ReportQty"   class="btn btn-primary" style="margin: 10px;">
                          <i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Generate Products Quantity Report
                    </button>
                    </div>
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
            'create': `/api/statement/create`,
            'edit': `/api/statement/${to.params.id}/edit`,
            'clone': `/api/statement/${to.params.id}/edit?mode=clone`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

 

    export default {
        components: { ErrorText, Typeahead, Spinner },
        mixins: [ form ],
        data () {
            return {
                resource: '/statement',
                store: '/api/statement',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created statement!',
                currencyURL: '/api/search/currencies'
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/statement/${this.$route.params.id}`
                this.message = 'You have successfully created your statement!'
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
            exportReport() {
                    window.location.href = `/statement/report/${this.$route.params.id}`
            },
            inReport() {
                    window.location.href = `/statement/inreport/${this.$route.params.id}`
            },
            inReportPDF(){
                    window.location.href = `/statement/inreportpdf/${this.$route.params.id}`
            },
            inReportEXCEL(){
                    window.location.href = `/statement/inreportexcel/${this.$route.params.id}`
            },
            cpReport() {
                    window.location.href = `/statement/cpreport/${this.$route.params.id}`
            },
            cpReportEXCEL(){
                    window.location.href = `/statement/cpreportexcel/${this.$route.params.id}`
            },
            ReportQty(){
                    window.location.href = `/statement/reportqty/${this.$route.params.id}`
            },
            poReport() {
                    window.location.href = `/statement/poreport/${this.$route.params.id}`
            },
            poReportEXCEL(){
                    window.location.href = `/statement/poreportexcel/${this.$route.params.id}`
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
                this.$title.set(`Statement ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
