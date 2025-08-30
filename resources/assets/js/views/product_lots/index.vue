<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Product Lots</span>
              <router-link slot="create" to="/products/" class="btn btn-primary">
                Products 
            </router-link>
            &nbsp;
            <router-link style="margin: 0 5px;" slot="create" to="/product_lots" class="btn btn-secondary">
                Clear
            </router-link>
            <tr slot-scope="props" >
                <td >{{ props.item.id }}</td>        
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
                                 <button 
                @click.stop="confirmDelete(props.item.id)" 
                class="btn btn-danger btn-xs">
                Delete
            </button>
                </td>
            </tr>
        </panel>
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
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import { get } from '../../lib/api'
    import moment from "moment";

    export default {
        components: { Panel },
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
                resource: '/product_lots',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Number', name: 'code', sort: true},
                    {title: 'Name', name: 'product_name', sort: true},
                    {title: 'Quantity', name: 'qty', sort: true},
                    {title: 'UOM', name: 'uom_id', sort: true},
                    {title: 'Vendor', name: 'vendor_id', sort: true},
                    {title: 'Date by', name: 'created_at', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/product_lots', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/product_lots', to.query)
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
             openLabelPDF(e){		
                 window.open('/docs/receive_label_barcode_lot_id/'+ e, "_blank",`width=600,height=400, left=400, top=250`);	
             },
            setData(res) {
                this.$title.set('Product Lots')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
