<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Product</span>
                <spinner v-if="isProcessing"></spinner>
                <div class="btn-group" v-else>
                    <button :disabled="isProcessing" @click="save" class="btn btn-primary">
                        Save
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
            <div class="panel-body">
                <div class="row">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>
                                Item Code
                                <small>(Auto Generated)</small>
                            </label>
                            <!-- <span class="form-control">FP{{form.code}}{{form.type_code}}</span> -->
                            <span class="form-control">FP{{form.code}}</span>
                            <span class="form-control">{{form.generated_code}}</span>
                        </div>
                        <div class="form-group" style="display:none">
                            <label>
                                minimum_stock
                            </label>
                            <input required type="text" class="form-control" v-model="form.minimum_stock">
                            <error-text :error="error.minimum_stock"></error-text>
                        </div>
                         <div class="form-group">
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

                            <div class="col col-3x">
                                <div class="form-group">
                                        <label>Comment:
                                            <small>(Optional)</small>
                                        </label>
                                        <textarea class="form-control" v-model="form.comment"></textarea>
                                </div>
                            </div>
                        
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea required class="form-control" v-model="form.description">
                            </textarea>
                            <error-text :error="error.description"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Manage Stock
                            </label>
                            <input type="text" class="form-control" v-model="form.qty_on_hand">
                            <error-text :error="error.qty_on_hand"></error-text>
                        </div>
                        
                    </div>
                     <div class="col col-4">
                        <div class="form-group">
                            <label>UOM</label>
                            <typeahead :initial="form.uom"
                                :url="uomURL"
                                @input="onUomUpdate" required
                            >
                            </typeahead>
                            <error-text :error="error.uom_id"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Warehouses</label>
                            <input type="text" class="form-control" v-model="form.warehouse"></input>
                        </div>
                        <div class="form-group">
                            <label>Product Type</label>
                            <typeahead :initial="form.type"
                                :url="typeURL"
                                @input="onTypeUpdate" required
                            >
                            </typeahead>
                            <error-text :error="error.type_id"></error-text>
                        </div>
                        <div class="row" style="display:none;">
                            <div class="col col-12">
                                <div class="form-group">
                                    <label>Upload Image</label>
                                    <file-upload @ready="onDocument"></file-upload>
                                </div>
                                <a v-if="form.document" :href="`/uploads/${form.document}`" target="_blank">
                                    <img class="panel-image" :src="`/uploads/${form.document}`" style="width: 150px;">
                                </a>
                            </div>
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
                <table class="item-table" v-if="form.taxes.length" style="">
                    <thead>
                        <tr>
                            <th>Tax Name</th>
                            <th>Rate</th>
                            <th>Tax Authority</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(v, index) in form.taxes">
                            <td :class="['width-4', errors(`items.${index}.name`)]">
                                <input disabled type="text" class="form-control" v-model="v.name">
                                <error-text :error="error[`items.${index}.name`]"></error-text>
                            </td>
                            <td :class="['width-2', errors(`items.${index}.rate`)]">
                                <input type="text" class="form-control" v-model="v.rate" required>
                                <error-text :error="error[`items.${index}.rate`]"></error-text>
                            </td>
                            <td :class="['width-6', errors(`items.${index}.tax_authority`)]">
                                <input type="text" class="form-control" v-model="v.tax_authority">
                                <error-text :error="error[`items.${index}.tax_authority`]"></error-text>
                            </td>
                            <td>
                                <button class="item-remove" @click="removeTax(v, index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot style="display:none">
                        <tr>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewTax">
                                    Add new Tax
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div v-else style="display:non">
                    <button class="btn btn-success btn-sm" @click="addNewTax">
                        Add Tax
                    </button>
                </div>

                <hr>
                <table class="item-table" v-if="form.values.length" >
                    <thead>
                        <tr>
                            <th>Attribute</th>
                            <th>value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(x, index) in form.values">
                            <td :class="['width-5', errors(`values.${index}.attribute_id`)]">
                                <typeahead :initial="x.attribute" :trim="80"
                                    @input="onAttributeUpdated(x, index, $event)"
                                    :url="attributesURL"
                                >
                                </typeahead>
                                <error-text :error="error[`values.${index}.attribute_id`]"></error-text>
                            </td>
                            <td>
                             <select class="form-control" v-model="form.values[index].attribute_value">
                             <option v-if="isEdit">{{ form.values[index].attribute_value}}</option>
                              <option id="attribute_value" name="attribute_value"  v-for="a in x.attributes">{{a.attribute_value }}</option>
                            </select>
                            </td>
                            <td>
                                <button class="item-remove" @click="removeAttribute(v, index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewAttribute">
                                    Add new Attribute
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div v-else  style="display:non">
                    <button class="btn btn-success btn-sm" @click="addNewAttribute">
                        Add new Attribute
                    </button>
                </div>
                <hr>
                <table class="item-table" v-if="form.materials.length" style="">
                    <thead>
                        <tr>
                            <th>Material Type</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(rm, index) in form.materials">
                            <td :class="['width-4', errors(`items.${index}.material_id`)]">
                                <typeahead :initial="rm.material" :trim="80"
                                    @input="onMaterialUpdated(rm, index, $event)"
                                    :url="rawMaterialUrl" required
                                >
                                </typeahead>
                                <error-text :error="error[`items.${index}.vendor_id`]"></error-text>
                            <td :class="['width-2', errors(`items.${index}.rate`)]">
                                <input type="text" class="form-control" v-model="rm.percentage" required>
                                <error-text :error="error[`items.${index}.rate`]"></error-text>
                            </td>
                            <td>
                                <button class="item-remove" @click="removeMaterial(rm, index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot style="display:non">
                        <tr>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewMaterial">
                                    Add new Raw Material
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div v-else style="display:non">
                    <button class="btn btn-success btn-sm" @click="addNewMaterial">
                        Add Raw Material
                    </button>
                </div>
                <hr>
                <div class="item-table" v-if="form.machines.length" style="">
                    <div class="machine_table"  v-for="(mc, index) in form.machines">
                            <div class="colc" style="float: left;padding: 5px;width: 50px;">
                                <input style="background: #233c46;color: #fff;width: 100%;text-align: center;padding: 5px;" type="text" class="form-control" v-model="mc.machine_process_id" required > 
                            </div>
                            <div :class="['col col-4', errors(`items.${index}.machine_id`)]" style="float:left;padding:5px;">
                                <typeahead :initial="mc.machinex" :trim="80"
                                    @input="onMachineUpdated(mc, index, $event)"
                                    :url="machineUrl" required
                                >
                                </typeahead>
                                <error-text :error="error[`items.${index}.vendor_id`]"></error-text>
                            </div>
                            <div  class="col col-2" style="display: none;">
                                <input type="text" class="form-control" v-model="mc.machine" required > 
                            </div>
                            <div class="col col-2" style="float: left;">
                                <input type="text" class="form-control" v-model="mc.speed" required > 
                            </div>
                            <div class="col col-2" style="float:left;padding: 12px;">
                                <button class="item-remove" @click="removeMachine(mc, index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                            <br>
                            <div class="col col-3x">
                                <div class="form-group">
                                        <label>Note:
                                            <small>(Optional)</small>
                                        </label>
                                        <textarea style="height:70px;" class="form-control" v-model="mc.comment"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin:0 0 10px 0;">
                                <table class="item-table items" style="">
                                <thead>
                                    <tr>
                                        <th style="width:50px">ID#</th>
                                        <th>Setting Name</th>
                                        <th>Setting Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="set in mc.settings">
                                        <td style="width:50px">
                                            <input style="width: 50px;" type="text" class="form-control" v-model="set.settings_id" required>
                                            <error-text :error="error[`items.${index}.rate`]"></error-text>
                                        </td>
                                        <td :class="['width-7', errors(`items.${index}.rate`)]">
                                            <input type="text" class="form-control" v-model="set.settings_name" required>
                                            <error-text :error="error[`items.${index}.rate`]"></error-text>
                                        </td>
                                        <td :class="['width-5', errors(`items.${index}.rate`)]">
                                            <input type="text" class="form-control" v-model="set.settings_value" required>
                                            <error-text :error="error[`items.${index}.rate`]"></error-text>
                                        </td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                            
                          
                    <button class="btn btn-success btn-sm" @click="addNewMachine">
                        Add Machine
                    </button>
               </div></div>
                <div v-else>
                     <button class="btn btn-success btn-sm" @click="addNewMachine">
                        Add Machine
                    </button>
                </div>
                
                <hr>

                <table class="item-table" v-if="form.items.length">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Reference</th>
                            <th>Unit Price</th>
                            <!-- <th>Tax Name</th>
                            <th>Tax Rate</th> -->
                            <th>Currency</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(v, index) in form.items">
                            <td :class="['width-3', errors(`items.${index}.client_id`)]">
                                <typeahead :initial="v.client" :trim="80"
                                    @input="onClientUpdated(v, index, $event)"
                                    :url="clientURL" required
                                >
                                </typeahead>
                                <error-text :error="error[`items.${index}.client_id`]"></error-text>
                            </td>
                            <td :class="['width-2', errors(`items.${index}.reference`)]">
                                <input  type="text" class="form-control" v-model="v.reference">
                                <error-text :error="error[`items.${index}.reference`]"></error-text>
                            </td>
                            <td :class="['width-2', errors(`items.${index}.price`)]">
                                <input required type="text" class="form-control" v-model="v.price">
                                <error-text :error="error[`items.${index}.price`]"></error-text>
                            </td>
                            <!-- <td :class="['width-1', errors(`items.${index}.tax_name`)]">
                                <input type="text" class="form-control" v-model="v.tax_name">
                                <error-text :error="error[`items.${index}.tax_name`]"></error-text>
                            </td>
                            <td :class="['width-1', errors(`items.${index}.tax_rate`)]">
                                <input type="text" class="form-control" v-model="v.tax_rate">
                                <error-text :error="error[`items.${index}.tax_rate`]"></error-text>
                            </td> -->
                            <td :class="['width-3', errors(`items.${index}.currency_id`)]">
                                <typeahead :initial="v.currency" :trim="80"
                                    @input="onClientCurrencyUpdated(v, index, $event)"
                                    :url="currencyURL"
                                >
                                </typeahead>
                                 <error-text :error="error[`items.${index}.currency_id`]"></error-text>
                            </td>
                            <td>
                                <button class="item-remove" @click="removeClient(v, index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewClient">
                                    Add new Client
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div v-else>
                    <button class="btn btn-success btn-sm" @click="addNewClient">
                        Add new Client
                    </button>
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
    import ImageUploadPreview from '../../components/form/ImageUploadPreview.vue'
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'
    import FileUpload from '../../components/form/FileUpload.vue'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/finished_product/create`,
            'edit': `/api/finished_product/${to.params.id}/edit`,
            'clone': `/api/finished_product/${to.params.id}/edit?mode=clone`,
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
            RollID(){
               return this.form.machines.reduce((carry, item) => {
                       return (Number(item.machine_process_id))
                     //  return carry + 1
                      
               }, 0)
           },
        },
        components: { ErrorText, Typeahead, Spinner, ImageUploadPreview, FileUpload },
        mixins: [ form ],
        data () {
            return {
                form: {
                    items: [],
                    values: [],
                    taxes: [],
                    machines: [],
                    settings: []
                },
                resource: '/finished_product',
                store: '/api/finished_product',
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
                machineUrl: '/api/search/machines',
                machineAttributesUrl: '/api/search/machine_attributes',
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/finished_product/${this.$route.params.id}`
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
            onDocument(e) {
                Vue.set(this.$data.form, 'document', e.target.value)
            },
            removeClient(v, index) {
                this.form.items.splice(index, 1)
            },
            removeTax(v, index) {
                this.form.taxes.splice(index, 1)
            },
            removeMaterial(v, index) {
                this.form.materials.splice(index, 1)
            },

            removeMachine(v, index) {
                this.form.machines.splice(index, 1)
            },

            removeAttribute(v, index) {
                this.form.values.splice(index, 1)
            },

            onReadyFile(name, e) {
                const file = e.target.value

                Vue.set(this.form, name, file)
            },
            onRemoveFile(file) {
                Vue.set(this.form, file, null)
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

            onMachineUpdated(mc, index, e) {
               const machine = e.target.value
                // machines
                Vue.set(this.form.machines[index], 'machinex', machine)
                Vue.set(this.form.machines[index], 'machine_id', machine.id)
                Vue.set(this.form.machines[index], 'machine', machine.name)
                

                Vue.set(this.form.machines[index], 'settings', machine.settings)
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

            addNewMachine() {
                this.form.machines.push({
                    'name': 'Vat',
                    'speed': null,
                    'comment': null,
                    'machine_process_id': this.RollID + 1,
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
            // this.submitMultipartForm(this.form, (data) => {
            //         // this.endProcessing()
            //         this.success()
            //         this.$router.push(`${this.resource}/${data.id}`)
            //     })
            // },
            saveAndNew() {
                this.submit((data) => {
                    const id = Math.random().toString(36).substring(7)
                    this.endProcessing()
                    this.success()
                    this.$router.push(`${this.resource}/create?new=${id}`)
                })
            },
            // this.submitMultipartForm(this.form, (data) => {
            //         const id = Math.random().toString(36).substring(7)
            //         this.endProcessing()
            //         this.success()
            //         this.$router.push(`${this.resource}/create?new=${id}`)
            //     })
            // },
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
.machine_table {
    /* box-shadow: 0px 3px 20px rgb(136 194 65 / 80%); */
    box-shadow: 0px 0px 0px 1px rgb(136 194 65 / 80%);
}

.machine_table > tr > td {padding: 20px 10px 20px 10px !important;}
</style>
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