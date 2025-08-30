<template>
    <div v-if="show ">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Damaged List</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" class="form-control" v-model="form.date">
                            <error-text :error="error.date"></error-text>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>
                                Number
                                <small>(Auto Generated)</small>
                            </label>
                            <span class="form-control">{{form.number}}</span>
                        </div>
                    </div>
                    
                </div>
                <hr>
                <table class="item-table" style="margin: 20px 0 0 0;">
                    <thead>
                        <tr>
                            <th>Main Category</th>
                            <th>Sub Category</th>
                            <th>Item Description</th>
                            <th><strong>Transfer Quantity</strong></th>
                            <th><strong>UOM</strong></th>
                            <th><strong class="red">Warehouse</strong></th>
                        </tr>
                      
                    </thead>
                    <tbody>
                        <template v-for="(item, index) in form.items">
                            
                        <tr  :class="{ unapproved_line: item.approved_line == 0 }" style="">
                             <td  :class="[errors(`items.${index}.category_id`)]">
                            <typeahead :initial="item.category"
                                :url="categoryURL"
                                @input="onCategoryUpdate(item, index, $event)"
                            >
                            </typeahead>
                            <error-text :error="error.category_id"></error-text>
                        </td>
                         <td class="" >
                            <typeahead :initial="item.sub_category"
                                :url="subcategoryURL"
                                :params="{category_id: item.category_id}"
                                @input="onSubCategoryUpdate(item, index, $event)"
                            >
                            </typeahead>
                            <error-text :error="error.sub_category_id"></error-text>
                        </td>
                            <td :class="['width-4', errors(`items.${index}.product_id`)]">
                                <typeahead :initial="item.product" :trim="50"
                                :params="{sub_category_id: item.sub_category_id}"
                                    @input="onProductUpdated(item, index, $event)"
                                    :url="productURL5"
                                >
                                </typeahead>
                                <error-text :error="error[`items.${index}.product_id`]"></error-text>
                            </td>
                            <td :class="['width-1', errors(`items.${index}.transfer_qty`)]" >
                                <input type="text" class="form-control" v-model="item.transfer_qty">
                                <error-text :error="error[`items.${index}.transfer_qty`]"></error-text>
                            </td>
                            <td :class="['width-2', errors(`items.${index}.uom_id`)]" >
                                <typeahead :initial="item.uom" :trim="50"
                                    :params="{uom_id: form.uom_id}"
                                    @input="onUomUpdate(item, index, $event)"
                                    :url="uomURL"
                                >
                                </typeahead>
                                <error-text :error="error[`items.${index}.uom_id`]"></error-text>
                            </td>
                            <td :class="[errors(`items.${index}.employee_id`)]">
                                <span class="form-control">Damaged Warehouse</span>
                                 
                            </td>
                            <td>
                                <button class="item-remove" @click="removeProduct(item, index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                     </template>
                    </tbody>
                    <tfoot>
                       
                        <tr>
                            <td class="item-footer" colspan="1" v-if="user.id == form.manager_id || user.id == 1"></td>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewLine">
                                    Add new line
                                </button>
                            </td>
                            <td class="item-footer" colspan="5"></td>
                        </tr>
                        <tr>
                            <td class="item-footer" colspan="2" v-if="user.id == form.manager_id || user.id == 1"></td>
                            <td class="item-footer" colspan="1" v-if="user.id != form.manager_id || user.id != 1"></td>
                            <td class="item-footer" colspan="4">
                                <strong>Total Qty:</strong>
                            </td>
                            <td class="item-footer" colspan="1">
                                <strong class="item-dark form-control">
                                    {{totalQty}}
                                </strong>    
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <div class="row">
                     <div class="col col-8">
                       <div class="form-group">
                            <label>Note 
                                    <small>(Optional)</small>
                            </label>
                            <textarea class="form-control" v-model="form.terms"></textarea>
                            <error-text :error="error.terms"></error-text>
                        </div>
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
    <div v-else>
    <div class="col col-12" v-if="user.is_transfer_edit != 1" style="margin: 25% auto;">
        <p style="color:red;text-align:center;"><strong>You don't Have Permission, Contact your system administrator</strong></p>
    </div></div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import ErrorText from '../../components/form/ErrorText.vue'
    import Typeahead from '../../components/form/Typeahead.vue'
    import FileUpload from '../../components/form/FileUpload.vue'
    import Spinner from '../../components/loading/Spinner.vue'
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/damaged_deteriorate/create`,
            'edit': `/api/damaged_deteriorate/${to.params.id}/edit`,
            'clone': `/api/damaged_deteriorate/${to.params.id}/edit?mode=clone`
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        computed: {
            base_currency() {
                return window.apex.base_currency
            },
            user() {
                return window.apex.user
            },
            totalQty() {
                return this.form.items.reduce((carry, item) => {
                        return carry + Number(item.transfer_qty)
                }, 0)
            }
        },
        components: { ErrorText, Typeahead, Spinner, FileUpload },
        mixins: [ form ],
        data () {
            return {
                resource: '/damaged_deteriorate',
                store: '/api/damaged_deteriorate',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created a Transfer!',
                currencyURL: '/api/search/currencies',
                productURL: '/api/search/products',
                // productURL5: '/api/search/products5',
                productURL5: '/api/search/products',
                vendorURL: '/api/search/vendors',
                deliveryconditionURL: '/api/search/deliverycondition',
                paymentconditionURL: '/api/search/paymentcondition',
                uomURL: '/api/search/uom',
                employeeURL: '/api/search/employees',
                priorityURL: '/api/search/priority',
                categoryURL: '/api/search/categories',
                warehouseURL: '/api/search/warehouses',
                subcategoryURL: '/api/search/subcategories',
                subsubcategoryURL: '/api/search/subsubcategories',
                productPriceURL: '/api/search/productsItemPrice',
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/damaged_deteriorate/${this.$route.params.id}?_method=PUT`
                this.message = 'You have successfully updated Transfer!'
                this.method = 'POST'
                this.title = 'Edit'
               
            } else if(this.mode === 'clone') {
                this.store = `/api/damaged_deteriorate`
                this.message = 'You have successfully cloned Transfer!'
                this.method = 'POST'
                this.title = 'Clone'
               
            }
          
        },
        beforeRouteEnter(to, from, next) {
            get(initializeUrl(to), to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false

            get(initializeUrl(to), to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            // onDocument(e) {
            //     Vue.set(this.$data.form, 'document', e.target.value)
            // },
            removeProduct(item, index) {
                if(this.form.items.length > 1) {
                    this.form.items.splice(index, 1)
                }
            },
            uncheckProduct(item, index) {
                const uncheck = 0;
                if(this.form.items.length > 1) {
                     Vue.set(this.form.items[index], 'approved_line', uncheck)
                     Vue.set(this.form.items[index], 'transfer_qty', '0')
                }
            },
            checkProduct(item, index) {
                const check = 1;
                if(this.form.items.length > 1) {
                     Vue.set(this.form.items[index], 'approved_line', check)
                     Vue.set(this.form.items[index], 'transfer_qty', '1')
                }
            },
            addNewLine() {
                this.form.items.push({
                    'category_id': null,
                    'category': null,
                    'sub_category_id': null,
                    'sub_category': null,
                    'product_id': null,
                    'product': null,
                    'employee': null,
                    'employee_id': null,
                    'transfer_qty': 1,
                    'qty_on_hand': 1,
                    'uom': null,
                    'uom_id': null,
                })
            },
            onUomUpdate(v, index, e) {
                const uom = e.target.value

                Vue.set(this.form.items[index], 'uom_id', uom.id)
                Vue.set(this.form.items[index], 'uom', uom)
            },
            onProductUpdated(item, index, e) {
                const product = e.target.value

                // product
                Vue.set(this.form.items[index], 'product', product)
                Vue.set(this.form.items[index], 'product_id', product.id)

                // unit price
                Vue.set(this.form.items[index], 'unit_price', product.unit_price)
                Vue.set(this.form.items[index], 'vendor_reference', product.reference)

                //uom
                Vue.set(this.form.items[index], 'uom', product.uom)
                Vue.set(this.form.items[index], 'uom_id', product.uom_id)

                Vue.set(this.form.items[index], 'qty_on_hand', product.qty_on_hand)
                
                Vue.set(this.form.items[index], 'tax_rate', product.tax_rate)
                Vue.set(this.form.items[index], 'tax_name', product.tax_name)

                 // taxes
                Vue.set(this.form.items[index], 'taxes', product.taxes)
            },
            onEmployeeUpdate(v, index, e) {
                const employee = e.target.value

                Vue.set(this.form.items[index], 'employee', employee)
                Vue.set(this.form.items[index], 'employee_id', employee.id)
              
            },
            onCategoryUpdate(v, index, e) {
                const category = e.target.value

                Vue.set(this.form.items[index], 'category_id', category.id)
                Vue.set(this.form.items[index], 'category', category)
            },
            onSubCategoryUpdate(v, index, e) {
                const sub_category = e.target.value
                Vue.set(this.form.items[index], 'sub_category_id', sub_category.id)
               Vue.set(this.form.items[index], 'sub_category', sub_category)
            },
            onSubSubCategoryUpdate(v, index, e) {
                const sub_sub_category = e.target.value
                Vue.set(this.form.items[index], 'sub_sub_category_id', sub_sub_category.id)
               Vue.set(this.form.items[index], 'sub_sub_category', sub_sub_category)
            },
            save() {
                this.submitMultipartForm(this.form, (data) => {
                    this.success()
                    this.$router.push(`${this.resource}/${data.id}`)
                })
            },
            saveAndNew() {
                this.submitMultipartForm(this.form, (data) => {
                    const id = Math.random().toString(36).substring(7)
                    this.endProcessing()
                    this.success()
                    this.$router.push(`${this.resource}/create?new=${id}`)
                })
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Transfer ${this.title}`)
                this.$bar.finish()
                this.show = true
            },
        }
    }
</script>
<style>
.red {color: red}
.unapproved_line {
    text-decoration: line-through;
}
.approved_line {
    text-decoration: none;
}
</style>