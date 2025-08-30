<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Aggregation</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>
                                Item Code
                                <small>(Auto Generated)</small>
                            </label>
                            <span class="form-control">{{form.code}}</span>
                        </div>
                        <div class="form-group">
                            <label>Quantity after Aggregation</label>
                            <input type="text" required class="form-control" v-model="form.current_stock" />
                            <error-text :error="error.current_stock"></error-text>
                        </div>
                        <div class="form-group" style="display:none;">
                            <label>
                                minimum_stock
                            </label>
                            <input required type="text" class="form-control" v-model="form.minimum_stock">
                            <error-text :error="error.minimum_stock"></error-text>
                        </div>
                         <div class="form-group" style="display:none;">
                            <label>
                                Unit Price
                            </label>
                            <input type="text" class="form-control" v-model="form.unit_price">
                            <error-text :error="error.unit_price"></error-text>
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
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea required class="form-control" v-model="form.description">
                            </textarea>
                            <error-text :error="error.description"></error-text>
                        </div>
                        <!-- <div class="form-group">
                            <label>Quantity after Aggregation</label>
                            <input type="text" required class="form-control" v-model="form.current_stock" />
                            <error-text :error="error.current_stock"></error-text>
                        </div> -->
                    </div>
                     <div class="col col-4">
                        <div class="form-group">
                            <label>UOM</label>
                            <typeahead :initial="form.uom"
                                :url="uomURL"
                                @input="onUomUpdate" required
                            >
                            </typeahead>
                            <input style="display: none;" type="text" class="form-control" v-model="form.uom_id">
                            <error-text :error="error.uom_id"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Warehouses</label>
                            <typeahead :initial="form.warehouse"
                                :url="warehouseURL"
                                @input="onWarehouseUpdate" required
                            >
                            </typeahead>
                            <error-text :error="error.warehouse_id"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <typeahead :initial="form.category"
                                :url="categoryURL"
                                @input="onCategoryUpdate" required
                            >
                            </typeahead>
                            <error-text :error="error.category_id"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Sub Category
                            </label>
                            <typeahead :initial="form.sub_category"
                                :url="subcategoryURL"
                                :params="{category_id: form.category_id}"
                                @input="onSubCategoryUpdate" required
                            >
                            </typeahead>
                            <error-text :error="error.sub_category_id"></error-text>
                        </div>
                          <div class="form-group" style="display:none">
                            <label>
                               Child Sub Category
                            </label>
                            <typeahead :initial="form.sub_sub_category"
                                :url="subsubcategoryURL"
                                :params="{sub_category_id: form.sub_category_id}"
                                @input="onSubSubCategoryUpdate" 
                            >
                            </typeahead>
                            <error-text :error="error.sub_sub_category_id"></error-text>
                        </div>
                    </div>
                    <div class="col col-4" style="display:none;">
                        <div class="form-group">
                            <strong>Inventory</strong>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="checkbox" v-model="form.has_inventory">
                                Track Inventory for this Item
                            </label>
                            <error-text :error="error.has_inventory"></error-text>
                        </div>
                    </div>
                </div>
                <hr>
                <p style="color:red;"><span><strong>Note:</strong>Bellow Products will be deleted after aggregation</span></p>
                <table class="item-table" v-if="form.items.length">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantities</th>
                            <th>U.O.M</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(v, index) in form.items">
                            <td :class="['width-5', errors(`items.${index}.product_id`)]">
                                <typeahead :initial="v.product" :trim="80"
                                    @input="onProductUpdated(v, index, $event)"
                                    :url="productURL" required
                                >
                                </typeahead>
                                <input style="display:non;" type="text" class="form-control" v-model="v.product_id">
                                <error-text :error="error[`items.${index}.product_id`]"></error-text>
                            </td>
                            <td :class="['width-2', errors(`items.${index}.current_stock`)]">
                                <input style="display:none;" type="text" class="form-control" v-model="v.current_stock">
                                <span class="form-control">{{v.current_stock}}</span>
                                <error-text :error="error[`items.${index}.current_stock`]"></error-text>
                            </td>
                            <td style="display:none;" :class="['width-2', errors(`items.${index}.product_code`)]">
                                <input  type="text" class="form-control" v-model="v.product_code">
                                <error-text :error="error[`items.${index}.product_code`]"></error-text>
                            </td>
                            <td style="display:none;" :class="['width-2', errors(`items.${index}.product_price`)]">
                                <input  type="text" class="form-control" v-model="v.product_price">
                                <error-text :error="error[`items.${index}.product_price`]"></error-text>
                            </td>
                            <td  style="display:none;" :class="['width-2', errors(`items.${index}.product_name`)]">
                                <input  type="text" class="form-control" v-model="v.product_name">
                                <error-text :error="error[`items.${index}.product_name`]"></error-text>
                            </td>
                            <td :class="['width-2', errors(`items.${index}.uom`)]">
                                <input style="display:none;" required type="text" class="form-control" v-model="v.uom_id">
                                <span class="form-control">{{v.uom}}</span>
                                <error-text :error="error[`items.${index}.uom`]"></error-text>
                            </td>
                            <td>
                                <button class="item-remove" @click="removeProduct(v, index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewProduct">
                                    Add Product
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div v-else>
                    <button class="btn btn-success btn-sm" @click="addNewProduct">
                        Add Product
                    </button>
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
            'create': `/api/products_aggregation/create`,
            'edit': `/api/products_aggregation/${to.params.id}/edit`,
            'clone': `/api/products_aggregation/${to.params.id}/edit?mode=clone`,
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
                form: {
                    items: [],
                },
                resource: '/products_aggregation',
                store: '/api/products_aggregation',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created product!',
                currencyURL: '/api/search/currencies',
                vendorURL: '/api/search/vendors',
                productURL: '/api/search/products_aggregation',
                uomURL: '/api/search/uom',
                categoryURL: '/api/search/categoriesall',
                warehouseURL: '/api/search/warehouses',
                subcategoryURL: '/api/search/subcategoriesall',
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/products_aggregation/${this.$route.params.id}`
                this.message = 'You have successfully updated product!'
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
            removeProduct(v, index) {
                this.form.items.splice(index, 1)
            },
            onProductUpdated(v, index, e) {
                const product = e.target.value

                // product
                Vue.set(this.form.items[index], 'product', product)
                Vue.set(this.form.items[index], 'product_code', product.code)
                Vue.set(this.form.items[index], 'product_name', product.description)
                Vue.set(this.form.items[index], 'product_price', product.unit_price)
                Vue.set(this.form.items[index], 'product_id', product.id)

                // currency
                Vue.set(this.form.items[index], 'uom', product.uom.unit)
                Vue.set(this.form.items[index], 'uom_id', product.uom.unit)
                
                Vue.set(this.form.items[index], 'current_stock', product.current_stock)
            },
        
            addNewProduct() {
                this.form.items.push({
                    'product_id': null,
                    'product': null,
                    'current_stock': 0,
                    'uom': 0,
                   
                })
            },
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
            onCurrencyUpdate(e) {
                const currency = e.target.value

                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency', currency)
            },
            onUomUpdate(e) {
                const uom = e.target.value

                Vue.set(this.form, 'uom_id', uom.unit)
                Vue.set(this.form, 'uom', uom)
            },
            onWarehouseUpdate(e) {
                const warehouse = e.target.value

                Vue.set(this.form, 'warehouse_id', warehouse.id)
                Vue.set(this.form, 'warehouse', warehouse)
            },
            onCategoryUpdate(e) {
                const category = e.target.value

                Vue.set(this.form, 'category_id', category.id)
                Vue.set(this.form, 'category', category)
            },
            onSubCategoryUpdate(e) {
                const sub_category = e.target.value
                Vue.set(this.form, 'sub_category_id', sub_category.id)
                Vue.set(this.form, 'sub_category', sub_category)
                Vue.set(this.form, 'sub_category_code', sub_category.number)
               
            },
            onSubSubCategoryUpdate(e) {
                const sub_sub_category = e.target.value
                Vue.set(this.form, 'sub_sub_category_id', sub_sub_category.id)
               Vue.set(this.form, 'sub_sub_category', sub_sub_category)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Product ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
