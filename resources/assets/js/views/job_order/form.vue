<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Job Order</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-2">
                        <div class="form-group">
                            <label>
                                Item Code
                                <small>(Auto Generated)</small>
                            </label>
                            <!-- <span class="form-control">FP{{form.code}}{{form.type_code}}</span> -->
                            <span class="form-control">{{form.code}}</span>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>
                                Job Description
                            </label>
                            <input class="form-control"  type="text" v-model="form.description" />
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="form-group">
                            <label>U.O.M</label>
                            <typeahead :initial="form.uom"
                                :url="uomURL"
                                @input="onUomUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.uom_id"></error-text>
                        </div>
                    </div>
                    <!-- {{form}} -->
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Sales Order</label>
                            <typeahead :initial="form.sales_order"
                                :url="salesOrderURL"
                                @input="onOrderUpdate"
                            >
                         
                            </typeahead>
                            <error-text :error="error.sales_order_id"></error-text>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-3">
                        <div class="form-group">
                            <label>Customer</label>
                             <input style="display:none;" type="text" v-model="form.client_id" />
                            <span class="form-control">{{form.customer}}</span>
                        </div>
                    </div>
                    <div class="col col-3">
                        <table class="items">
                            <thead>
                                <tr>
                                    <th class="ac">#</th>
                                    <th class="ac">Code</th>
                                    <th class="ac">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="fp in form.items">
                                    <td class="ac">
                                        <input type="checkbox" :v-model="form.product_id" :value="fp.product_code.id"  />
                                    </td>
                                    <td class="ac">
                                        {{fp.product_code.generated_code}}
                                    </td>
                                    <td class="ac">
                                        {{fp.product_code.description}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
            'create': `/api/job_order/create`,
            'edit': `/api/job_order/${to.params.id}/edit`,
            'clone': `/api/job_order/${to.params.id}/edit?mode=clone`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        computed: {
            user() {
                return window.apex.user
            },
            company_type() {
                return window.apex.company_type
            },
        },
        components: { ErrorText, Typeahead, Spinner },
        mixins: [ form ],
        data () {
            return {
                form: {
                    items: [],
                    values: [],
                    taxes: []
                },
                resource: '/job_order',
                store: '/api/job_order',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created product!',
                currencyURL: '/api/search/currencies',
                clientURL: '/api/search/clients',
                attributesURL: '/api/search/attributes',
                attributesValueURL: '/api/search/attributesvalue',
                uomURL: '/api/search/uom',
                typeURL: '/api/search/finished_product_type',
                categoryURL: '/api/search/categoriesall',
                warehouseURL: '/api/search/warehouses',
                subcategoryURL: '/api/search/subcategoriesall',
                subsubcategoryURL: '/api/search/subsubcategories',
                rawMaterialUrl: '/api/search/raw_material_type',
                salesOrderURL: '/api/search/sales_orders',
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/job_order/${this.$route.params.id}`
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
            removeClient(v, index) {
                this.form.items.splice(index, 1)
            },
            removeTax(v, index) {
                this.form.taxes.splice(index, 1)
            },
            removeMaterial(v, index) {
                this.form.materials.splice(index, 1)
            },
            removeAttribute(v, index) {
                this.form.values.splice(index, 1)
            },

            onClientUpdated(v, index, e) {
                const client = e.target.value

                // client
                Vue.set(this.form.items[index], 'client', client)
                Vue.set(this.form.items[index], 'client_id', client.id)

                // currency
                Vue.set(this.form.items[index], 'currency', client.currency)
                Vue.set(this.form.items[index], 'currency_id', client.currency.id)
            },
            onAttributeUpdated(x, index, e) {
               const attribute = e.target.value

                // attribute
                Vue.set(this.form.values[index], 'attribute', attribute)
                Vue.set(this.form.values[index], 'attribute_id', attribute.id)

                // attribute value
                Vue.set(this.form.values[index], 'attributes', attribute.items)
                
            },
            onMaterialUpdated(rm, index, e) {
               const material = e.target.value
                // materials
                Vue.set(this.form.materials[index], 'material', material)
                Vue.set(this.form.materials[index], 'material_id', material.id)
            },
            
            onClientCurrencyUpdated(v, index, e) {
                const currency = e.target.value

                // currency
                Vue.set(this.form.items[index], 'currency', currency)
                Vue.set(this.form.items[index], 'currency_id', currency.id)
            },
            addNewClient() {
                this.form.items.push({
                    'client_id': null,
                    'client': null,
                    'reference': 'reference',
                    'price': 0,
                    'tax_name': 'Vat',
                    'tax_rate': 11,
                    'currency_id': null,
                    'currency': null
                })
            },
            addNewTax() {
                this.form.taxes.push({
                    'name': 'Vat',
                    'rate': 11,
                    'tax_authority': 'tax_authority'
                })
            },

            addNewMaterial() {
                this.form.materials.push({
                    'name': 'Vat',
                    'percentage': 100,
                })
            },
            addNewAttribute() {
                this.form.values.push({
                    'attribute_id': null,
                    'attribute': null,
                    'attribute_value': null,
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
            onOrderUpdate(e) {
                const sales_order = e.target.value

                Vue.set(this.form, 'sales_order_id', sales_order.id)
                Vue.set(this.form, 'sales_order', sales_order)
                Vue.set(this.form, 'customer', sales_order.client.person)
                Vue.set(this.form, 'client_id', sales_order.client.id)
                Vue.set(this.form, 'items', sales_order.items)
            },
            onCurrencyUpdate(e) {
                const currency = e.target.value

                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency', currency)
            },
            onUomUpdate(e) {
                const uom = e.target.value

                Vue.set(this.form, 'uom_id', uom.id)
                Vue.set(this.form, 'uom', uom)
            },
            onWarehouseUpdate(e) {
                const warehouse = e.target.value

                Vue.set(this.form, 'warehouse_id', warehouse.id)
                Vue.set(this.form, 'warehouse', warehouse)
            },
            onTypeUpdate(e) {
                const type = e.target.value

                Vue.set(this.form, 'type_id', type.id)
                Vue.set(this.form, 'type', type)
                Vue.set(this.form, 'type_code', type.code)
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
<style>
    .red {color: red;}
    pre {
        font-family: sans-serif;
    }

    table {
        border-spacing: 0;
        width: 100%;
        border-collapse: collapse;
    }
    th,
    td{
        font-weight: normal;
        vertical-align: top;
        text-align: left;
    }
    .header-logo {
        width: 25%;
        text-align: right;
    }
    .header-company_name {
        font-size: 18pt;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .content {

    }
    .content-title {
        margin-bottom: 20px;
        padding: 5px;
        text-align: center;
        font-size: 12pt;
        font-weight: bold;
        border: 0.1mm solid #dedede;
    }

    .document-blue {
        color: #3aa3e3;
    }

    .document-orange {
        color: #FF9800;
    }

    .document-red {
        color: #E75650;
    }

    .document-blue_light {
        color: #48606f;
    }
    .document-green {
        color: #66bb6a;
    }
    .summary-address {
        width: 33.333%;
    }
    .summary-addressx {
        width: 33.333%;
    }
    .summary-empty {
        width: 33.333%;
    }
    .summary-info {
        width: 33.333%;
    }
    .info td {
        text-align: right;
    }
    .info td:nth-child(2n) {
        padding-left: 15px;
    }
    .items {
        /* margin-top: 20px; */
        border: 0.1mm solid #dedede;
    }
    .items thead th {
        padding: 6px 3px;
        background: #dedede;
        border: 0.1mm solid #dedede;
    }
    .items tbody td {
        border: 0.1mm solid #dedede;
        padding: 3px;
    }

    .items tfoot td {
         background: #f1f1f1;
        border: 0.1mm solid #dedede;
        text-align: right;
        padding: 4px 3px;
    }
    .item-empty {

    }
    .ar {
        text-align: right;
    }
    .ac {
        text-align: center;
    }
    .terms {
        margin-top: 20px;
    }
    .terms-description {
        width: 70%;
    }
    .footer {
        text-align: center;
    }
</style>

    <style>
       .content {
       padding-top: -30px !important;
       }
       .header {
       position: fixed;
       left: 0;
       color: #777;
       text-align: right;
       }
       .footer {
       position: fixed;
       left: 0;
       bottom: -20px;
       color: #777;
       width: 100%;
       text-align: center;
       }
    </style>