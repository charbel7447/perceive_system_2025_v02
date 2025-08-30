<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Custom Report -- Products</span>
            </div>
            <div class="panel-body">
                <div class="col col-12">
                    <div class="row">
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Code
                                </label>
                                <input class="form-control" type="text" v-model="form.code" />
                                <error-text :error="error.code"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Product
                                </label>
                                <typeahead :initial="form.product" :trim="80"
                                    :url="productURL"
                                    @input="onProductUpdate">
                                </typeahead>
                                <error-text :error="error.product_id"></error-text>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Supplier
                                </label>
                                <typeahead :initial="form.vendor" :trim="80"
                                    :url="vendorURL"
                                    @input="onVendorUpdate">
                                </typeahead>
                                <error-text :error="error.vendor_id"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Status
                                </label>
                                <select class="form-control" v-model="form.status">
                                    <option :value="1">Active</option>
                                    <option :value="2">InActive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Category
                                </label>
                                <typeahead :initial="form.category" :trim="80"
                                    :url="categoryURL"
                                    @input="onCategoryUpdate">
                                </typeahead>
                                <error-text :error="error.category_id"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Sub Category
                                </label>
                                <typeahead :initial="form.subcategory" :trim="80"
                                    :url="subcategoryURL"
                                    @input="onSubCategoryUpdate">
                                </typeahead>
                                <error-text :error="error.subcategory_id"></error-text>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>From Qty
                                </label>
                                <input class="form-control" type="text" v-model="form.from_qty" />
                                <error-text :error="error.from_qty"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>To Qty
                                </label>
                                <input class="form-control" type="text" v-model="form.to_qty" />
                                <error-text :error="error.to_qty"></error-text>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>From Purchase Price
                                </label>
                                <input class="form-control" type="text" v-model="form.from_p_price" />
                                <error-text :error="error.from_p_price"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>To Purchase Price
                                </label>
                                <input class="form-control" type="text" v-model="form.to_p_price" />
                                <error-text :error="error.to_p_price"></error-text>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>From Sale Price
                                </label>
                                <input class="form-control" type="text" v-model="form.from_price" />
                                <error-text :error="error.from_price"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>To Sale Price
                                </label>
                                <input class="form-control" type="text" v-model="form.to_price" />
                                <error-text :error="error.to_price"></error-text>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Filed 1
                                </label>
                                <input  type="text" class="form-control" v-model="form.field1">
                                <error-text :error="error.field1"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Filed 2
                                </label>
                                <input  type="text" class="form-control" v-model="form.field2">
                                <error-text :error="error.field2"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Filed 3
                                </label>
                                <input  type="text" class="form-control" v-model="form.field3">
                                <error-text :error="error.field3"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Filed 4
                                </label>
                                <input  type="text" class="form-control" v-model="form.field4">
                                <error-text :error="error.field4"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Filed 5
                                </label>
                                <input  type="text" class="form-control" v-model="form.field5">
                                <error-text :error="error.field5"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Filed 6
                                </label>
                                <input  type="text" class="form-control" v-model="form.field6">
                                <error-text :error="error.field6"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Filed 7
                                </label>
                                <input  type="text" class="form-control" v-model="form.field7">
                                <error-text :error="error.field7"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Filed 8
                                </label>
                                <input  type="text" class="form-control" v-model="form.field8">
                                <error-text :error="error.field8"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Filed 9
                                </label>
                                <input  type="text" class="form-control" v-model="form.field9">
                                <error-text :error="error.field9"></error-text>
                            </div>
                        </div>
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Filed 10
                                </label>
                                <input  type="text" class="form-control" v-model="form.field10">
                                <error-text :error="error.field10"></error-text>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display: none;">
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
                    <button @click="save" class="btn btn-primary">
                       <!-- Export PDF -->
                       Export Excel
                    </button>
                    <!-- <button :disabled="isProcessing" v-if="!isEdit"
                        @click="saveAndNew" class="btn btn-secondary">
                        Save and New
                    </button> -->
                    <router-link  :to="`${resource}/create`"
                        class="btn" >
                        Go PDF
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
            'create': `/api/products_report/create`,
            'edit': `/api/products_report/${to.params.id}/edit`,
            'clone': `/api/products_report/${to.params.id}/edit?mode=clone`,
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
                resource: '/products_report',
                store: '/api/products_report',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created Warehouse!',
                currencyURL: '/api/search/currencies',
                productURL: '/api/search/price_changes_products',
                vendorURL: '/api/search/vendors',
                warehouseURL: '/api/search/warehouses',
                uomURL: '/api/search/uom',
                employeeURL: '/api/search/employees',
                categoryURL: '/api/search/categories',
                subcategoryURL: '/api/search/subcategoriesall',
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/products_report/${this.$route.params.id}`
                this.message = 'You have successfully created the report!'
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
            onSubCategoryUpdate(e) {
                const subcategory = e.target.value

                Vue.set(this.form, 'subcategory_id', subcategory.id)
                Vue.set(this.form, 'subcategory', subcategory)
            },
            

            onVendorUpdate(e) {
                const vendor = e.target.value

                // vendor
                Vue.set(this.form, 'vendor', vendor)
                Vue.set(this.form, 'vendor_id', vendor.id)
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
                    // window.location.href = `/docs${this.resource}/${this.$route.params.id}`
                    window.location.href = `/docs${this.resource}/excel/${this.$route.params.id}`
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
                this.$title.set(`products_report ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
