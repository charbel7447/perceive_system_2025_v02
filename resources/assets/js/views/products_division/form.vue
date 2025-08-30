<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Product Division/Addition</span>
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
                                Quantity per U.O.M
                            </label>
                            <input v-if="form.current_stock > 0" type="text" class="form-control" v-model="form.current_stock">
                            <label style="color:red;" v-if="form.current_stock == 0">
                                <i class="fa fa-exclamation-triangle">
                                <strong>&nbsp;&nbsp;&nbsp;Mode Error, No More Quanity for this product</strong> </i>
                            </label>
                            <label style="color:red;" v-if="form.current_stock < 0">
                                <i class="fa fa-exclamation-triangle">
                                <strong>&nbsp;&nbsp;&nbsp;Mode Error, No More Quanity for this product</strong> </i>
                            </label>
                            <error-text :error="error.current_stock"></error-text>
                        </div>

                        

                        <div class="form-group">
                            <label>
                                From U.O.M 
                            </label>
                            <span class="form-control" style="color:red;">
                                 {{form.uom.unit}}
                                 <input style="display: none;" type="text" class="form-control" v-model="form.unit">
                            </span>
                        </div>

                       
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                             <label>
                                &nbsp;
                            </label>
                             <span style="line-height: 32px;"> &nbsp; &nbsp;<br></span>
                        </div>

                        <div class="form-group">
                            <label>
                                To Quantity per {{form.to_uom_unit}}
                            </label>
                            <input  type="text" class="form-control" v-model="form.to_current_stock">
                            <label style="color:red;" v-if="form.to_current_stock <= 0">
                                <i class="fa fa-exclamation-triangle">
                                <strong>&nbsp;&nbsp;&nbsp;Mode Error</strong> </i>
                            </label>
                            <error-text :error="error.to_current_stock"></error-text>
                        </div>

                         <div class="form-group">
                            <label>
                                To U.O.M
                            </label>
                            <typeahead required :initial="form.to_uom"
                                :url="uomURL"
                                @input="onUomUpdate"
                            >
                            </typeahead>
                            <input  style="display: none;" type="text" class="form-control" v-model="form.to_uom_id">
                            <error-text :error="error.to_uom_id"></error-text>
                        </div>
                    </div>
                     <div class="col col-4">
                        <div class="form-group">
                             <label>
                                Result:
                            </label>
                             <span class="form-control">
                                 {{form.current_stock}} {{form.uom.unit}} = {{form.to_current_stock}} / {{form.to_uom_unit}}
                             </span>
                        </div>
                         <div class="form-group">
                             <label>
                                Result:
                            </label>
                             <span class="form-control">
                                 Each <strong style="color:red;">{{form.to_uom_unit}}</strong> will be considered as new Product
                             </span>
                        </div>

                        <div class="form-group">
                             <label style="color:red;">
                                Mode:
                            </label>
                             <span class="form-control">
                                <strong v-if="form.current_stock < form.to_current_stock">Product Division</strong>
                                <strong v-if="form.current_stock > form.to_current_stock">Addition Product</strong>
                                <strong v-if="form.current_stock == form.to_current_stock">Change U.O.M Only</strong>
                             </span>
                        </div>

                     </div>
                    <hr>
                        
                    
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
            'create': `/api/products_division/create`,
            'edit': `/api/products_division/${to.params.id}/edit`,
            'clone': `/api/products_division/${to.params.id}/edit?mode=clone`,
            'division': `/api/products_division/${to.params.id}/edit?mode=division`,
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
                resource: '/products_division',
                store: '/api/products_division',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created  products_division !',
                currencyURL: '/api/search/currencies',
                uomURL: '/api/search/uom',
                categoryURL: '/api/search/categories',
                warehouseURL: '/api/search/warehouses'
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/products_division/${this.$route.params.id}`
                this.message = 'You have successfully updated products_division !'
                this.method = 'PUT'
                this.title = 'Edit'
            }
            if(this.mode === 'division') {
                this.store = `/api/products_division/${this.$route.params.id}`
                this.message = 'You have successfully division items !'
                this.method = 'PUT'
                this.title = 'division'
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
                const to_uom = e.target.value

                Vue.set(this.form, 'to_uom_id', to_uom.unit)
                Vue.set(this.form, 'to_uom_unit', to_uom.unit)
                Vue.set(this.form, 'to_uom', to_uom)
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
