<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{model.code}}</span>
                <div>
                    <router-link :to="`/products_aggregation`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link target="_blank" :to="`../docs/products_aggregation/${model.id}/`" class="btn" title="Print Product Label">
                        <i class="fa fa-barcode"></i>
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
                                    <strong>Category</strong><br><br>
                                    <span>{{model.category.name}}</span>
                                    <hr>
                                    <strong>Sub Category</strong><br><br>
                                    <span>{{model.sub_category.name}}</span>
                                    <hr>
                                </div>
                                 <div class="col col-7">
                                    <strong>Description</strong><br><br>
                                    <pre>{{model.description}}</pre>
                                    <hr>
                                    <strong>UOM</strong><br><br>
                                    <span>{{model.uom_id}}</span>
                                     <hr>
                                    <strong>Warehouse</strong><br><br>
                                    <span>{{model.warehouse.name}}</span>
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
                                            {{model.current_stock}} - {{model.uom_id}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-12">
                         <hr>
                                    <strong>From Products</strong><br><br>
                                    <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Code - Description</th>
                                            <th>Quantites</th>
                                            <th>U.O.M</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in model.items">
                                        <td class="width-4">{{item.product_code}} - {{item.product_name}}</td>
                                        <td class="width-2">{{item.current_stock}}</td>
                                        <td class="width-6">{{item.uom_id}}</td>
                                    </tr>
                                    </tbody>
                                    </table>
                    </div>
                </div>
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
            get(`/api/products_aggregation/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/products_aggregation/${to.params.id}`)
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
                byMethod('delete', `/api/products_aggregation/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/products_aggregation')
                            this.$message.success(`You have successfully deleted quotation!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`products_aggregation - ${this.model.code}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
