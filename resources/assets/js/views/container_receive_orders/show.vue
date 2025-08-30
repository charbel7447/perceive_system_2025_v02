<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Receive Order {{model.number}}</span>
                <div>
                    <router-link :to="`/container_receive_orders`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <div class="btn-group">
                        <a :href="`/docs/container_receive_orders/${model.id}`" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a :href="`/docs/container_receive_orders/${model.id}?mode=download`" target="_blank"
                            class="btn" title="Download PDF">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="document">
                    <div class="document-heading">
                        <div class="row">
                            <div class="col col-4">
                                <p><strong>Received From:</strong></p>
                                <router-link :to="`/shippers/${model.shipper_id}`">
                                    <span>{{model.shipper.person}}</span><br>
                                    <span>{{model.shipper.company}}</span><br>
                                    <pre>{{model.shipper.billing_address}}</pre>
                                </router-link>
                            </div>
                            <div class="col col-4">
                                &nbsp;
                            </div>
                            <div class="col col-4">
                                <table class="document-summary">
                                    <tbody>
                                        <tr>
                                            <td>Status</td>
                                            <td><status :id="model.status_id" /></td>
                                        </tr>
                                        <tr>
                                            <td>Date:</td>
                                            <td>{{model.date}}</td>
                                        </tr>
                                        <tr>
                                            <td>Number:</td>
                                            <td>{{model.number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Date:</td>
                                            <td>{{model.date}}</td>
                                        </tr>
                                        <tr>
                                            <td>Container Order:</td>
                                            <td>
                                                <router-link :to="`/container_orders/${model.container_order_id}`">
                                                    {{model.container_order.number}}
                                                </router-link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="document-body">
                        <table class="document-table">
                            <thead>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Item Description</th>
                                    <th class="align-center">Qty Received</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in model.items">
                                    <td class="width-2">
                                        {{item.product.code}}
                                    </td>
                                    <td class="width-4">
                                        <router-link :to="`/products/${item.product_id}`">
                                            <pre>{{item.product.description}}</pre>
                                        </router-link>
                                    </td>
                                    <td class="width-1 align-center">
                                        {{item.quantity}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="document-footer row">
                        <div class="col col-9">
                            <strong>Internal Note</strong>
                            <div class="document-terms">
                                <pre>{{model.note}}</pre>
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
    import Status from '../../components/status/ReceiveOrder.vue'
    import {Dropdown} from '../../components/dropdown'
    export default {
        components: { Status, Dropdown },
        data() {
            return {
                show: false,
                model: {
                    items: [],
                    currency: {},
                    shipper: {},
                    container_order: {}
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/container_receive_orders/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/container_receive_orders/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            deleteModel() {
                this.$refs.more.close()
                const r = confirm("Are you sure!")
                if(r != true) { return }
                this.$bar.start()
                byMethod('delete', `/api/container_receive_orders/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/container_receive_orders')
                            this.$message.success(`You have successfully deleted receive order!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            markAs(status) {
                this.$refs.more.close()
                this.$bar.start()
                post(`/api/container_receive_orders/${this.model.id}/mark`, {status: status})
                    .then(({data}) => {
                        if(data.saved) {
                            Vue.set(this.model, 'status_id', data.status_id)
                            Vue.set(this.model, 'is_editable', data.is_editable)
                            this.$message.success(`You have successfully marked receive order!`)
                        }
                        this.$bar.finish()
                    })

            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Receive Order - ${this.model.number}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
