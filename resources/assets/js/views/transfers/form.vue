<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Transfer Product</span>
            </div>
            <div class="panel-heading">
                <div class="col-md-12" style="float: left;width: 100%;">
                    <strong>Note:</strong>
                    <p>* If you transfer all quantity, Same Code and same barcode will be placed</p>
                    <p>* Same Vendor, Category, Price placed for both</p>
                </div>
                <div class="col-md-12" style="float: left;width: 100%;">
                    <strong>Note:</strong> 
                    <p>* If you transfer partial quantity, New Code and new barcode will be placed for 
                    the new product created, old barcode and Item code will be the same with remain quantity</p>
                </div>
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
                            <label>
                                Name
                            </label>
                            <input type="text" class="form-control" v-model="form.description" />
                        </div>
                        <div class="form-group">
                            <label>
                                Quantity to Transfer
                            </label>
                            <input v-if="form.current_stock > 0" type="text" class="form-control" v-model="form.current_stock">
                            <label style="color:red;" v-if="form.current_stock == 0">
                                <i class="fa fa-exclamation-triangle">
                                <strong>&nbsp;&nbsp;&nbsp;Mode Error, No More Quanity for this product</strong> </i>
                            </label>
                            <error-text :error="error.current_stock"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                From Warehouse 
                            </label>
                            <span class="form-control" style="color:red;">
                                 {{form.warehouse.number}} - {{form.warehouse.name}}
                            </span>
                        </div>

                        <div class="form-group">
                            <label>
                                To Warehouse
                            </label>
                            <typeahead required :initial="form.to_warehouse"
                                :url="warehouseURL"
                                @input="onWarehouseUpdate"
                            >
                            </typeahead>
                            <input style="display: none;" type="text" class="form-control" v-model="form.to_warehouse_id" />
                            <error-text :error="error.to_warehouse_id"></error-text>
                        </div>
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
            'create': `/api/transfers/create`,
            'edit': `/api/transfers/${to.params.id}/edit`,
            'clone': `/api/transfers/${to.params.id}/edit?mode=clone`,
            'transfer': `/api/transfers/${to.params.id}/edit?mode=transfer`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        components: { ErrorText, Typeahead, Spinner },
        mixins: [ form ],
        data () {
            return {
                 form: {
                    items: [],
                },
                resource: '/transfers',
                store: '/api/transfers',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created  transfers !',
                currencyURL: '/api/search/currencies',
                uomURL: '/api/search/uom',
                categoryURL: '/api/search/categories',
                warehouseURL: '/api/search/warehouses'
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/transfers/${this.$route.params.id}`
                this.message = 'You have successfully updated transfers !'
                this.method = 'PUT'
                this.title = 'Edit'
            }
            if(this.mode === 'transfer') {
                this.store = `/api/transfers/${this.$route.params.id}`
                this.message = 'You have successfully transfer items !'
                this.method = 'PUT'
                this.title = 'transfer'
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
            removeWarehouse(v, index) {
                this.form.items.splice(index, 1)
            },
            onWarehouseUpdate(e) {
                const to_warehouse = e.target.value
                // vendor
                Vue.set(this.form, 'to_warehouse', to_warehouse)
                Vue.set(this.form, 'to_warehouse_id', to_warehouse.id)
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

                Vue.set(this.form, 'uom_id', uom.id)
                Vue.set(this.form, 'uom', uom.unit)
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
