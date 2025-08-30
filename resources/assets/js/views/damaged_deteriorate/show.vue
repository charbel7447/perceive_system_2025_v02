<template>
    <div>
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{model.number}}</span>
                <div>
                    <router-link :to="`/damaged_deteriorate`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                     <a  v-if="model.status_id === 3 || user.id === model.manager_id" :href="`/docs/damaged_deteriorate/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                    <a @click.stop="deleteModel" class="btn btn-danger" v-if="user.is_transfer_delete == 1 && model.status_id === 1">
                        <i class="fa fa-trash"></i>
                    </a>
                    <a  :href="`/docs/damaged_deteriorate/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                    <dropdown title="Action" ref="more" style="display:non">
                        <li v-if="(user.id == 1)">
                            <a @click.stop="markAs(3)">Mark as Confirmed</a>
                            
                        </li>
                        <li>
                            <a @click.stop="deleteModel">Delete</a>
                        </li>
                    </dropdown>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-12">
                        <div class="bg-grey">
                            <div class="row">
                                <div class="col col-5">
                                    <strong>Transfer Number</strong><br><br>
                                    <span>{{model.number}}</span>
                                    <hr>
                                    <strong>Products</strong><br><br>
                                     <span v-for="pro in model.items">
                                         <li>{{pro.product.code}} - {{pro.product.description}}</li>
                                     </span>
                                </div>
                                 <div class="col col-7">
                                    <strong>End User</strong><br><br>
                                     <span>{{model.created_by}}</span>
                                    <span v-for="pro in model.items">
                                         <li><strong>{{pro.product.code}} - {{pro.product.description}}</strong>  ---Transferred To: --- <strong>Damaged Warehouse</strong></li>
                                     </span>
                                    <!-- <pre>{{model.employee.name}}</pre> -->
                                    <hr>
                                    <strong>Transfered Quantity</strong><br><br>
                                    <table class="items">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Transfered Qty</th>
                                                 <th>U.O.M</th>
                                              
                                            </tr>
                                        </thead>
                                        <tbody>
                                                <tr v-for="pro in model.items">
                                                    <td>{{pro.product.code}} - {{pro.product.description}}</td>
                                                    <td>{{pro.transfer_qty}}</td>
                                                    <td>{{pro.uom1.unit}}</td>
                                                </tr>
                                        </tbody>
                                    </table>
                                    <!-- <span>{{model.transfer_qty}}</span> -->
                                    <hr>
                                    <strong>Transfer Date</strong><br><br>
                                    <span>{{model.date}}</span>
                                    <hr>
                                    <strong>Document By</strong><br><br>
                                    <span>{{model.created_by}} - {{model.created_at}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, post, byMethod } from '../../lib/api'
    import Status from '../../components/status/Transfer.vue'
    import {Dropdown} from '../../components/dropdown'
    export default {
        computed: {
            user() {
                return window.apex.user
            },
        },
        components: { Status, Dropdown },
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
            get(`/api/damaged_deteriorate/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/damaged_deteriorate/${to.params.id}`)
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
                byMethod('delete', `/api/damaged_deteriorate/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/damaged_deteriorate')
                            this.$message.success(`You have successfully deleted transfer!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
             markAs(status) {
                this.$refs.more.close()
                this.$bar.start()
                post(`/api/damaged_deteriorate/${this.model.id}/mark`, {status: status})
                    .then(({data}) => {
                        if(data.saved) {
                            Vue.set(this.model, 'status_id', data.status_id)
                            Vue.set(this.model, 'is_editable', data.is_editable)
                            this.$message.success(`You have successfully marked transfer!`)
                        }
                        this.$bar.finish()
                    })

            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`damaged_deteriorate - ${this.model.number}`)
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