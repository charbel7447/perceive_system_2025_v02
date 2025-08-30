<template>
    <div>
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{model.code}}</span>
                <div>
                    <router-link :to="`/products`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <a  @click="myFunction(model.id, index)"
                        class="btn" title="Attach Image">
                        <i class="fa fa-paperclip"></i>
                    </a>
                    <router-link target="_blank" :to="`/docs/products/${model.id}/`" class="btn" title="Print Product Label">
                        <i class="fa fa-file-pdf-o "></i>
                    </router-link>

                       <button class="btn" @click="myFunctionLabel(model.id)">
                                     <i class="fa fa-barcode" style="color: #28a745;"></i>
                                     </button>

                                     <button class="btn" @click="myFunctionLots(model.id)">
                                     <i class="fa fa-cubes" style="color: #28a745;"></i>
                                     </button>

                    <router-link :to="`/products/${model.id}/edit`"
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
                                    <img width="200" :src=" '/uploads/'+ model.thumbnail" onerror="this.src='/images/placeholder.png'" />

                                    <hr>
                                    <strong>Status</strong><br><br>
                                    <span>{{model.status}}</span>
                                </div>
                                 <div class="col col-7">
                                    <strong>Description</strong><br><br>
                                    <pre>{{model.title}}</pre>
                                    <hr>
                                    <strong>Category</strong><br><br>
                                    <span>{{model.category.title}}</span>

                                    <hr>
                                    <strong v-if="model.sub_category">Sub Category</strong><br><br>
                                    <span v-if="model.sub_category">{{model.sub_category.text}}</span>
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
                                            {{model.current_stock}}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col col-9">
                                            On Hold Qty
                                        </div>
                                        <div class="col col-3">
                                            {{model.on_hold_qty}}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col col-9">
                                            Remaining Qty
                                        </div>
                                        <div class="col col-3">
                                            {{model.current_stock - model.on_hold_qty}}
                                        </div>
                                    </div>
                                <hr>
                            
                                    <div class="row" v-if="model.min_qty >= model.current_stock">
                                        <div class="col col-12">
                                            <span style="color:red;">
                                                <strong>Alert:</strong>This product reach the minimum stock
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-grey">
                            <h3>Sales Info</h3>
                            <hr>
                            <div class="row">
                                <div class="col col-12">
                                    <!-- <div class="row">
                                        <div class="col col-9">
                                            Currency
                                        </div>
                                        <div class="col col-3">
                                            {{model.currency.code}}
                                        </div>
                                    </div>
                                    <hr> -->
                                    <div class="row">
                                        <div class="col col-9">
                                            Retail Price
                                        </div>
                                        <div class="col col-3">
                                            {{model.unit_price}}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row product_cost_price">
                                        <div class="col col-9">
                                            Cost Price
                                        </div>
                                        <div class="col col-3">
                                            {{model.sale_price}}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row product_profit_value">
                                        <div class="col col-9">
                                             Profit %
                                        </div>
                                        <div class="col col-3">
                                            {{model.rating_value}}
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel" v-if="model.taxes" style="display: none;">
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
        <mini-panel class="product_lots" :resource="lotsURL" :heading="lotsColumns" >
    <div slot="title" class="flex justify-between items-center w-full">
        <span>Product Lots</span>
        
        <!-- Search Input -->
     
       
    </div>
   <router-link :to="`/product_lots?product_id=${model.id}`"
                class="btn btn-secondary btn-sm" slot="create">View All</router-link>
        
    <!-- Table Row -->
    <tr slot-scope="props">
        <td>{{ props.item.code }}</td>
        <td>{{ props.item.product_name }}</td>
        <td>{{ props.item.qty || '-' }}</td>
        <td>{{ props.item.uom.unit || '-' }}</td>
        <td v-if="props.item.vendor">{{ props.item.vendor.company || '-' }}</td>
        <td v-else></td>
        <td>{{ moment(props.item.created_at).format("DD-MM-YYYY") }}</td>
        <td>
            <!-- Edit Button -->
            <button @click.stop="openLabelPDF(props.item.id)" 
                    class="btn btn-primary btn-xs">PDF</button>
            &nbsp;
            <!-- Edit Button -->
            <button @click.stop="openEditModal(props.item)" 
                    class="btn btn-primary btn-xs">Edit</button>

            &nbsp;
                <!-- Delete Button with Confirmation -->
            <button 
                @click.stop="confirmDelete(props.item.id)" 
                class="btn btn-danger btn-xs">
                Delete
            </button>
        </td>
    </tr>
</mini-panel>

<!-- Popup Modal -->
<div v-if="show" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
    <div class="bg-white p-6 rounded shadow-lg w-1/3">
        <h2 class="text-lg font-bold mb-4">Edit Lot</h2>

        <div class="mb-2">
            <label class="block text-sm">Code</label>
            <input v-model="editItem.code" class="border w-full px-2 py-1 rounded"/>
        </div>
        <div class="mb-2">
            <label class="block text-sm">Quantity</label>
            <input v-model="editItem.qty" type="number" class="border w-full px-2 py-1 rounded"/>
        </div>
        
        <div class="flex justify-end gap-2 mt-4">
            <button @click="show = false" class="btn btn-secondary btn-sm">Cancel</button>
            <button @click="saveEdit" class="btn btn-primary btn-sm">Save</button>
        </div>
    </div>
</div>
        <mini-panel class="product_sales_order" :resource="salesOrderURL"
            :heading="salesOrderColumns">
            <div slot="title">
                Sales Orders
            </div>
            <router-link :to="`/sales_orders/create?item_id=${model.id}`"
                class="btn btn-secondary btn-sm" slot="create">Create</router-link>
            <tr slot-scope="props" @click="$router.push(`/sales_orders/${props.item.order_id}`)">
                    <td>{{ props.item.number }}</td>
                    <td>{{ props.item.date }}</td>
                    <td>{{ props.item.price || '-' }}</td>
                    <td>{{ props.item.quantity || '-' }}</td>
                    <td>{{ (props.item.quantity * props.item.price )|| '-' }}</td>
                    <td>{{ props.item.total | formatMoney(props.item.currency)}}</td>
                    <td><sales-order :id="props.item.status_id" /></td>
                </tr>
        </mini-panel>
        <mini-panel class="product_invoice" :resource="invoicesURL"
            :heading="invoicesColumns">
            <div slot="title">
                Invoices
            </div>
            <router-link :to="`/invoices/create?item_id=${model.id}`"
                class="btn btn-secondary btn-sm" slot="create">Create</router-link>
            <tr slot-scope="props" @click="$router.push(`/invoices/${props.item.invoice_id}`)">
                    <td>{{ props.item.number }}</td>
                    <td>{{ props.item.date }}</td>
                    <td>{{ props.item.price || '-' }}</td>
                    <td>{{ props.item.quantity || '-' }}</td>
                    <td>{{ (props.item.quantity * props.item.price )|| '-' }}</td>
                    <td>{{ props.item.total | formatMoney(props.item.currency)}}</td>
                    <td><invoice :id="props.item.status_id" /></td>
                </tr>
        </mini-panel>
        <mini-panel class="product_purchase_order" :resource="purchaseOrderURL"
            :heading="purchaseOrderColumns">
            <div slot="title">
                Purchase Orders
            </div>
            <router-link :to="`/purchase_orders/create?item_id=${model.id}`"
                class="btn btn-secondary btn-sm" slot="create">Create</router-link>
            <tr slot-scope="props" @click="$router.push(`/purchase_orders/${props.item.purchase_order_id}`)">
                    <td>{{ props.item.number }}</td>
                    <td>{{ props.item.date }}</td>
                    <td>{{ props.item.unit_price || '-' }}</td>
                    <td>{{ props.item.qty || '-' }}</td>
                    <td>{{ (props.item.qty * props.item.unit_price )|| '-' }}</td>
                    <td>{{ props.item.total | formatMoney(props.item.currency)}}</td>
                    <td><purchase-order :id="props.item.status_id" /></td>
                </tr>
        </mini-panel>
        <div class="panel product_vendors"  v-if="model.items.length">
            <div class="panel-heading">
                <span class="panel-title">Product Vendors</span>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Vendor</th>
                            <th>Reference</th>
                            <th>Supplier Item Nb</th>
                            <th>Price</th>
                            <th>Currency</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in model.items">
                            <td class="width-6">
                                <router-link :to="`/vendors/${item.vendor_id}`">
                                    {{item.vendor.text}}
                                </router-link>
                            </td>
                            <td class="width-2">{{item.reference}}</td>
                            <td class="width-2">{{item.supplier_item_nb}}</td>
                            <td class="width-2">{{item.price | formatMoney(item.currency)}}</td>
                            <td class="width-2">{{item.currency.code}}</td>
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
    import MiniPanel from '../../components/search/MiniPanel.vue'
    import SalesOrder from '../../components/status/SalesOrder.vue'
    import PurchaseOrder from '../../components/status/PurchaseOrder.vue'
    import Invoice from '../../components/status/Invoice.vue'
    import moment from "moment";
    export default {
        components: {MiniPanel,SalesOrder,PurchaseOrder, Invoice},
        computed: {
            user() {
                return window.apex.user
            },
        },
        data() {
            return {
                moment: moment,
                search: "",    // for filtering
    editItem: {},  // store selected row
                show: false,
                lotsURL: `/api/mini/products/lots/${this.$route.params.id}`,
                lotsColumns: ['Number', 'Name','Quantity','UOM', 'Vendor', 'Date'],
                salesOrderURL: `/api/mini/products/sales_orders/${this.$route.params.id}`,
                salesOrderColumns: ['Number', 'Date', 'Item Price','Quantity','Total Item Amount', 'Total Order Amount', 'Status'],
                purchaseOrderURL: `/api/mini/products/purchase_orders/${this.$route.params.id}`,
                purchaseOrderColumns: ['Number', 'Date', 'Item Price','Quantity', 'Total Item Amount','Total Order Amount', 'Status'],
                invoicesURL: `/api/mini/products/invoices/${this.$route.params.id}`,
                invoicesColumns: ['Number', 'Date', 'Item Price','Quantity', 'Total Item Amount','Total Order Amount', 'Status'],
                model: {
                    currency: {},
                    items: []
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/products/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/products/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            openEditModal(item) {
            this.editItem = { ...item }; // copy row data
            this.show = true;
        },
            confirmDelete(id) {
        if (confirm("Are you sure you want to delete this item?")) {
            window.location.href = `/product_lots/lots/${id}/delete`;
        }
    },
async saveEdit() {
    try {
        await byMethod('put', `/api/products/lots/${this.editItem.id}`, this.editItem);
        this.show = false; // close modal
        this.$message.success("Lot updated successfully!");

        // Reload the current product page
        window.location.href = `/products/${this.model.id}`;

    } catch (e) {
        alert("Error saving changes");
    }
},

            myFunction(e){		
                window.open('/products/'+ e + '/product_image', "_blank",`width=1024,height=800`);	
            },
            myFunctionLabel(e){		
                 window.open('/docs/label_barcode/'+ e, "_blank",`width=600,height=400, left=400, top=250`);	
             },
            myFunctionLots(e){		
                 window.open('/docs/receive_label_barcode_id/'+ e, "_blank",`width=600,height=400, left=400, top=250`);	
             },
             openLabelPDF(e){		
                 window.open('/docs/receive_label_barcode_lot_id/'+ e, "_blank",`width=600,height=400, left=400, top=250`);	
             },
            deleteModel() {
                const r = confirm("Are you sure!")
                if(r != true) { return }
                this.$bar.start()
                byMethod('delete', `/api/products/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/products')
                            this.$message.success(`You have successfully deleted quotation!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Products - ${this.model.code}`)
                this.$bar.finish()
                // this.show = true
            }
        }
    }
</script>
