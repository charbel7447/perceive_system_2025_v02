<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{model.text}}</span>
                <div>
                    <router-link :to="`/subsubcategories`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/subsubcategories/${model.id}/edit`"  
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                      <router-link :to="`/docs/subsubcategories/${model.id}/`"
                        class="btn" title="Report" target="_blank">
                        <i class="fa fa-download"></i>
                    </router-link>
                    <a @click.stop="deleteModel" class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-12">
                        <div class="bg-grey">
                            <div class="row">
                               
                                <div class="col col-4">
                                    <strong> Category Name</strong>
                                    <p>{{model.name}}</p>
                                </div>
                            
                                
                                <div class="col col-4">
                                    <strong>Description</strong>
                                    <p>{{model.description}}</p>
                                </div>
                            
                               
                                <div class="col col-4">
                                    <strong>Category Code</strong>
                                    <p>{{model.number}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
         <mini-panel :resource="productURL"
            :heading="productColumns">
            <div slot="title">
                Products
            </div>
            <router-link :to="`/products/create?items_id=${model.id}`"
                class="btn btn-secondary btn-sm" slot="create">Create</router-link>
            <tr slot-scope="props" @click="$router.push(`/products/${props.item.id}`)">
                    <td>{{ props.item.code }}</td>
                    <td>{{ props.item.description | trim(90) }}</td>
                    <td>{{ props.item.qty_on_hand || '-' }}</td>
                    <td>{{ props.item.uom.unit || '-' }}</td>
                    <td>{{ props.item.warehouse.name || '-' }}</td>
                    <td>{{ props.item.category.name || '-' }}</td>
                    <td><span v-for="supplier in props.item.product">{{ supplier.vendor.company  || '-' }}
                        <br><small>({{ supplier.vendor.person  || '-' }})</small></span></td>
                    <!-- <td>{{ props.item.unit_price | formatMoney()}}</td> -->
                    <td>{{ props.item.status | trim(90) }}</td>
                    <td>{{ moment(props.item.created_at).format("DD-MM-YYYY") || '-' }}</td>
                </tr>
        </mini-panel>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import moment from "moment";
    import { get, byMethod } from '../../lib/api'
    import MiniPanel from '../../components/search/MiniPanel.vue'
    export default {
         computed: {
            user() {
                return window.apex.user
            },
        },
        components: {MiniPanel,},
        data() {
            return {
                show: false,
                productURL: `/api/mini/subsubcategories/products/${this.$route.params.id}`,
                productColumns: ['Item Code', 'Material Name', 'Quantity','UOM', 'Warehouse', 'Category','Vendor Name','Status','Date'],
                model: {
                    currency: {}
                },
                moment: moment,
                stats: {}
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/subsubcategories/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/subsubcategories/${to.params.id}`)
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
                byMethod('delete', `/api/subsubcategories/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/subsubcategories')
                            this.$message.success(`You have successfully deleted Category!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                Vue.set(this.$data, 'stats', res.data.stats)
                this.$title.set(`Category - ${this.model.text}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
