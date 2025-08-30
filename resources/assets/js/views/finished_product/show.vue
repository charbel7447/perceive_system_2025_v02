<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{model.code}}</span>
                <div>
                    <router-link :to="`/finished_product`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link target="_blank" :to="`../docs/finished_product/${model.id}/`" class="btn" title="Print Product DataSheet">
                        <i class="fa fa-file-pdf-o"></i>
                    </router-link>
                    <router-link :to="`/finished_product/${model.id}/finish_product_image`"
                        class="btn" title="Attach Image">
                        <i class="fa fa-paperclip"></i>
                    </router-link>

                    <router-link :to="`/finished_product/${model.id}/edit`"
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                    <a @click.stop="deleteModel" class="btn btn-danger" >
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
            
            <div class="panel-body">
                <div class="row">
                    <div class="col col-8">
                        <div class="bg-grey">
                            <div class="row">
                                <div class="col col-5">
                                    <strong>Item Code</strong><br><br>
                                    <span>{{model.code}}</span>
                                    <hr>
                                    <strong>Generated Code</strong><br><br>
                                    <span>{{model.generated_code}}</span>
                                    <hr>
                                    
                                    <strong>minimum_stock</strong><br><br>
                                    <span>{{model.minimum_stock}}</span>
                                    <hr>
                                    <strong>Currency</strong><br><br>
                                    <span>{{model.currency.text}}</span>
                                    <hr>
                                    <strong>Product Type</strong><br><br>
                                     <span>{{model.type.code}} - {{model.type.name}}</span>
                                  
                                </div>
                                 <div class="col col-7">
                                    <strong>Description</strong><br><br>
                                    <pre>{{model.description}}</pre>
                                    <hr>
                                    <strong>UOM</strong><br><br>
                                    <span>{{model.uom.unit}}</span>
                                     <hr>
                                    <strong>Warehouse</strong><br><br>
                                    <span>{{model.warehouse}}</span>
                                    <hr>
                                    <strong>Raw Material Type</strong><br>
                                    <table class="items">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Type
                                                </th>
                                                <th>
                                                    Percentage
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="rm in model.materials">
                                                <td>
                                                    {{rm.material.code}} - {{rm.material.name}} 
                                                </td>
                                                <td>
                                                        {{rm.percentage}} %
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="bg-grey">
                            <h3>Inventory</h3>
                            <hr>
                            <div class="row">
                                <div class="col col-12">
                                    <div class="row">
                                        <div class="col col-9">
                                            Qty
                                        </div>
                                        <div class="col col-3">
                                            {{model.qty_on_hand}}
                                        </div>
                                    </div>
                                </div>  
                                <hr>
                                <div class="col col-12" style="display:none;">
                                    <div class="row" v-if="model.minimum_stock >= model.qty_on_hand">
                                        <div class="col col-12">
                                            <span style="color:red;">
                                                <strong>Alert:</strong>This product reach the minimum stock
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-grey" style="display:non;">
                            <h3>Attributes</h3>
                            <hr>
                            <div class="row">
                                <div class="col col-12">
                                    <div class="row">
                                        <div class="col col-9">
                                            <tr width="10%">
                                                <td   v-for="z in model.attributes" style="padding:0 10px;border-right: 1px solid;">
                                                   {{z.attribute_name.description}}
                                                </td>
                                            </tr>
                                            <tr width="10%">
                                            <td>
                                                &nbsp;
                                            </td>
                                            </tr>
                                            <tr width="10%">    
                                                <td v-for="x in model.values" style="padding:0 10px;border-right: 1px solid;">
                                                    {{x.attribute_value}}
                                                </td>
                                            </tr>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel" v-if="model.document">
                            <div class="panel-heading">
                                <span class="panel-title">Uploaded Document</span>
                            </div>
                            <div class="panel-body">
                                <a :href="`/uploads/${model.document}`" target="_blank">
                                    <iframe class="panel-image" :src="`/uploads/${model.document}`" style="max-width: 350px;"></iframe>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel" v-if="model.taxes.length">
            <div class="panel-heading">
                <span class="panel-title">Product Taxes</span>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tax Name</th>
                            <th>Rate</th>
                            <th>Tax Authority</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in model.taxes">
                            <td class="width-4">{{item.name}}</td>
                            <td class="width-2">{{item.rate}}%</td>
                            <td class="width-6">{{item.tax_authority}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel" v-if="model.items.length">
            <div class="panel-heading">
                <span class="panel-title">Product Clients</span>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Reference</th>
                            <th>Price</th>
                            <th>Currency</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in model.items">
                            <td class="width-6">
                                <router-link :to="`/clients/${item.client_id}`">
                                    {{item.client.text}}
                                </router-link>
                            </td>
                            <td class="width-2">{{item.reference}}</td>
                            <td class="width-2">{{item.price | formatMoney(item.currency)}}</td>
                            <td class="width-2">{{item.currency.text}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel" v-if="model.machines.length">
            <div class="panel-heading">
                <span class="panel-title">Machines</span>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Machine</th>
                            <th>Seq</th>
                            <th>Speed</th>
                            <th>Comment</th>

                            <th>Settings</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ma in model.machines">
                            <td class="width-6">
                                <router-link :to="`/machines/${ma.machine_id}`">
                                    {{ma.machinex.name}}
                                </router-link>
                            </td>
                            <td class="width-2">{{ma.machine_process_id}}</td>
                            <td class="width-2">{{ma.speed}}</td>
                            <td class="width-2">{{ma.comment}}</td>
                            <td>
                               <tr v-for="mass in ma.settings">
                                    <td class="width-6">
                                    {{mass.settings_id}}
                                    </td>
                                    <td class="width-6">
                                    {{mass.settings_name}}
                                    </td>
                                    <td class="width-6">
                                    {{mass.settings_value}}
                                    </td>
                                </tr>
                            </td>
                        </tr>
                      
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, byMethod } from '../../lib/api'
    export default {
        computed: {
            user() {
                return window.apex.user
            },
        },
        data() {
            return {
                show: false,
                model: {
                    currency: {},
                    items: []
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/finished_product/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/finished_product/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            deleteModel() {
                const r = confirm("Are you sure!")
                if(r != true) { return }
                this.$bar.start()
                byMethod('delete', `/api/finished_product/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/finished_product')
                            this.$message.success(`You have successfully deleted quotation!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`finished_product - ${this.model.code}`)
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