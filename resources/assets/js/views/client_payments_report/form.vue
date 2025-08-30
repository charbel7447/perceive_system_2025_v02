<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Custom Report -- Client Payment</span>
            </div>
            <div class="panel-body">
                <div class="col col-12">
                    <div class="row">
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Clients
                                </label>
                                <typeahead :initial="form.client" :trim="80"
                                    :url="clientURL"
                                    @input="onClientUpdate">
                                </typeahead>
                                <error-text :error="error.client_id"></error-text>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Field 1
                                </label>
                                <input  type="text" class="form-control" v-model="form.field1">
                                <error-text :error="error.field1"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Field 2
                                </label>
                                <input  type="text" class="form-control" v-model="form.field2">
                                <error-text :error="error.field2"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Field 3
                                </label>
                                <input  type="text" class="form-control" v-model="form.field3">
                                <error-text :error="error.field3"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Field 4
                                </label>
                                <input  type="text" class="form-control" v-model="form.field4">
                                <error-text :error="error.field4"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Field 5
                                </label>
                                <input  type="text" class="form-control" v-model="form.field5">
                                <error-text :error="error.field5"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Field 6
                                </label>
                                <input  type="text" class="form-control" v-model="form.field6">
                                <error-text :error="error.field6"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Field 7
                                </label>
                                <input  type="text" class="form-control" v-model="form.field7">
                                <error-text :error="error.field7"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Field 8
                                </label>
                                <input  type="text" class="form-control" v-model="form.field8">
                                <error-text :error="error.field8"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Field 9
                                </label>
                                <input  type="text" class="form-control" v-model="form.field9">
                                <error-text :error="error.field9"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Field 10
                                </label>
                                <input  type="text" class="form-control" v-model="form.field10">
                                <error-text :error="error.field10"></error-text>
                            </div>
                        </div>
                    </div>
                     <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>From Date</label>
                            <input required type="date" class="form-control" v-model="form.from_date">
                            <error-text :error="error.from_date"></error-text>
                        </div>
                    </div>
               
                    <div class="col col-3">
                        <div class="form-group">
                            <label>To Date</label>
                            <input required type="date" class="form-control" v-model="form.to_date">
                            <error-text :error="error.to_date"></error-text>
                        </div>
                    </div>
                </div>
                <div class="col col-6">
                    
                </div>
                
                </div>
                <hr>
            </div>
            <div class="panel-footer">
                <div class="btn-group">
                    <button  @click="save" class="btn btn-primary">
                        Export PDF
                    </button>
                    <router-link  :to="`${resource}/show`"
                        class="btn" >
                        GO EXCEL
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
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/client_payments_report/create`,
            'edit': `/api/client_payments_report/${to.params.id}/edit`,
            'clone': `/api/client_payments_report/${to.params.id}/edit?mode=clone`,
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
                resource: '/client_payments_report',
                store: '/api/client_payments_report',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created Warehouse!',
                currencyURL: '/api/search/currencies',
                productURL: '/api/search/products',
                clientURL: '/api/search/clients',
                warehouseURL: '/api/search/warehouses',
                uomURL: '/api/search/uom',
                employeeURL: '/api/search/employees',
                categoryURL: '/api/search/categories'
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/client_payments_report/${this.$route.params.id}`
                this.message = 'Invoice Report Created Successfully !'
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
             onWarehouseUpdate(e) {
                const warehouse = e.target.value
                // vendor
               
                Vue.set(this.form, 'warehouse_id', warehouse.id)
                Vue.set(this.form, 'warehouse', warehouse)
            },
            onUomUpdate(e) {
                const uom = e.target.value

                Vue.set(this.form, 'uom_id', uom.id)
                Vue.set(this.form, 'uom', uom)
            },
            onEmployeeUpdate(e) {
                const employee = e.target.value

                Vue.set(this.form, 'employee_id', employee.id)
                Vue.set(this.form, 'employee', employee)
            },
            onCategoryUpdate(e) {
                const category = e.target.value

                Vue.set(this.form, 'category_id', category.id)
                Vue.set(this.form, 'category', category)
            },
            onClientUpdate(e) {
                const client = e.target.value

                // vendor
                Vue.set(this.form, 'client', client)
                Vue.set(this.form, 'client_id', client.id)
            },
            onProductUpdate(e) {
                const product = e.target.value

                // vendor
                Vue.set(this.form, 'product', product)
                Vue.set(this.form, 'product_id', product.id)
            },
            save() {
                this.submit((data) => {
                    this.success()
                    // this.$router.push(`${this.resource}/${data.id}`)
                    window.location.href = `/docs${this.resource}/${this.$route.params.id}`
                    //c/onsole.log(`/docs${resource}/${$route.params.id}`);x
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
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`client_payments_report ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
