<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Custom Report -- Warehouse</span>
            </div>
            <div class="panel-body">
                <div class="col col-12">
                     <div class="row">
                        <div class="col col-4" style="">
                            <div class="form-group">
                                <label>Warehouse
                                </label>
                                <typeahead :initial="form.warehouse" :trim="80"
                                    :url="warehouseURL"
                                    @input="onWarehouseUpdate">
                                </typeahead>
                                <error-text :error="error.warehouse_id"></error-text>
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
                    </div>
                    <div class="row">
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
                    </div>
                    <div class="row">
                        <div class="col col-2" style="">
                            <div class="form-group">
                                <label>UOM</label>
                                <typeahead :initial="form.uom"
                                    :url="uomURL"
                                    @input="onUomUpdate">
                                </typeahead>
                                <error-text :error="error.uom_id"></error-text>
                            </div>
                        </div>
                     </div>
                     <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>From Date</label>
                            <input type="date" class="form-control" v-model="form.from_date">
                            <error-text :error="error.from_date"></error-text>
                        </div>
                    </div>
               
                    <div class="col col-3">
                        <div class="form-group">
                            <label>To Date</label>
                            <input type="date" class="form-control" v-model="form.to_date">
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
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/warehouses_report_criteria/create`,
            'edit': `/api/warehouses_report_criteria/${to.params.id}/edit`,
            'clone': `/api/warehouses_report_criteria/${to.params.id}/edit?mode=clone`,
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
                resource: '/warehouses_report_criteria',
                store: '/api/warehouses_report_criteria',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created Warehouse!',
                currencyURL: '/api/search/currencies',
                productURL: '/api/search/items',
                vendorURL: '/api/search/vendors',
                warehouseURL: '/api/search/warehouses',
                uomURL: '/api/search/uom',
                categoryURL: '/api/search/categories'
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/warehouses_report_criteria/${this.$route.params.id}`
                this.message = 'You have successfully updated Warehouse!'
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
            onCategoryUpdate(e) {
                const category = e.target.value

                Vue.set(this.form, 'category_id', category.id)
                Vue.set(this.form, 'category', category)
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
                this.$title.set(`Warehouses_report_criteria ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
